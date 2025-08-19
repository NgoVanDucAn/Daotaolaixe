@extends('layouts.admin')
<style>
    .image-upload-card {
        border: 2px dashed #ccc;
        border-radius: 8px;
        width: 100%;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        background-color: #f8f9fa;
    }

    .image-upload-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-upload-card .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: #fff;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
    }
</style>

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('students.index'),
            'title' => 'Quay về trang quản lý học viên'
        ])
    </div>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- <div class="row">
                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="name">Họ và tên:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" class="form-control" required>
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" class="form-control" required>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="phone">Số điện thoại:</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" class="form-control" required>
                    @error('phone')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="gender">Giới tính:</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                    @error('gender')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="dob">Ngày sinh:</label>
                    <input type="date" placeholder="dd/mm/yyyy"id="dob" name="dob" value="{{ old('dob', optional($student->dob)->format('Y-m-d')) }}" class="form-control">
                    @error('dob')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="identity_card">Căn cước công dân:</label>
                    <input type="text" id="identity_card" name="identity_card" value="{{ old('identity_card', $student->identity_card) }}" class="form-control">
                    @error('identity_card')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" class="form-control">
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="password_confirmation">Nhập lại mật khẩu:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    @error('password_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $student->address) }}" class="form-control">
                    @error('address')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="sale_support" class="form-label">Người hỗ trợ:</label>
                    <select class="form-select" id="sale_support" name="sale_support">
                        <option value="">Chọn người hỗ trợ</option>
                        @foreach($saleSupports as $support)
                            <option value="{{ $support->id }}" {{ old('sale_support', $student->sale_support) == $support->id ? 'selected' : '' }}>{{ $support->name }}</option>
                        @endforeach
                    </select>
                    @error('sale_support')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="lead_source" class="form-label">Nguồn khách hàng:</label>
                    <select class="form-select" id="lead_source" name="lead_source">
                        <option value="">Chọn nguồn khách</option>
                        @foreach($leadSources as $source)
                            <option value="{{ $source->id }}" {{ old('lead_source', $student->lead_source ) == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                        @endforeach
                    </select>
                    @error('lead_source')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="description">Mô tả:</label>
                    <textarea id="description" name="description" class="form-control" rows="1">{{ old('description', $student->description) }}</textarea>
                    @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                    <label for="image">Ảnh đại diện:</label>
                    <input type="file" id="image" name="image" class="form-control">
                    @error('image')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @if($student->image)
                        <img src="{{ asset('storage/' . $student->image) }}" width="100" class="mt-2" alt="Profile Image">
                    @endif
                </div>
            </div> --}}
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card rounded-4">
                        <div class="card-body row">
                            <div class="form-group col-12 col-md-6">
                                <label for="name" class="form-label">Tên</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $student->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $student->email) }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="phone" class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $student->phone) }}" required>
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="gender" class="form-label">Giới tính</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6 position-relative">
                                <label for="dob" class="form-label">Ngày sinh</label>
                                <input 
                                    type="text" 
                                    placeholder="dd/mm/yyyy"
                                    id="dob" 
                                    name="dob" 
                                    value="{{ old('dob', optional($student->dob)->format('d/m/Y')) }}" 
                                    class="form-control real-date" autocomplete="off"
                                >
                                @error('dob')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="identity_card">Căn cước công dân</label>
                                <input type="text" id="identity_card" name="identity_card" value="{{ old('identity_card', $student->identity_card) }}" class="form-control">
                                @error('identity_card')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" name="address" class="form-control" id="address" value="{{ old('address', $student->address) }}">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" id="password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="form-label">Số thẻ</label>
                                <input type="text" name="card_number" class="form-control" id="card_number" value="{{ old('card_number', $student->card_number) }}">
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label for="password_confirm" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password_confirm" name="password_confirm" class="form-control" id="password_confirm">
                                @error('password_confirm')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="active" {{ request('status', $student->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ request('status', $student->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="ranking_id" class="form-label">Hạng bằng đăng ký</label>
                                <select class="form-select" id="ranking_id" name="ranking_id">
                                    <option value="">-- Chọn hạng bằng --</option>
                                    {{-- <option value="">Chọn hạng bằng</option> --}}
                                    @foreach ($rankings as $ranking)
                                        <option value="{{ $ranking->id }}" {{ $student->ranking?->id == $ranking->id ? 'selected' : '' }}>{{ $ranking->name }}</option>
                                    @endforeach
                                </select>
                           </div>
                           <div class="form-group col-12 col-md-6 position-relative">
                               <label for="date_of_profile_set" class="form-label">Ngày đăng ký hồ sơ</label>
                               <input 
                                   type="text" 
                                   placeholder="dd/mm/yyyy"
                                   class="form-control real-date" autocomplete="off" 
                                   id="date_of_profile_set" name="date_of_profile_set" 
                                   value="{{ request('date_of_profile_set') ? \Carbon\Carbon::parse(request('date_of_profile_set'))->format('d/m/Y') : '' }}"
                               >
                               @error('date_of_profile_set')
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
                                <label for="image">Ảnh đại diện</label>
                                {{-- <input type="file" id="image" name="image" class="form-control">
                                @error('image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror --}}
                                <div class="image-upload-card" onclick="document.getElementById('image').click();" id="imageCard">
                                    @if ($student->image)
                                        <img src="{{ asset('storage/' . $student->image) }}" width="100" class="mt-2" alt="Profile Image">
                                    @else
                                        <span id="placeholderText">Chọn hình ảnh</span>
                                        <img id="previewImage" src="#" alt="Preview" style="display: none;">
                                    @endif
  
                                    <button type="button" class="remove-image" onclick="removeImage(event)"></button>
                                </div>
                            
                                <input type="file" id="image" name="image" accept="image/*" class="form-control d-none" onchange="previewSelectedImage(event)">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 ps-2 pe-2 pt-0 pb-0">
                                <b>
                                    <i class="mdi mdi-content-save fs-4"></i>Lưu
                                </b>
                            </button>
                        </div>
                    </div>
                </div>  
            </div>

            <div class="card col-8">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-9 col-md-4">
                            <label for="fee_ranking_input" class="form-label">Tổng lệ phí hồ sơ</label>
                            <input type="text" name="fee_ranking" id="fee_ranking_input" class="form-control currency-input" id=""  value="{{ old('card_number', $student->fee_ranking) }}" required>
                            @error('fee_ranking')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-9 col-md-4">
                            <label for="paid_fee_input" class="form-label">Lệ phí đã đóng</label>
                            <input type="text" name="paid_fee" id="paid_fee_input" class="form-control currency-input" value="{{ old('card_number', $student->paid_fee) }}">
                            @error('paid_fee')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-9 col-md-4">
                            <label for="remaining_fee_input" class="form-label">Lệ phí còn thiếu</label>
                            <input type="text" name="remaining_fee" id="remaining_fee_input" class="form-control currency-input" value="{{ $student->remaining_fee }}" disabled>
                            @error('remaining_fee')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">STT</th>
                        <th rowspan="2" class="text-nowrap align-middle">Mã học viên</th>
                        <th rowspan="2" class="align-middle">Hạng</th>
                        <th rowspan="2" class="text-nowrap align-middle">Họ và tên</th>
                        <th rowspan="2" class="text-nowrap align-middle">Ngày sinh</th>
                        <th rowspan="2" class="text-nowrap align-middle">Giới tính</th>
                        {{-- <th rowspan="2" class="text-nowrap align-middle">Số điện thoại</th> --}}
                        <th rowspan="2" class="text-nowrap align-middle">Ngày ký hợp đồng</th>
                        <th rowspan="2" class="text-nowrap align-middle">CMT/CCCD</th>
                        <th rowspan="2" class="text-nowrap align-middle">Khám sức khoẻ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Địa chỉ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Số thẻ</th>
                        <th rowspan="2" class="text-nowrap align-middle">Giáo viên</th>
                        {{-- <th rowspan="2" class="text-nowrap align-middle">Trạng thái</th> --}}
                        {{-- <th rowspan="2" class="text-nowrap align-middle">Ngày hoạt động</th> --}}
                
                        <th colspan="3" class="align-middle">Tình trạng thi</th>
                
                        <th colspan="4" class="align-middle">Phiên học</th>
                        <th colspan="3" class="align-middle">Học phí</th>
                
                        <th rowspan="3" class="align-middle">Trạng thái</th>
                        <th rowspan="3" class="align-middle">Hành động</th>
                    </tr>
                    <tr>
                        {{-- <th colspan="{{ max($lyThuyetExams->count(), 1) }}" class="align-middle">Thi hết môn LT</th>
                        <th colspan="{{ max($thucHanhExams->count(), 1) }}" class="align-middle">Thi hết môn TH</th>
                        <th colspan="{{ max($totNghiepExams->count(), 1) }}" class="align-middle">Thi tốt nghiệp</th> --}}
                        <th class="align-middle">Thi hết môn LT</th>
                        <th class="align-middle">Thi hết môn TH</th>
                        <th class="align-middle">Thi tốt nghiệp</th>

                        <th class="align-middle">Giờ</th>
                        <th class="align-middle">Km</th>
                        <th class="align-middle">Giờ đêm</th>
                        <th class="align-middle">Giờ tự động</th>
                
                        <th class="align-middle">Tổng</th>
                        <th class="align-middle">Đã nạp</th>
                        <th class="align-middle">Còn thiếu</th>
                    </tr>
                    {{-- <tr>
                        @if ($lyThuyetExams->isNotEmpty())
                            @foreach ($lyThuyetExams as $exam)
                                <th class="text-nowrap">{{ $exam->name }}</th>
                            @endforeach
                        @else
                            <th>--</th>
                        @endif
                        @if ($thucHanhExams->isNotEmpty())
                            @foreach ($thucHanhExams as $exam)
                                <th class="text-nowrap">{{ $exam->name }}</th>
                            @endforeach
                        @else
                            <th>--</th>
                        @endif
                        @if ($totNghiepExams->isNotEmpty())
                            @foreach ($totNghiepExams as $exam)
                                <th class="text-nowrap">{{ $exam->name }}</th>
                            @endforeach
                        @else
                            <th>--</th>
                        @endif
                    </tr> --}}
                </thead>
                <tbody>
                    @php
                        $courseCount = $student->courses->count();
                    @endphp
                    
                    @foreach ($student->courses as $index => $course)
                        <tr class="student-main-row" data-student-id="{{ $student->id }}">
                            @if ($index == 0)
                                <td rowspan="{{ $courseCount }}">{{ $loop->iteration }}</td>
                                <td rowspan="{{ $courseCount }}">{{ $student->student_code }}</td>
                            @endif
                    
                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($course->ranking->name ?? 'Không xếp hạng') }}
                                </span>
                            </td>
                    
                            @if ($index == 0)
                                <td rowspan="{{ $courseCount }}" class="text-nowrap text-start">
                                    <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}" class="toggle-detail" style="padding: 2px 12px;">
                                        {{ $student->name }}
                                    </a>
                                </td>
                                <td rowspan="{{ $courseCount }}">{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                                <td rowspan="{{ $courseCount }}">
                                    @if($student->gender == 'male') Nam
                                    @elseif($student->gender == 'female') Nữ
                                    @else Khác
                                    @endif
                                </td>
                            @endif
                    
                            <td>{{ \Carbon\Carbon::parse($course->pivot->contract_date)->format('d/m/Y') }}</td>
                    
                            @if ($index == 0)
                                <td rowspan="{{ $courseCount }}" class="text-center">{{ $student->identity_card ?? '--' }}</td>
                            @endif
                    
                            <td>{{ \Carbon\Carbon::parse($course->pivot->health_check_date)->format('d/m/Y') }}</td>
                    
                            @if ($index == 0)
                                <td rowspan="{{ $courseCount }}" class="text-nowrap text-start">{{ $student->address ?? '--' }}</td>
                                <td rowspan="{{ $courseCount }}">
                                    @if ($student->card_id)
                                        {{ $student->card_id }}
                                    @else
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCardModal" 
                                            onclick='setStudentId({{ $student->id }}, {!! json_encode($student->name) !!}, {!! json_encode($student->student_code) !!})'>
                                            Gán
                                        </button>
                                    @endif
                                </td>
                            @endif
                    
                            {{-- <td>
                                <a href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}">
                                    <span class="badge bg-success">{{ $course->code }}</span>
                                </a>
                            </td> --}}
                            <td class="text-nowrap">
                                @foreach ($teachers as $teacher)
                                    @if ($teacher->id == $course->pivot->teacher_id)
                                        {{ $teacher->name }}
                                    @endif
                                @endforeach
                            </td>
                    
                            {{-- Phần thông tin học tập theo khóa học --}}
                            <td></td>
                            <td>0 new</td>
                            <td>0 new</td>
                            <td>
                                <button class="btn btn-sm btn-info"
                                    data-student-id="{{ $student->id }}"
                                    data-course-id="{{ $course->id }}"
                                    onclick="showStudyDetails(this)">
                                    {{ $totalHours[$student->id][$course->id] ?? 0 }}/{{ $course->duration ?? '-' }}
                                </button>
                            </td>
                            {{-- <td>{{ $totalKm[$student->id][$course->id] }}/{{ $course->km ?? '-' }}</td>
                            <td>{{ $totalNightHours[$student->id][$course->id] ?? 0 }}</td>
                            <td>{{ $totalAutoHours[$student->id][$course->id] ?? 0 }}</td> --}}
                             <td>{{ $totalKm }}</td>
                            <td>{{ $totalNightHours }}</td>
                            <td>{{ $totalAutoHours }}</td>
                            <td>
                                @if(isset($remainingFees[$student->id][$course->id]))    
                                    {{ number_format($course->pivot->tuition_fee, 0, ',', '.') }} VND
                                @else
                                    0 VND
                                @endif
                            </td>
                            <td>
                                @if(isset($remainingFees[$student->id][$course->id]))    
                                    {{ number_format($courseFees[$student->id][$course->id], 0, ',', '.') }} VND
                                @else
                                    0 VND
                                @endif
                            </td>
                            <td>
                                @if(isset($remainingFees[$student->id][$course->id]))
                                    <span class="badge bg-danger">
                                        {{ number_format($remainingFees[$student->id][$course->id], 0, ',', '.') }} VND
                                    </span>
                                @else
                                    <span class="badge bg-success">0 VND</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    {{ 
                                        $course->pivot->status == 1 ? 'bg-success' : 
                                        ($course->pivot->status == 2 ? 'bg-warning' : 
                                        ($course->pivot->status == 3 ? 'bg-primary' : 'bg-secondary')) 
                                    }}">
                                    {{ 
                                        $course->pivot->status == 0 ? 'Chưa học' : 
                                        ($course->pivot->status == 1 ? 'Đang học' : 
                                        ($course->pivot->status == 2 ? 'Bỏ học' : 'Đã tốt nghiệp')) 
                                    }}
                                </span>
                            </td>
                    
                            @if ($index == 0)
                                <td rowspan="{{ $courseCount }}" class="text-nowrap">
                                    <a 
                                        href="{{ route('student.course.action', ['student' => $student->id, 'course' => $course->id]) }}"
                                        class="btn btn-sm btn-info m-1" 
                                        style="padding: 2px 12px;">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning m-1">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger m-1" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            {{-- {{ $students->links('pagination::bootstrap-5') }} --}}
        </div>
    </div>
</div>

@endsection
@section('js')

<script>
    const rankingFees = @json($rankings->pluck('fee_ranking', 'id'));
    $(document).ready(function() {
       

        $('#ranking_id').select2({
            placeholder: "-- Chọn hạng bằng --",
            allowClear: true
        });

        const newOption =new Option('Chọn hạng bằng', null)
        $('#ranking_id').prepend(newOption);

        $('#ranking_id').trigger('change.select2');

        function calculateRemainingFee() {
            const totalFee = parseFloat(unformatNumber($('#fee_ranking_input').val())) || 0;
            const paidFee = parseFloat(unformatNumber($('#paid_fee_input').val())) || 0;
            const remaining = totalFee - paidFee;
            $('#remaining_fee_input').val(formatNumber(remaining > 0 ? remaining : 0));
        }

        $('#ranking_id').on('change', function () {
            const selectedId = $(this).val();
            const fee = rankingFees[selectedId] || 0;
            $('#fee_ranking_input').val(formatNumber(fee));
            calculateRemainingFee();
        });

        $('#paid_fee_input').on('input', function () {
            calculateRemainingFee();
        });

        const selectedId = $('#ranking_id').val();
        if (selectedId) {
            const fee = rankingFees[selectedId] || 0;
            $('#fee_ranking_input').val(formatNumber(fee));
            calculateRemainingFee();
        }
    });
</script>

<script>
    function previewSelectedImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('previewImage');
        const placeholder = document.getElementById('placeholderText');
        const removeBtn = document.querySelector('.remove-image');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                removeBtn.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage(event) {
        event.stopPropagation(); // Không mở lại file picker khi bấm nút close
        const input = document.getElementById('image');
        const preview = document.getElementById('previewImage');
        const placeholder = document.getElementById('placeholderText');
        const removeBtn = document.querySelector('.remove-image');

        input.value = '';
        preview.src = '#';
        preview.style.display = 'none';
        placeholder.style.display = 'block';
        removeBtn.style.display = 'none';
    }

    // Ẩn nút close lúc đầu
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.remove-image').style.display = 'none';
    });
</script>
@endsection
