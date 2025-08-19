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

<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                    <div class="form-group col-12 col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', '') }}" required>
                        @error('phone')
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
                            value="{{ old('contract_date') ? \Carbon\Carbon::parse(old('dob'))->format('d/m/Y') : '' }}"
                        >
                        @error('dob')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="identity_card">CMT/CCCD</label>
                        <input 
                            type="text" 
                            id="identity_card" 
                            name="identity_card" 
                            value="{{ old('identity_card') }}" 
                            class="form-control"
                            placeholder="Nhập số CMT/CCCD"
                        >
                        @error('identity_card')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input
                            type="text" 
                            name="address" 
                            class="form-control" 
                            id="address" 
                            value="{{ old('address') }}"
                            placeholder="Nhập địa chỉ của bạn"
                        >
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(view()->exists($extraFields))
                        @include($extraFields)
                    @endif
                    
                    <div class="form-group col-12 col-md-6">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
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
                            <span id="placeholderText">Chọn hình ảnh</span>
                            <img id="previewImage" src="#" alt="Preview" style="display: none;">
                            <button type="button" id="remove_image" class="remove-image" onclick="removeImage(event)"></button>
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

        {{-- <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', '') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', '') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="phone" class="form-label">Điện thoại</label>
            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', '') }}" required>
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="identity_card">Căn cước công dân</label>
            <input type="text" id="identity_card" name="identity_card" value="{{ old('identity_card') }}" class="form-control">
            @error('identity_card')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
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
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="dob" class="form-label">Ngày sinh</label>
            <input type="date" placeholder="dd/mm/yyyy"class="form-control" id="dob" name="dob" value="{{ old('dob') }}">
            @error('dob')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}">
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        @if(view()->exists($extraFields))
            @include($extraFields)
        @endif

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="image">Ảnh đại diện</label>
            <input type="file" id="image" name="image" class="form-control">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Thêm mới</button> --}}
</form>
