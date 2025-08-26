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

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- <div class="row">
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="name" class="form-label">Tên:</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', '') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', '') }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="phone" class="form-label">Điện thoại:</label>
                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', '') }}" required>
                @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="identity_card">Căn cước công dân:</label>
                <input type="text" id="identity_card" name="identity_card" value="{{ old('identity_card') }}" class="form-control">
                @error('identity_card')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="gender" class="form-label">Giới tính:</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="dob" class="form-label">Ngày sinh:</label>
                <input type="date" placeholder="dd/mm/yyyy"class="form-control" id="dob" name="dob" value="{{ old('dob') }}">
                @error('dob')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="password" class="form-label">Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="address" class="form-label">Địa chỉ:</label>
                <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="sale_support" class="form-label">Người hỗ trợ:</label>
                <select class="form-select" id="sale_support" name="sale_support">
                    <option value="">Chọn người hỗ trợ</option>
                    @foreach($saleSupports as $support)
                        <option value="{{ $support->id }}" {{ old('sale_support') == $support->id ? 'selected' : '' }}>{{ $support->name }}</option>
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
                        <option value="{{ $source->id }}" {{ old('lead_source') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                    @endforeach
                </select>
                @error('lead_source')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="1" value="{{ old('description') }}"></textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <label for="image">Ảnh đại diện:</label>
                <input type="file" id="image" name="image" class="form-control">
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Thêm mới</button> --}}
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card rounded-4">
                    <div class="card-body row">
                        <div class="form-group col-12 col-md-6">
                            <label for="name" class="form-label">Tên</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', '') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', '') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="phone" class="form-label">Điện thoại:</label>
                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', '') }}" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="gender" class="form-label">Giới tính</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
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
                                class="form-control real-date" autocomplete="off" 
                                id="dob" name="dob" 
                                value="{{ old('dob') ? \Carbon\Carbon::parse(old('dob'))->format('d/m/Y') : '' }}"
                            >
                            @error('dob')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="identity_card">Căn cước công dân</label>
                            <input type="text" id="identity_card" name="identity_card" value="{{ old('identity_card') }}" class="form-control">
                            @error('identity_card')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text"  autocomplete="new-address" name="address" class="form-control" id="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="password" class="form-label">Mật khẩu <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Nếu không nhập mật khẩu thì mật khẩu mặc định là 123456" style="cursor: pointer;">&#x3f;</span></label>
                            <input type="password" autocomplete="new-password" name="password" class="form-control" id="password" placeholder="123456" >
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="card_id">Số thẻ</label>
                            <input type="text" id="card_id" name="card_id" value="{{ old('card_id') }}" class="form-control">
                            @error('card_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="123456">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="form-group col-12 col-md-6"> --}}
                            {{-- <label for="ranking" class="form-label">Giới tính:</label>
                            <select class="form-select" id="ranking" name="ranking">
                                <option value="male" {{ old('ranking') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('ranking') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('ranking') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('ranking')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror --}}
                            {{-- <label class="form-label">Hạng thi</label>
                            <select name="ranking_id]" id="ranking_id" class="form-select select2" >
                                @foreach($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ ($ranking->id == old('ranking_ids')) ? 'selected' : '' }}>
                                        {{ $ranking->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ranking_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div> --}}
                        {{-- <div class="form-group col-12 col-md-6">
                            <label for="license_number">GPLX:</label>
                            <input type="text" name="license_number" id="license_number" class="form-control" value="{{ old('license_number') }}">
                            @error('license_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        {{-- <div class="form-group col-12 col-md-6">
                            <label for="vehicle_id">Biển số xe:</label>
                            <select name="vehicle_id" class="form-control">
                                <option value="">Chọn xe</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ (old('vehicle_id', $user->vehicle_id ?? 0) == $vehicle->id) ? 'selected' : '' }}>{{ $vehicle->license_plate }} ({{ $vehicle->model }})</option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <div class="form-group col-12 col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                             <label for="ranking_id" class="form-label">Hạng bằng đăng ký</label>
                            <select class="form-select select2" id="ranking_id" name="ranking_id">
                                <option value="">-- Chọn hạng bằng --</option>
                                @foreach ($rankings as $ranking)
                                    <option value="{{ $ranking->id }}" {{ old('ranking_id') == $ranking->id ? 'selected' : '' }}>{{ $ranking->name }}</option>
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
                                value="{{ old('date_of_profile_set') ? \Carbon\Carbon::parse(old('date_of_profile_set'))->format('d/m/Y') : '' }}"
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
                            <label for="image">Ảnh đại diện:</label>
                            {{-- <input type="file" id="image" name="image" class="form-control">
                            @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror --}}
                            <div class="image-upload-card" onclick="document.getElementById('image').click();" id="imageCard">
                                <span id="placeholderText">Chọn hình ảnh</span>
                                <img id="previewImage" src="#" alt="Preview" style="display: none;">
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
                        <input type="text" name="fee_ranking" id="fee_ranking_input" class="form-control currency-input" id="" value="" required>
                        @error('fee_ranking')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-9 col-md-4">
                        <label for="paid_fee_input" class="form-label">Lệ phí đã đóng</label>
                        <input type="text" name="paid_fee" id="paid_fee_input" class="form-control currency-input">
                        @error('paid_fee')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-9 col-md-4">
                        <label for="remaining_fee_input" class="form-label">Lệ phí còn thiếu</label>
                        <input type="text" name="remaining_fee" id="remaining_fee_input" class="form-control currency-input" disabled>
                        @error('remaining_fee')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> 
                </div>
            </div>
        </div>
    </form>
@endsection
@section('js')
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
<script>
    const rankingFees = @json($rankings->pluck('fee_ranking', 'id'));
    $(document).ready(function() {
        $('#ranking_id').select2({
            placeholder: "-- Chọn hạng bằng --",
            allowClear: true
        });

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
@endsection
