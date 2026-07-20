<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // KHAI BÁO THÊM ĐỂ LẤY NGƯỜI ĐĂNG NHẬP
use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Component;
use App\Models\Location;

class ImportController extends Controller
{
    public function index()
    {
        $imports = Import::orderBy('id', 'desc')->get();
        return view('imports.index', compact('imports'));
    }

    public function show(string $id)
    {
       // Thêm 'user' và 'details.location' vào mảng with()
        $import = Import::with(['details.component', 'user', 'details.location'])->findOrFail($id);

        return view('imports.show', compact('import'));
    }
    
    public function create()
    {
        $components = \App\Models\Component::all();
        
        // 1. Lấy toàn bộ Vị Trí từ Database (bên Quản lý Vị trí thêm mới là ở đây tự có)
        $locations = \App\Models\Location::all(); 
        
        // Lấy danh sách user (như đã làm ở bước trước)
        $users = \App\Models\User::all();

        // 2. Tính toán sức chứa để truyền ra ngoài giao diện
        foreach ($locations as $loc) {
            $usedCapacity = \App\Models\ImportDetail::where('location_id', $loc->id)->sum('quantity');
            $loc->remaining = $loc->max_capacity - $usedCapacity;
        }

        return view('imports.create', compact('components', 'locations', 'users'));
    }

    public function store(Request $request)
    {
        // 1. Validate dữ liệu: Yêu cầu phải có mảng details
        $request->validate([
            'details' => 'required|array',
            'details.*.component_id' => 'required',
            'details.*.location_id' => 'required',
            'details.*.quantity' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 2. Tạo phiếu nhập chung (Ghi nhận người nhập)
                $import = Import::create([
                    'import_date' => now(), 
                    'note' => $request->note, 
                    'importer_name' => $request->importer_name, // Lấy tên người nhập từ Form
                    'user_id' => Auth::id(), // Vẫn lưu ngầm ID của Admin thao tác để bảo mật
                ]);

                // 3. Lặp qua từng linh kiện gửi lên từ Form
                foreach ($request->details as $item) {
                    
                    // Lấy thông tin vị trí để kiểm tra sức chứa
                    $location = Location::findOrFail($item['location_id']);

                    // Đếm xem vị trí này hiện tại đang chứa bao nhiêu đồ (Tính tổng quantity)
                    $currentStored = ImportDetail::where('location_id', $location->id)->sum('quantity');

                    // KHIỂM TRA SỨC CHỨA: Hiện có + Chuẩn bị cất > Sức chứa tối đa
                    if (($currentStored + $item['quantity']) > $location->max_capacity) {
                        // Bắn ra lỗi, hệ thống tự động hoàn tác toàn bộ (Rollback)
                        throw new \Exception("Vị trí '{$location->name}' không đủ chỗ! (Đã chứa: {$currentStored}/{$location->max_capacity})");
                    }

                    // Nếu còn chỗ thì tiến hành lưu vào chi tiết
                    ImportDetail::create([
                        'import_id' => $import->id,
                        'component_id' => $item['component_id'],
                        'location_id' => $item['location_id'], // Đã có vị trí cất
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? 0,
                    ]);

                    // Cộng dồn vào kho tổng của linh kiện
                    $component = Component::findOrFail($item['component_id']);
                    $component->increment('quantity', $item['quantity']); 
                }
            });

            // Nếu vòng lặp chạy mượt mà, chuyển hướng ra danh sách
            return redirect()->route('imports.index')->with('success', 'Nhập hàng vào kho thành công!');

        } catch (\Exception $e) {
            // Nếu kệ đầy hoặc có lỗi, báo lỗi chi tiết ra màn hình
            return back()->with('error', 'Lỗi nhập kho: ' . $e->getMessage());
        }
    }

    public function edit(string $id) { /* ... */ }
    public function update(Request $request, string $id) { /* ... */ }
    public function destroy(string $id) { /* ... */ }
}