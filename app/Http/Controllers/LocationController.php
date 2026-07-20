<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Hiển thị danh sách vị trí
    public function index()
    {
       // Lấy danh sách vị trí kèm theo cả chi tiết NHẬP và XUẤT
        $locations = \App\Models\Location::with(['importDetails.component', 'exportDetails.component'])
                        ->orderBy('id', 'desc')->paginate(10);

        foreach ($locations as $loc) {
            // 1. Tính tổng nhập và xuất
            $totalImported = $loc->importDetails->sum('quantity');
            $totalExported = $loc->exportDetails->sum('quantity');

            // 2. Số lượng ĐANG CÓ THỰC TẾ trên kệ (Đã dùng = Nhập - Xuất)
            $used = $totalImported - $totalExported;
            
            $loc->used = $used;
            $loc->remaining = $loc->max_capacity - $used; // Trả lại chỗ trống cho kệ

            // 3. Tính toán xem linh kiện nào đang còn bao nhiêu trên kệ
            $componentsInLocation = [];
            
            // Cộng dồn lúc nhập
            foreach ($loc->importDetails as $detail) {
                $compName = $detail->component->name ?? 'Linh kiện đã xóa';
                if (!isset($componentsInLocation[$compName])) {
                    $componentsInLocation[$compName] = 0;
                }
                $componentsInLocation[$compName] += $detail->quantity;
            }
            
            // Trừ đi lúc xuất
            foreach ($loc->exportDetails as $detail) {
                $compName = $detail->component->name ?? 'Linh kiện đã xóa';
                if (isset($componentsInLocation[$compName])) {
                    $componentsInLocation[$compName] -= $detail->quantity;
                }
            }
            
            // Lọc bỏ những linh kiện đã xuất hết sạch (số lượng <= 0)
            $loc->groupedComponents = array_filter($componentsInLocation, function($qty) {
                return $qty > 0;
            });
        }

        return view('locations.index', compact('locations'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('locations.create');
    }

    // Lưu vị trí mới vào Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_capacity' => 'required|numeric|min:1',
        ]);

        Location::create($request->all());

        return redirect()->route('locations.index')->with('success', 'Đã thêm vị trí kho thành công!');
    }

    // Hiển thị form sửa
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    // Cập nhật dữ liệu
    public function update(Request $request, Location $location)
    {
       // 1. Validate dữ liệu đầu vào cơ bản
        $request->validate([
            'name' => 'required|string|max:255',
            'max_capacity' => 'required|numeric|min:1',
        ]);

        // 2. Tính tổng số lượng linh kiện đang thực tế nằm ở vị trí này
        $usedCapacity = \App\Models\ImportDetail::where('location_id', $location->id)->sum('quantity');

        // 3. RÀNG BUỘC: Kiểm tra sức chứa mới nhập vào có bị nhỏ hơn số hàng đang có không
        if ($request->max_capacity < $usedCapacity) {
            return back()->with('error', 'Lỗi: Sức chứa mới (' . $request->max_capacity . ') không được nhỏ hơn số linh kiện đang cất trên kệ (' . $usedCapacity . '). Vui lòng xuất kho bớt trước khi giảm sức chứa!');
        }

        // 4. Nếu hợp lệ, tiến hành cập nhật
        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Cập nhật vị trí thành công!');
    }

    // Xóa vị trí
    public function destroy(Location $location)
    {
       // Kiểm tra xem có bất kỳ chi tiết phiếu nhập nào đang dùng location_id này không
        $usedCount = \App\Models\ImportDetail::where('location_id', $location->id)->count();
        
        // Nếu đếm ra > 0, chặn lại và báo lỗi
        if ($usedCount > 0) {
            return back()->with('error', 'Lỗi: Không thể xóa! Vị trí "' . $location->name . '" hiện đang chứa linh kiện.');
        }

        // Nếu qua được vòng kiểm tra (bằng 0), tiến hành xóa
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Đã xóa vị trí thành công!');
    }
}