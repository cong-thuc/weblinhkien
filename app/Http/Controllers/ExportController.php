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

        // --- PHẦN THÊM MỚI: TẠO MẢNG DỮ LIỆU ĐỂ BẮN SANG JAVASCRIPT ---
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
        // Validate bắt buộc phải chọn location_id
        $request->validate([
            'component_id' => 'required',
            'location_id'  => 'required', 
            'quantity' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // TÌM LINH KIỆN ĐỂ KIỂM TRA TỒN KHO TRƯỚC
                $component = Component::findOrFail($request->component_id);

                // Nếu số lượng muốn xuất lớn hơn số lượng TỔNG đang có -> Báo lỗi ngay!
                if ($request->quantity > $component->quantity) {
                    throw new \Exception("Thất bại! Trong kho tổng chỉ còn {$component->quantity} sản phẩm, không đủ để xuất.");
                }

                // --- PHẦN BỔ SUNG: KIỂM TRA SỐ LƯỢNG THỰC TẾ TRÊN CÁI KỆ ĐƯỢC CHỌN ---
                $importedAtLoc = \App\Models\ImportDetail::where('component_id', $request->component_id)
                                        ->where('location_id', $request->location_id)->sum('quantity');
                $exportedAtLoc = \App\Models\ExportDetail::where('component_id', $request->component_id)
                                        ->where('location_id', $request->location_id)->sum('quantity');
                $stockAtLoc = $importedAtLoc - $exportedAtLoc;

                if ($request->quantity > $stockAtLoc) {
                    $loc = \App\Models\Location::find($request->location_id);
                    $locName = $loc ? $loc->name : 'Vị trí này';
                    throw new \Exception("Thất bại! Tại '{$locName}' chỉ còn {$stockAtLoc} sản phẩm này, không đủ để xuất.");
                }
                // ----------------------------------------------------------------------

                // A. Tạo phiếu xuất chung (Bảng exports)
                $export = Export::create([
                    'note' => $request->note, 
                    'export_date' => now(), // Bỏ comment nếu bảng exports của bạn có cột export_date
                ]);

                // B. Tạo chi tiết phiếu xuất (Bảng export_details)
                ExportDetail::create([
                    'export_id' => $export->id,
                    'component_id' => $request->component_id,
                    'location_id' => $request->location_id, // <-- LƯU THÊM VỊ TRÍ XUẤT VÀO DB
                    'quantity' => $request->quantity,
                    'price' => $request->price ?? 0, // Giá xuất
                ]);

                // C. TRỪ SỐ LƯỢNG KHO (Dùng hàm decrement thay vì increment)
                $component->decrement('quantity', $request->quantity); 
            });

            // Nếu thành công thì quay về trang danh sách
            return redirect()->route('exports.index')->with('success', 'Xuất kho thành công');

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