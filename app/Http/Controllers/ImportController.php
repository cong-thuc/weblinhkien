<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Component;
use App\Models\Supplier; // Hãy đảm bảo bạn đã tạo Model Supplier nhé

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách phiếu nhập, sắp xếp mới nhất lên đầu, phân trang 10 dòng/trang
        $imports = Import::orderBy('id', 'desc')->paginate(10);
        
        return view('imports.index', compact('imports'));
    }

    public function show(string $id)
    {
        // Lấy thông tin phiếu nhập kèm theo chi tiết và tên linh kiện
        // (Yêu cầu Model Import đã có hàm details(), Model ImportDetail đã có hàm component())
        $import = Import::with('details.component')->findOrFail($id);

        return view('imports.show', compact('import'));
    }

    
    public function create()
    {
        // Lấy danh sách linh kiện và nhà cung cấp truyền ra giao diện HTML
        $components = Component::all();
        $suppliers = Supplier::all(); // Xóa dòng này nếu bạn chưa cần đến Nhà cung cấp

        // Trả về view resources/views/imports/create.blade.php
        return view('imports.create', compact('components', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào (Validation)
        $request->validate([
            'component_id' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);

        try {
            // Sử dụng Transaction: Nếu có lỗi ở bất kỳ dòng code nào, database sẽ tự động hoàn tác
            DB::transaction(function () use ($request) {
                
                // 2. Tạo phiếu nhập chung (Lưu vào bảng imports)
                $import = Import::create([
                    // Sử dụng hàm now() của Laravel để lấy ngày giờ hiện tại
                    'import_date' => now(), 
                    'note' => $request->note, 
                    // Nếu sau này bạn dùng lại supplier_id hay ghi chú thì bỏ comment ra nhé
                    // 'supplier_id' => $request->supplier_id, 
                   
                ]);

                // 3. Tạo chi tiết phiếu nhập (Lưu vào bảng import_details)
                ImportDetail::create([
                    'import_id' => $import->id,
                    'component_id' => $request->component_id,
                    'quantity' => $request->quantity,
                    'price' => $request->price ?? 0,
                ]);

                // 4. Cập nhật số lượng (Cộng dồn vào bảng components)
                $component = Component::findOrFail($request->component_id);
                $component->increment('quantity', $request->quantity); 
                // Chú ý: Nếu cột số lượng trong bảng components của bạn không phải tên 'quantity', hãy sửa lại chữ 'quantity' ở dòng trên.
            });

            // Nếu thành công, quay lại trang trước và báo thành công
            return back()->with('success', 'Nhập kho thành công & đã cộng dồn số lượng!');

        } catch (\Exception $e) {
            // Nếu lỗi, quay lại trang trước và báo lỗi chi tiết
            return back()->with('error', 'Lỗi nhập kho: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}