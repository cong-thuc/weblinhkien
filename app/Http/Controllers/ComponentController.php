<?php
namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class ComponentController extends Controller
{
    // 1. Giao diện danh sách linh kiện
    public function index()
    {
        $components = Component::with('category')->latest()->paginate(10);
        return view('components.index', compact('components'));
    }

    // 2. Giao diện form Thêm mới
    public function create()
    {
        $categories = Category::all(); 
        return view('components.create', compact('categories'));
    }

    // 3. Xử lý lưu linh kiện vào DB
    public function store(Request $request)
    {
        // 🌟 ĐÃ SỬA: Đổi 'user_id' thành 'creator_name' để khớp với form gõ tên
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'creator_name' => 'required|string|max:255', 
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:components,code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ], [
            'code.unique' => 'Mã linh kiện (Code) này đã tồn tại! Vui lòng nhập mã khác',
            'code.required' => 'Vui lòng nhập mã linh kiện (Code)',
            'creator_name.required' => 'Vui lòng nhập tên người thực hiện',
            'name.required' => 'Vui lòng nhập tên linh kiện',
            'category_id.required' => 'Vui lòng chọn danh mục cho linh kiện',
            'category_id.exists' => 'Danh mục đã chọn không tồn tại trong hệ thống',
            'creator_name.required' => 'Vui lòng nhập tên người thực hiện',
            'image.image' => 'File tải lên phải là định dạng hình ảnh',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ]);

        $data = $request->all();

        // Ép số lượng = 0 khi mới tạo.
        $data['quantity'] = 0; 
        $data['price'] = 0; 

        // Xử lý lưu hình ảnh nếu có upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('components', 'public');
            $data['image'] = $imagePath;
        }

        Component::create($data);

        return redirect()->route('components.index')->with('success', 'Thêm linh kiện thành công!');
    }

    // 4. Giao diện form Chỉnh sửa
    public function edit(Component $component)
    {
        $categories = Category::all();
        return view('components.edit', compact('component', 'categories'));
    }

    // 5. Xử lý lưu dữ liệu cập nhật
    public function update(Request $request, Component $component)
    {
        // 🌟 ĐÃ SỬA: Cập nhật luôn cho form Sửa
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'creator_name' => 'required|string|max:255', 
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:components,code,' . $component->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'code.unique' => 'Mã linh kiện này đã tồn tại trong hệ thống. Vui lòng nhập mã khác!',
            'creator_name.required' => 'Vui lòng nhập tên người thực hiện.',
        ]);

        $data = $request->except(['quantity', 'price']);

        if ($request->hasFile('image')) {
            if ($component->image) {
                Storage::disk('public')->delete($component->image);
            }
            $data['image'] = $request->file('image')->store('components', 'public');
        }

        $component->update($data);

        return redirect()->route('components.index')->with('success', 'Cập nhật linh kiện thành công!');
    }

    // 6. Xóa linh kiện
    public function destroy(Component $component)
    {
        $hasImports = \App\Models\ImportDetail::where('component_id', $component->id)->exists();
        $hasExports = \App\Models\ExportDetail::where('component_id', $component->id)->exists();

        if ($hasImports || $hasExports) {
            return back()->with('error', 'Lỗi: Không thể xóa linh kiện này vì đã có lịch sử Giao dịch (Nhập/Xuất kho).');
        }

        if ($component->image) {
            Storage::disk('public')->delete($component->image);
        }
        
        $component->delete();

        return redirect()->route('components.index')->with('success', 'Đã xóa linh kiện thành công!');
    }
}