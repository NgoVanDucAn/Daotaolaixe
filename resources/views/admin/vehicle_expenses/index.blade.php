@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="filter-options mb-3">
            
            <form id="examSchedulesFilterForm" class="row" method="GET">
                <div class="col-12 col-lg-3 col-md-3 mb-3">
                    <label class="form-label fw-bold">Biển số</label>
                    <select name="license_plate" id='license_plate_filter' class="form-select">
                        <option value="">-- Chọn xe --</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('license_plate') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->license_plate }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" name="license_plate" class="form-control" placeholder="Nhập biển số" value="{{ request('license_plate') }}"> --}}
                </div>
                <div class="col-12 col-lg-3 col-md-3 mb-3">
                    <label for="type">Loại chi phí</label>
                    <select name="type" id='type_id_select' class="form-select">
                        <option value="">-- Chọn loại --</option>
                        @foreach (App\Models\VehicleExpense::getTypeOptions() as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg-3 col-md-3 mb-4 position-relative">
                    <label class="form-label fw-bold">Nộp từ ngày</label>
                    <input type="text" placeholder="dd/mm/yyyy" name="start_date" value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}" class="form-control real-date" autocomplete="off" placeholder="Ngày bắt đầu">
                </div>
                <div class="col-6 col-lg-3 col-md-3 mb-4 position-relative">
                    <label class="form-label fw-bold">Nộp đến ngày</label>
                    <input type="text" placeholder="dd/mm/yyyy" name="end_date" value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}" class="form-control real-date" autocomplete="off" placeholder="Ngày kết thúc">
                </div>
                <div class="col-12 col-lg-3 col-md-3 mb-3">
                    <label for="user_id">Người chi</label>
                    <select name="user_id" id="user_id_select" class="form-select select2">
                        <option value="">-- Không chọn --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-3 col-md-4 mb-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route('vehicle-expenses.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            {{-- href="{{ route('vehicles.create') }}" --}}
            type="button" 
            class="btn"
            data-bs-toggle="modal"
            data-bs-target="#vehicleExpenseCreateModal" 
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo chi phí xe</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <button
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#vehicleExpenseCreateModal" 
                class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
                style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;"
            >
                +
            </button>
        </div>
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1"><strong>Tổng tiền: </strong><strong>{{ '0 new' }}</strong></div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Biển số</th>
                    <th>Loại chi phí</th>
                    <th>Số tiền</th>
                    <th>Thời gian</th>
                    <th>Người chi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $expense->vehicle->license_plate ?? 'N/A' }}</td>
                        <td>{{ strtolower($expense->type) == 'other' ? $expense->note : $expense->type_vi}}</td>
                        <td>{{ number_format($expense->amount, 0, ',', '.') }} VND</td>
                        <td>{{ \Carbon\Carbon::parse($expense->time)->format('d/m/Y H:i') }}</td>
                        <td>{{ $expense->user->name ?? 'Không có' }}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                {{-- <a href="{{ route('vehicle-expenses.edit', $expense) }}" class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0">
                                    <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                </a> --}}
    
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0 openEditModal"
                                    data-id="{{ $expense->id }}"
                                    data-vehicle_id="{{ $expense->vehicle_id }}"
                                    data-type="{{ $expense->type }}"
                                    data-time="{{ \Carbon\Carbon::parse($expense->time)->format('Y-m-d\TH:i') }}"
                                    data-amount="{{ $expense->amount }}"
                                    data-user_id="{{ $expense->user_id }}"
                                    data-note="{{ $expense->note }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#vehicleExpenseEditModal"
                                >
                                    <i class="mdi mdi-square-edit-outline fs-4"></i>
                                </button>
    
    
                                <form action="{{ route('vehicle-expenses.destroy', $expense) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi-trash-can-outline fs-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Không có dữ liệu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Model Create --}}

<div class="modal fade" id="vehicleExpenseCreateModal" tabindex="-1" aria-labelledby="vehicleExpenseCreateModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Thêm chi phí</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vehicle-expenses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="modal_name" value="vehicleExpenseCreateModal">
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="vehicle_id">Chọn xe</label>
                            <select name="vehicle_id" id="vehicle_id" class="form-select select2" required>
                                <option value="">-- Chọn xe --</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->license_plate }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-12 col-md-6 mb-3">
                            <label for="type">Loại chi phí</label>
                            <select name="type" class="form-select" required>
                                <option value="">-- Chọn loại --</option>
                                @foreach (App\Models\VehicleExpense::getTypeOptions() as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-12 col-md-6 mb-3">
                            <label for="time">Thời gian</label>
                            <input type="text" name="time" class="form-control datetime-local" value="{{ old('time') }}" required>
                            @error('time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="amount">Số tiền</label>
                            <input type="text" name="amount" class="form-control currency-input" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="col-12 col-md-6 mb-3">
                            <label for="user_id">Người chi</label>
                            <select name="user_id" id="user_id" class="form-select select2">
                                <option value="">-- Không chọn --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="note">Ghi chú</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="" data-bs-dismiss="modal" class="btn btn-secondary me-2">Hủy</button>
                            <button type="submit" class="btn btn-success">Lưu</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="vehicleExpenseEditModal" tabindex="-1" aria-labelledby="vehicleExpenseEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật chi phí</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editExpenseForm" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="modal_name" value="vehicleExpenseEditModal">
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="edit_vehicle_id">Chọn xe</label>
                            <select name="vehicle_id" id="edit_vehicle_id" class="form-select select2" required>
                                <option value="">-- Chọn xe --</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="edit_type">Loại chi phí</label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="">-- Chọn loại --</option>
                                @foreach (App\Models\VehicleExpense::getTypeOptions() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="edit_time">Thời gian</label>
                            <input type="text" name="time" id="edit_time" class="form-control datetime-local" required>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="edit_amount">Số tiền</label>
                            <input type="text" name="amount" id="edit_amount" class="form-control currency-input" required>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="edit_user_id">Người chi</label>
                            <select name="user_id" id="edit_user_id" class="form-select select2">
                                <option value="">-- Không chọn --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="edit_note">Ghi chú</label>
                            <textarea name="note" id="edit_note" class="form-control"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary me-2">Hủy</button>
                            <button type="submit" class="btn btn-success">Lưu</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection
@section('js')
    <script>
        $(document).ready(function() {
            const selectIds = ['#user_id', '#vehicle_id', '#edit_type', '#edit_vehicle_id', '#edit_user_id', '#user_id_select', '#type_id_select', '#license_plate_filter'];

            selectIds.forEach(function(id) {
                $(id).select2({
                    placeholder: '-- Chọn --',
                    allowClear: true,
                    width: '100%'
                });
            });

            $('.openEditModal').click(function () {
                const id = $(this).data('id');
                const vehicle_id = $(this).data('vehicle_id');
                const type = $(this).data('type');
                const time = $(this).data('time');
                const amount = $(this).data('amount');
                const user_id = $(this).data('user_id');
                const note = $(this).data('note');

                $('#editExpenseForm').attr('action', '/vehicle-expenses/' + id);

                $('#edit_vehicle_id').val(vehicle_id).trigger('change');
                $('#edit_type').val(type);
                $('#edit_time').val(time);
                $('#edit_amount').val(amount);
                $('#edit_user_id').val(user_id).trigger('change');
                $('#edit_note').val(note);
            });
        }); 
    </script>
    
@endsection