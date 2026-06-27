<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị danh sách
    public function index()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    // Không dùng trang create riêng
    public function create()
    {
        return redirect()->route('category.index');
    }

    // Lưu dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('category.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    // Không dùng
    public function show(string $id)
    {
        //
    }

    // Hiển thị form sửa
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        return view('category.edit', compact('category'));
    }

    // Cập nhật
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('category.index')
            ->with('success', 'Cập nhật thành công!');
    }

    // Xóa
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')
            ->with('success', 'Xóa thành công!');
    }
}