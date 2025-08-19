@extends('layouts.admin')

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a
            type="button" 
            class="btn"
            data-bs-toggle="modal" 
            data-bs-target="#addStudentToCourse"
            data-student-id="{{ $lead->id }}"
            data-student-name="{{ $lead->name }}"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Chuyển sang HV-KH</span>
        </a>
    </div>
@endsection

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('leads.index'),
            'title' => 'Quay về trang quản lý lead'
        ])
    </div>
@endsection

<style>
    .staff_id {
        visibility: hidden;
    }
</style>
@section('content')
    <form action="{{ route('leads.update', $lead) }}" method="POST">
        @csrf
        @method('PUT')
        {{-- <div class="form-group">
            <label for="name">Tên:</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $lead->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ $lead->email }}" required>
        </div>
        <div class="form-group">
            <label for="phone">Điện thoại:</label>
            <input type="text" name="phone" class="form-control" id="phone" value="{{ $lead->phone }}" required>
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ:</label>
            <input type="text" name="address" class="form-control" id="address" value="{{ $lead->address }}" required>
        </div>
        <div class="form-group">
            <label for="user_id">Người hỗ trợ:</label>
            <select name="user_id" class="form-control" id="user_id" required>
                <option value="">Chọn người hỗ trợ</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $lead->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="lead_source_id">Nguồn Gốc Lead:</label>
            <select name="lead_source_id" class="form-control" id="lead_source_id" required>
                <option value="">Chọn nguồn khách</option>
                @foreach($leadSources as $source)
                    <option value="{{ $source->id }}" {{ $source->id == $lead->lead_source_id ? 'selected' : '' }}>{{ $source->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="assigned_to">Người Phụ Trách:</label>
            <select name="assigned_to" class="form-control" id="assigned_to" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $lead->assigned_to ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật Lead</button> --}}
        {{-- @dd($lead) --}}
        
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card rounded-4">
                    <div class="card-body row">
                        {{-- <div class="form-group col-12 col-md-6">
                            <label for="student_id" class="form-label">Chọn học viên(Nếu có)</label>
                            <select class="form-select" id="student_id" name="student_id">
                                <option value="">Chọn học viên</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                                
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="form-group col-12 col-md-6"> 
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name',  $lead->name ?? '') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email',  $lead->email ?? '') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="phone" class="form-label">SĐT</label>
                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone',  $lead->phone ?? '') }}" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" id="address" value="{{ old('address', $lead->address ?? '') }}" required>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6 position-relative">
                            <label for="dob" class="form-label">Ngày sinh</label>
                            <input 
                                type="text" 
                                placeholder="dd/mm/yyyy"
                                class="form-control real-date" autocomplete="off"
                                id="dob" 
                                name="dob" 
                                value="{{ old('dob', $lead->dob?->format('d/m/Y') ?? '') }}"
                            >
                            @error('dob')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="lead_source" class="form-label">Nguồn</label>
                            <select class="form-select" id="lead_source" name="lead_source">
                                <option value="">Chọn nguồn</option>
                                @foreach($leadSources as $source)
                                    <option value="{{ $source->id }}" {{ old('lead_source') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('lead_source')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="interest_level" class="form-label">Mức độ quan tâm</label>
                            <select class="form-select" id="interest_level" name="interest_level">
                                @foreach(App\Models\Student::getOptions('interest_level') as $key => $label)
                                    <option value="{{ $key }}" {{ old('interest_level') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('interest_level')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="status_lead" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status_lead" name="status_lead">
                                @foreach(App\Models\Student::getOptions('status_lead') as $key => $label)
                                    <option value="{{ $key }}" {{ old('status_lead') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status_lead')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="description" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="description" name="description" value="{{ old('description',  $lead->description ?? '') }}"></textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-4">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="sale_support" class="form-label">Người phụ trách</label>
                            <select class="form-select" id="sale_support" name="sale_support">
                                <option value="">Chọn người phụ trách</option>
                                @foreach($saleSupports as $support)
                                    <option value="{{ $support->id }}" {{ old('sale_support', $lead->sale_support) == $support->id ? 'selected' : '' }}>{{ $support->name }}</option>
                                @endforeach
                            </select>
                            @error('sale_support')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 ps-2 pe-2 pt-0 pb-0"><b><i class="mdi mdi-content-save fs-4"></i>Lưu</b></button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    {{-- <div class="card">
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
                    >
                        <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <strong>Công việc</strong>
                    </button>
                    <button 
                        type="button" 
                        class="btn"
                        style="color: #3b82f6; border: 1px solid #3b82f6; padding: 2px 8px; margin-left: 8px" 
                        data-bs-toggle="modal" 
                        data-bs-target="#meetModal"
                    >
                        <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <strong>Lịch họp</strong>
                    </button>
                    <button 
                        type="button" 
                        class="btn"
                        style="color: #3b82f6; border: 1px solid #3b82f6; padding: 2px 8px; margin-left: 8px" 
                        data-bs-toggle="modal" 
                        data-bs-target="#callModal"
                        data-lead-id={{ $lead->id }}
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
    </div> --}}

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
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user', $user->id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sale_support')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                                <div class="mb-3">
                                    <label for="lead" class="form-label">Nhân viên</label>
                                    <select class="form-select staff_id" id="staff_id" name="staff_id[]" multiple required>
                                        <option></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
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
                                    <label for="user_id" class="form-label">Người phụ trách(Admin)</label>
                                    <select class="form-select user_ids" name="user_id" required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user', $user->id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                                <div class="mb-3">
                                    <label for="lead" class="form-label">Nhân viên</label>
                                    <select class="form-select staff_id" id="staff_id" name="staff_id[]" multiple required>
                                        <option></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
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
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user', $user->id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sale_support')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        
                                <div class="mb-3">
                                    <label for="lead" class="form-label">Lead</label>
                                    <select class="form-select lead_id" name="lead" multiple required>
                                        <option value="">Chọn lead</option>
                                        @foreach($leads as $lead)
                                            <option value="{{ $lead->id }}" {{ old('lead')}}>{{ $lead->name }}</option>
                                        @endforeach
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
                    <button type="submit" class="btn btn-primary" form="formCall">
                        <i class="mdi mdi-content-save-outline me-2"></i>
                        <span>Lưu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@include('admin.leads.add_student_to_course')
@endsection
@section('js')
<script src="{{ asset('assets/js/add-student-to-course.js') }}"></script>
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


                $('#callModal').on('show.bs.modal', function (event) {
                    const button = $(event.relatedTarget); // Nút được click
                    const leadId = button.data('lead-id'); // Lấy id
                    const modal = $(this);

                    // Gán value vào select2 hoặc select thường
                    const select = modal.find('.lead_id');

                    // Xóa chọn cũ và set lead mới
                    select.val(leadId).trigger('change');
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

                if ($leadSelect.length) {
                    $leadSelect.select2({
                        placeholder: '-- Vui lòng chọn lead --',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $modal,
                    });
                }

                function updateUserOptions(users) {
                    if (!$userSelect.length) return;

                    $userSelect.empty();
                    users.forEach(user => {
                        const option = new Option(user.name, user.id, false, false);
                        $userSelect.append(option);
                    });

                    $userSelect.trigger('change'); // Cập nhật giao diện select2
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
