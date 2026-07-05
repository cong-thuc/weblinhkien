<?php
namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Để dùng hàm xóa/lưu ảnh

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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:components,code',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Bắt buộc phải là file ảnh
        ]);

        $data = $request->all();

        // Xử lý lưu hình ảnh nếu có upload
        if ($request->hasFile('image')) {
            // Ảnh sẽ được lưu vào thư mục storage/app/public/components
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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            // Dòng code dưới đây giúp bỏ qua kiểm tra trùng lặp cho chính mã code hiện tại
            'code' => 'required|string|unique:components,code,' . $component->id,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Xử lý nếu người dùng upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ trong thư mục nếu nó tồn tại
            if ($component->image) {
                Storage::disk('public')->delete($component->image);
            }
            // Lưu ảnh mới
            $data['image'] = $request->file('image')->store('components', 'public');
        }

        $component->update($data);

        return redirect()->route('components.index')->with('success', 'Cập nhật linh kiện thành công!');
    }

    // 6. Xóa linh kiện
    public function destroy(Component $component)
    {
        // Phải xóa file ảnh vật lý trước khi xóa dữ liệu trong DB
        if ($component->image) {
            Storage::disk('public')->delete($component->image);
        }
        
        $component->delete();

        return redirect()->route('components.index')->with('success', 'Đã xóa linh kiện thành công!');
    }
}