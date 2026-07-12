<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Export;
use App\Models\ExportDetail; // <--- Cứu tinh của bạn đây!
use App\Models\Component;


class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách phiếu xuất, sắp xếp mới nhất lên đầu
        $exports = Export::orderBy('id', 'desc')->paginate(10);
        
        return view('exports.index', compact('exports'));
    }

    public function show(string $id)
    {
        // Lấy phiếu xuất kèm theo danh sách chi tiết và thông tin linh kiện
        $export = Export::with('details.component')->findOrFail($id);

        return view('exports.show', compact('export'));
    }


    public function create()
    {
        // Lấy danh sách linh kiện để chọn
        $components = Component::all();
        return view('exports.create', compact('components'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'component_id' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // TÌM LINH KIỆN ĐỂ KIỂM TRA TỒN KHO TRƯỚC
                $component = Component::findOrFail($request->component_id);

                // Nếu số lượng muốn xuất lớn hơn số lượng đang có -> Báo lỗi ngay!
                if ($request->quantity > $component->quantity) {
                    throw new \Exception("Thất bại! Trong kho chỉ còn {$component->quantity} sản phẩm, không đủ để xuất.");
                }

                // A. Tạo phiếu xuất chung (Bảng exports)
                $export = Export::create([
                    'note' => $request->note, 
                    'export_date' => now(), // Bỏ comment nếu bảng exports của bạn có cột export_date
                ]);

                // B. Tạo chi tiết phiếu xuất (Bảng export_details)
                ExportDetail::create([
                    'export_id' => $export->id,
                    'component_id' => $request->component_id,
                    'quantity' => $request->quantity,
                    'price' => $request->price ?? 0, // Giá xuất
                ]);

                // C. TRỪ SỐ LƯỢNG KHO (Dùng hàm decrement thay vì increment)
                $component->decrement('quantity', $request->quantity); 
            });

            // Nếu thành công thì quay về trang danh sách
            return redirect()->route('exports.index')->with('success', 'Xuất kho thành công & đã trừ số lượng!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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
