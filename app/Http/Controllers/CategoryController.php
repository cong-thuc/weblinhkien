<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // 2. Lưu danh mục mới vào DB
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // 3. Cập nhật thông tin danh mục
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    // 4. Xóa danh mục
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
    }
    // Hàm để hiển thị form chỉnh sửa
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }
}