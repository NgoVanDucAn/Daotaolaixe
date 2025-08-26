@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' =>  url()->previous(),
            'title' => 'Quay về trang trước'
        ])
    </div>
@endsection

<style>
    .staff_id {
        visibility: hidden;
    }
</style>

@section('content')
<div class="card">
    <div class="card-body">
        {{-- <p><strong>Họ và tên:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'N/A' }}</p>
        @php
            $genderValue = strtolower($user->gender);
            $genderLabel = [
                'male' => 'Nam',
                'female' => 'Nữ',
                'other' => 'Khác',
            ][$genderValue] ?? 'Không rõ';
        @endphp
        <p><strong>Giới tính:</strong> {{ $genderLabel }}</p>
        <p><strong>Date of Birth:</strong> {{ optional($user->dob)->format('d/m/Y') }}</p>
        <p><strong>Identity Card:</strong> {{ $user->identity_card ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($user->status) ?? 'N/A' }}</p>
        <p><strong>Role:</strong>
            @foreach($roles as $role)
                <span class="badge bg-info">{{ $role }}</span>
            @endforeach
        </p>

        <a href="{{ url()->previous() }}" class="btn btn-primary mt-4">Back to Users List</a> --}}
        <span style="font-size: 24px">Thông tin <b>{{ $user->name }}</b></span>
        <table class="table table-bordered table-hover">
            <tbody>
                <tr>
                    <th>Tên</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Điện thoại</th>
                    <td>{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>{{ $user->address }}</td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : 'Chưa có thông tin' }}</td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <span class="badge bg-primary">{{ $user->status }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td>{{ $user->description ?? '--' }}</td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'Chưa có thông tin' }}</td>
                </tr>
                <tr>
                    <th>Ngày cập nhật</th>
                    <td>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y') : 'Chưa có thông tin' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 24px;">
            <div style="font-size: 24px;">Lịch làm việc</div>
            <div>
                <button type="button" class="btn btn-primary">Tìm kiếm</button>
                <button 
                    type="button" 
                    class="btn"
                    style="color: #3b82f6; border: 1px solid #3b82f6; padding: 2px 8px; margin-left: 8px" 
                    data-bs-toggle="modal" 
                    data-bs-target="#jobModal"
                    data-sale-id = {{ $user->id }}
                >
                    <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <strong>Công việc</strong>
                </button>
                <button 
                    type="button" 
                    class="btn"
                    style="color: #3b82f6; border: 1px solid #3b82f6; padding: 2px 8px; margin-left: 8px" 
                    data-bs-toggle="modal" 
                    data-bs-target="#meetModal"
                    data-sale-id = {{ $user->id }}
                >
                    <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <strong>Lịch họp</strong>
                </button>
                <button 
                    type="button" 
                    class="btn"
                    style="color: #3b82f6; border: 1px solid #3b82f6; padding: 2px 8px; margin-left: 8px" 
                    data-bs-toggle="modal" 
                    data-bs-target="#callModal"
                    data-sale-id = {{ $user->id }}
                >
                    <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <strong>Lịch gọi</strong>
                </button>
            </div>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <th>STT</th>
                <th>Loại</th>
                <th>Tên sự kiện</th>
                <th>Ngày</th>
                <th>Trạng thái</th>
                <th>Ưu tiên</th>
                <th>Hành động</th>
            </thead>
            <tbody>

                <tr>
                    <td>{{ 'new' }}</td>
                    <td>{{ 'new' }}</td>
                    <td>{{ 'new' }}</td>
                    <td>{{ 'new' }}</td>
                    <td>{{ 'new' }}</td>
                    <td>{{ 'new' }}</td>
                    <td>{{ 'new' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    {{-- modal Công việc --}}
    <div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feeModalLabel">Thêm <span style="color: red">Công việc</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="formJob" action="{{ route('fees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="modal_name" value="jobModal">
            
                        <div class="row">
                            <!-- Cột trái (col-9) -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="name_evt" class="form-label">Tên sự kiện</label>
                                        <input type="text" class="form-control name_evt" name="name_evt" value="{{ old('name_evt') }}" required>
                                        @error('name_evt')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Thời gian bắt đầu</label>
                                        <input type="text" placeholder="dd/mm/yyyy" class="form-control datetime-local start_date" name="start_date" value="{{ old('start_date')?->format('d/m/Y') }}" required>
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Thời gian kết thúc</label>
                                        <input type="text" placeholder="dd/mm/yyyy" class="form-control datetime-local end_date" name="end_date" value="{{ old('end_date')?->format('d/m/Y') }}" required>
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-12 mb-3">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select status" required>
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Chưa bắt đầu</option>
                                            <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Đang tiến hành</option>
                                            <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Hoãn lại</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <textarea class="form-control description" name="description" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Cột phải (col-3) -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sale_support" class="form-label">Người phụ trách(Admin)</label>
                                    <select class="form-select user_ids" name="user_ids[]" multiple required>
                                        {{-- @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user', $user->id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('sale_support')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                                <div class="mb-3">
                                    <label for="staff_id" class="form-label">Nhân viên</label>
                                    <select class="form-select staff_id" name="staff_id[]" multiple required>
                                        <option></option>
                                        {{-- @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('staff_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                            </div>
                        </div>
                        
                    </form>
                </div>
        
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i>
                        <span>Hủy</span>
                    </button>
                    <button type="submit" class="btn btn-primary" form="formJob">
                        <i class="mdi mdi-content-save-outline me-2"></i>
                        <span>Lưu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal Lịch họp --}}
    <div class="modal fade" id="meetModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feeModalLabel">Thêm <span class="text-warning">Lịch họp</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
        
                <div class="modal-body">
                    <form id="formMeet" action="{{ route('fees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="modal_name" value="meetModal">
                        <div class="row">
                            <!-- Cột trái (col-9) -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="name_evt" class="form-label">Tên sự kiện</label>
                                        <input type="text" class="form-control name_evt" name="name_evt" value="{{ old('name_evt') }}" required>
                                        @error('name_evt')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Thời gian bắt đầu</label>
                                        <input type="text" placeholder="dd/mm/yyyy" class="form-control datetime-local start_date" name="start_date" value="{{ old('start_date')?->format('d/m/Y') }}" required>
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Thời gian kết thúc</label>
                                        <input type="text" placeholder="dd/mm/yyyy" class="form-control datetime-local end_date" name="end_date" value="{{ old('end_date')?->format('d/m/Y') }}" required>
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-12 mb-3">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select status" required>
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Chưa bắt đầu</option>
                                            <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Đang tiến hành</option>
                                            <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Hoãn lại</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <textarea class="form-control description" name="description" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Cột phải (col-3) -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sale_support" class="form-label">Người phụ trách(Admin)</label>
                                    <select class="form-select user_ids" name="user_id" required>
                                        {{-- @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user', $user->id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('sale_support')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                                <div class="mb-3">
                                    <label for="lead" class="form-label">Nhân viên</label>
                                    <select class="form-select staff_id" id="staff_id" name="staff_id[]" multiple required>
                                        <option></option>
                                        {{-- @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('staff_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                            </div>
                        </div>
                        
                    </form>
                </div>
        
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i>
                        <span>Hủy</span>
                    </button>
                    <button type="submit" class="btn btn-primary" form="formMeet">
                        <i class="mdi mdi-content-save-outline me-2"></i>
                        <span>Lưu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal Lịch gọi --}}
    <div class="modal fade" id="callModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feeModalLabel">Thêm <span class="text-success">Lịch gọi</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
        
                <div class="modal-body">
                    <form id="formCall" action="{{ route('fees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="modal_name" value="callModal">
                        <div class="row">
                            <!-- Cột trái (col-9) -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="name_evt" class="form-label">Tên sự kiện</label>
                                        <input type="text" class="form-control name_evt" name="name_evt" value="{{ old('name_evt') }}" required>
                                        @error('name_evt')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Thời gian bắt đầu</label>
                                        <input type="text" placeholder="dd/mm/yyyy" class="form-control datetime-local start_date" name="start_date" value="{{ old('start_date')?->format('d/m/Y') }}" required>
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Thời gian kết thúc</label>
                                        <input type="text" placeholder="dd/mm/yyyy" class="form-control datetime-local end_date" name="end_date" value="{{ old('end_date')?->format('d/m/Y') }}" required>
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-12 mb-3">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select status" required>
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Chưa bắt đầu</option>
                                            <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Đang tiến hành</option>
                                            <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Hoãn lại</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Mô tả</label>
                                        <textarea class="form-control description" name="description" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Cột phải (col-3) -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sale_support" class="form-label">Người phụ trách(Admin)</label>
                                    <select class="form-select user_ids" name="user_ids[]" required>
                                        {{-- @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user', $user->id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('sale_support')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                                <div class="mb-3">
                                    <label for="lead" class="form-label">Lead</label>
                                    <select class="form-select lead_id" name="lead" multiple required>
                                        {{-- @foreach($leads as $lead)
                                            <option value="{{ $lead->id }}" {{ old('lead')}}>{{ $lead->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('lead')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                            </div>
                        </div>
                        
                    </form>
                </div>
        
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #ebebeb" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i>
                        <span>Hủy</span>
                    </button>
                    <button type="submit" class="btn btn-primary" form="formModal">
                        <i class="mdi mdi-content-save-outline me-2"></i>
                        <span>Lưu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            ['#jobModal', '#meetModal'].forEach(function (modalId) {
                $(modalId).on('shown.bs.modal', function () {
                    const $select = $(this).find('.staff_id');

                    // Check if already initialized
                    if ($select.hasClass('select2-hidden-accessible')) return;

                    $select.select2({
                        placeholder: '-- Vui lòng chọn nhân viên --',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $(modalId)
                    });

                    // Show select2 UI
                    $select.next('.select2-container').css('visibility', 'visible');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            const modalIds = ['#jobModal', '#meetModal', '#callModal'];

            modalIds.forEach(function (modalId) {
                const $modal = $(modalId);
                const $start = $modal.find('.start_date');
                const $end = $modal.find('.end_date');
                const $userSelect = $modal.find('.user_ids');
                const $leadSelect = $modal.find('.lead_id');
                const $staffSelect = $modal.find('.staff_id');

                let defaultSaleId = null;
                $modal.on('show.bs.modal', function (event) {
                    const button = $(event.relatedTarget);
                    defaultSaleId = button.data('sale-id') || null;
                });

                // Khởi tạo Select2 cho tất cả select (nếu có)
                if ($userSelect.length) {
                    $userSelect.select2({
                        placeholder: '-- Vui lòng chọn mốc thời gian --',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $modal,
                    });
                }

                $leadSelect.select2({
                    placeholder: '-- Vui lòng chọn lead --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $modal,
                });

                function updateUserOptions(users) {
                    if (!$userSelect.length) return;

                    $userSelect.empty();

                    users.forEach(user => {
                        const isSelected = user.id == defaultSaleId;
                        const option = new Option(user.name, user.id, isSelected, isSelected);
                        $userSelect.append(option);
                    });

                    $userSelect.trigger('change'); // cập nhật giao diện
                }

                function checkAndFetchUsers() {
                    const startVal = $start.val();
                    const endVal = $end.val();
                    const params = new URLSearchParams({
                        type: 'lead',
                        start_time: startVal,
                        end_time: endVal
                    });
                    if (startVal && endVal) {
                        $userSelect.prop('disabled', false);
                        updateSelect2Placeholder($userSelect, 'Chọn người phụ trách');

                        // Gửi request fetch user
                        fetch(`/users-available?${params.toString()}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data && Array.isArray(data.users)) {
                                    updateUserOptions(data.users);
                                }
                            })
                            .catch(err => console.error('Fetch users error:', err));
                    } else {
                        // Reset select
                        $userSelect.val(null).trigger('change');
                        $userSelect.prop('disabled', true);
                        updateSelect2Placeholder($userSelect, '-- Vui lòng chọn mốc thời gian --');
                    }
                }

                function updateSelect2Placeholder($select, newPlaceholder) {
                    const currentVal = $select.val();
                    $select.select2('destroy');
                    $select.select2({
                        placeholder: newPlaceholder,
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $modal,
                    });
                    $select.val(currentVal).trigger('change');
                }

                // Gọi khi modal mở
                $modal.on('shown.bs.modal', checkAndFetchUsers);

                // Gọi khi người dùng thay đổi ngày
                $start.on('change', checkAndFetchUsers);
                $end.on('change', checkAndFetchUsers);
            });
        });
    </script>
@endsection
