<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Export;
use App\Models\ExportDetail;
use App\Models\Component;
use App\Models\Location; 
use App\Models\User;

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
        $users = User::all();
        $locations = Location::all();

        // --- PHẦN TẠO MẢNG DỮ LIỆU ĐỂ BẮN SANG JAVASCRIPT ---
        // Mảng chứa dữ liệu: Linh kiện A nằm ở Kệ B còn bao nhiêu cái
        $stockDetails = []; 

        foreach ($components as $comp) {
            $stockDetails[$comp->id] = [];
            foreach ($locations as $loc) {
                // Đếm tổng nhập của linh kiện NÀY ở vị trí NÀY
                $imported = \App\Models\ImportDetail::where('component_id', $comp->id)
                            ->where('location_id', $loc->id)->sum('quantity');
                
                // Đếm tổng xuất của linh kiện NÀY ở vị trí NÀY
                $exported = \App\Models\ExportDetail::where('component_id', $comp->id)
                            ->where('location_id', $loc->id)->sum('quantity');
                
                $stock = $imported - $exported;

                // Nếu kệ này có chứa linh kiện đang xét, thì mới nạp vào mảng
                if ($stock > 0) {
                    $stockDetails[$comp->id][] = [
                        'id' => $loc->id,
                        'name' => $loc->name,
                        'stock' => $stock
                    ];
                }
            }
        }
        // ---------------------------------------------------------------

        // Nhớ truyền thêm stockDetails và users ra view
        return view('exports.create', compact('components', 'stockDetails', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu mảng nhiều linh kiện và người xuất
        $request->validate([
            'exporter_name' => 'required|string|max:255',
            'note'          => 'nullable|string',
            'component_id'  => 'required|array',
            'component_id.*'=> 'required',
            'location_id'   => 'required|array', 
            'location_id.*' => 'required',
            'quantity'      => 'required|array',
            'quantity.*'    => 'required|numeric|min:1',
            'price'         => 'nullable|array',
        ], [
            'exporter_name.required' => 'Vui lòng nhập tên người thực hiện.',
            'component_id.*.required' => 'Vui lòng chọn linh kiện.',
            'location_id.*.required' => 'Vui lòng chọn vị trí lấy hàng.',
            'quantity.*.min' => 'Số lượng xuất phải từ 1 trở lên.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // A. Tạo phiếu xuất chung (Bảng exports)
                $export = Export::create([
                    'exporter_name' => $request->exporter_name,
                    'note' => $request->note, 
                    'export_date' => now(), 
                ]);

                // B. Dùng vòng lặp xử lý từng linh kiện gửi lên
                foreach ($request->component_id as $key => $compId) {
                    
                    $locId = $request->location_id[$key];
                    $qtyToExport = $request->quantity[$key];
                    $exportPrice = $request->price[$key] ?? 0;

                    // TÌM LINH KIỆN ĐỂ KIỂM TRA TỒN KHO TRƯỚC
                    $component = Component::findOrFail($compId);

                    // Nếu số lượng muốn xuất lớn hơn số lượng TỔNG đang có -> Báo lỗi
                    if ($qtyToExport > $component->quantity) {
                        throw new \Exception("Thất bại! Linh kiện '{$component->name}' trong kho tổng chỉ còn {$component->quantity} sản phẩm, không đủ để xuất.");
                    }

                    // --- KIỂM TRA SỐ LƯỢNG THỰC TẾ TRÊN CÁI KỆ ĐƯỢC CHỌN ---
                    $importedAtLoc = \App\Models\ImportDetail::where('component_id', $compId)
                                            ->where('location_id', $locId)->sum('quantity');
                    $exportedAtLoc = \App\Models\ExportDetail::where('component_id', $compId)
                                            ->where('location_id', $locId)->sum('quantity');
                    $stockAtLoc = $importedAtLoc - $exportedAtLoc;

                    if ($qtyToExport > $stockAtLoc) {
                        $loc = \App\Models\Location::find($locId);
                        $locName = $loc ? $loc->name : 'Vị trí này';
                        throw new \Exception("Thất bại! Tại '{$locName}', linh kiện '{$component->name}' chỉ còn {$stockAtLoc} sản phẩm, không đủ để xuất.");
                    }
                    // ----------------------------------------------------------------------

                    // C. Tạo chi tiết phiếu xuất (Bảng export_details)
                    ExportDetail::create([
                        'export_id' => $export->id,
                        'component_id' => $compId,
                        'location_id' => $locId,
                        'quantity' => $qtyToExport,
                        'price' => $exportPrice,
                    ]);

                    // D. TRỪ SỐ LƯỢNG KHO (Trừ đi số lượng linh kiện vừa lấy)
                    $component->decrement('quantity', $qtyToExport); 
                }
            });

            // Nếu thành công thì quay về trang danh sách
            return redirect()->route('exports.index')->with('success', 'Xuất kho thành công!');

        } catch (\Exception $e) {
            // withInput() giúp giữ lại các dữ liệu người dùng đã gõ nếu có lỗi
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

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