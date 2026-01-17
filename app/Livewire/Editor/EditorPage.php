<?php

namespace App\Livewire\Editor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\UserCard;
use App\Models\Template;
use App\Services\CardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * EditorPage - Livewire component cho Split-view Editor
 */
class EditorPage extends Component
{
    use WithFileUploads;

    public UserCard $card;
    public Template $template;
    public array $schema = [];
    public array $content = [];
    public string $activeSection = '';
    
    // Temporary uploads
    public $tempImage;
    public $tempAudio;

    // State
    public bool $isSaving = false;
    public string $saveStatus = '';

    protected $listeners = ['contentUpdated' => 'handleContentUpdate'];

    public function mount(UserCard $card)
    {
        // Kiểm tra quyền sở hữu
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $this->card = $card;
        $this->template = $card->template;
        $this->schema = $this->template->getSchemaSections();
        $this->content = $card->content ?? $this->template->getDefaultContent();
        
        // Set active section đầu tiên
        if (!empty($this->schema)) {
            $this->activeSection = $this->schema[0]['section_id'] ?? '';
        }
    }

    /**
     * Cập nhật content field
     */
    public function updateField(string $key, $value)
    {
        $this->content[$key] = $value;
        $this->saveStatus = 'Chưa lưu';
    }

    /**
     * Handle content update từ JS
     */
    public function handleContentUpdate(string $key, $value)
    {
        $this->updateField($key, $value);
    }

    /**
     * Upload ảnh
     */
    public function uploadImage(string $key)
    {
        $this->validate([
            'tempImage' => 'image|max:5120', // 5MB max
        ]);

        if ($this->tempImage) {
            // Resize ảnh nếu cần (max 1920px width)
            $path = $this->tempImage->store("cards/{$this->card->id}", 'public');
            
            $this->content[$key] = $path;
            $this->tempImage = null;
            $this->saveStatus = 'Chưa lưu';
        }
    }

    /**
     * Upload audio
     */
    public function uploadAudio(string $key)
    {
        $this->validate([
            'tempAudio' => 'file|mimes:mp3,wav|max:10240', // 10MB max
        ]);

        if ($this->tempAudio) {
            $path = $this->tempAudio->store("cards/{$this->card->id}/audio", 'public');
            
            $this->content[$key] = $path;
            $this->tempAudio = null;
            $this->saveStatus = 'Chưa lưu';
        }
    }

    /**
     * Lưu thay đổi
     */
    public function save()
    {
        $this->isSaving = true;
        
        try {
            $this->card->content = $this->content;
            $this->card->save();
            
            $this->saveStatus = 'Đã lưu!';
            $this->dispatch('saved');
        } catch (\Exception $e) {
            $this->saveStatus = 'Lỗi: ' . $e->getMessage();
        }
        
        $this->isSaving = false;
    }

    /**
     * Chuyển section
     */
    public function setActiveSection(string $sectionId)
    {
        $this->activeSection = $sectionId;
    }

    /**
     * Lấy URL preview
     */
    public function getPreviewUrlProperty()
    {
        return route('cards.public', $this->card->slug) . '?preview=1';
    }

    /**
     * Lấy field config theo key
     */
    public function getFieldConfig(string $key): ?array
    {
        foreach ($this->schema as $section) {
            foreach ($section['fields'] ?? [] as $field) {
                if ($field['key'] === $key) {
                    return $field;
                }
            }
        }
        return null;
    }

    public function render()
    {
        return view('livewire.editor.editor-page')
            ->layout('layouts.app', ['title' => 'Chỉnh sửa thiệp']);
    }
}
