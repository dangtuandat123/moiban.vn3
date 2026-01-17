<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * AdminTemplateController - Quản lý templates
 */
class AdminTemplateController extends Controller
{
    protected TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    /**
     * Danh sách templates
     */
    public function index()
    {
        $templates = Template::withCount('userCards')
            ->orderBy('sort_order')
            ->get();

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Form tạo template mới
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Lưu template mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:templates,code|regex:/^[a-z0-9-]+$/',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['code', 'name', 'description', 'is_premium', 'is_active']);
        $data['schema'] = [];
        $data['sort_order'] = Template::max('sort_order') + 1;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('templates/thumbnails', 'public');
        }

        Template::create($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Đã tạo template mới');
    }

    /**
     * Form sửa template
     */
    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Cập nhật template
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'schema' => 'nullable|json',
        ]);

        $data = $request->only(['name', 'description', 'is_premium', 'is_active']);

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ
            if ($template->thumbnail) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('templates/thumbnails', 'public');
        }

        if ($request->filled('schema')) {
            $data['schema'] = json_decode($request->schema, true);
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Đã cập nhật template');
    }

    /**
     * Toggle active
     */
    public function toggleActive(Template $template)
    {
        $template->is_active = !$template->is_active;
        $template->save();

        return back()->with('success', $template->is_active ? 'Đã bật template' : 'Đã tắt template');
    }

    /**
     * Xóa template
     */
    public function destroy(Template $template)
    {
        // Kiểm tra có thiệp nào đang dùng không
        if ($template->userCards()->count() > 0) {
            return back()->with('error', 'Không thể xóa template đang được sử dụng');
        }

        if ($template->thumbnail) {
            Storage::disk('public')->delete($template->thumbnail);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Đã xóa template');
    }

    /**
     * Sync templates từ thư mục
     */
    public function sync()
    {
        $count = $this->templateService->syncFromDirectory();

        return back()->with('success', "Đã sync {$count} templates");
    }
}
