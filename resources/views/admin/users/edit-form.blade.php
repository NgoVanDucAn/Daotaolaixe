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

<form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- <div class="row">
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="name" class="form-label">Họ và tên:</label>
            <input type="text" name="name" class="form-control" placeholder="your name" value="{{ old('name', $user->name ?? '') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="your email" value="{{ old('email', $user->email ?? '') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="phone" class="form-label">Số điện thoại:</label>
            <input type="text" name="phone" class="form-control" placeholder="your phone" value="{{ old('phone', $user->phone ?? '') }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="gender" class="form-label">Giới tính:</label>
            <select name="gender" class="form-control">
                <option value="male" {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ old('gender', $user->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
            </select>
            @error('gender')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="dob" class="form-label">Ngày sinh:</label>
            <input type="date" placeholder="dd/mm/yyyy"name="dob" class="form-control" value="{{ old('dob', $user->dob?->format('Y-m-d') ?? '') }}">
            @error('dob')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="identity_card" class="form-label">Căn cước công dân:</label>
            <input type="number" name="identity_card" class="form-control" placeholder="Identity Card" value="{{ old('identity_card', $user->identity_card ?? '') }}">
            @error('identity_card')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="password" class="form-label">Mật khẩu mới:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="nhập mật khẩu mới">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu mới:</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="nhập lại mật khẩu">
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="address" class="form-label">Địa chỉ:</label>
            <input type="text" name="address" class="form-control" placeholder="your address" value="{{ old('address', $user->address ?? '') }}">
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        @if(view()->exists($extraFields))
            @include($extraFields)
        @endif

        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <label for="image">Ảnh đại diện:</label>
            <input type="file" id="image" name="image" class="form-control">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @if($user->image)
                <img src="{{ asset('storage/' . $user->image) }}" width="100" class="mt-2" alt="Profile Image">
            @endif
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button> --}}
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card rounded-4">
                <div class="card-body row">
                    <div class="form-group col-12 col-md-6">
                        <label for="name" class="form-label">Tên</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name ?? '') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email ?? '') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="male" {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $user->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gender')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $user->phone ?? '') }}" required>
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6 position-relative">
                        <label for="dob" class="form-label">Ngày sinh</label>
                        <input type="text" placeholder="dd/mm/yyyy"name="dob" class="form-control real-date" autocomplete="off" value="{{ old('dob', $user->dob?->format('d/m/Y') ?? '') }}">
                        @error('dob')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="identity_card">CMT/CCCD</label>
                        <input 
                            type="text" 
                            name="identity_card" 
                            class="form-control" 
                            placeholder="Nhập số CMT/CCCD"
                            value="{{ old('identity_card', $user->identity_card ?? '') }}"
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
                            placeholder="Nhập địa chỉ của bạn"
                            value="{{ old('address', $user->address ?? '') }}"
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
                            <option value="active" {{ request('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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

                        <div class="image-upload-card" onclick="document.getElementById('image').click();" id="imageCard">
                            @if ($user->image)
                                <img id="previewImage" src="{{ asset('storage/' . $user->image) }}">
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
</form>

@section('js')
    <script>
        $(document).ready(function() {
            $('#ranking_ids').select2({
                placeholder: 'Chọn một tùy chọn',
                width: '100%',
            });
            $('#vehicle_id').select2({
                placeholder: 'Chọn một tùy chọn',
                width: '100%',
            });
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