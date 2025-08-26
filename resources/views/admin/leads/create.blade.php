@extends('layouts.admin')

@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('leads.index'),
            'title' => 'Quay về trang quản lý lead'
        ])
    </div>
@endsection


@section('content')
    <form action="{{ route('leads.store') }}" method="POST">
    @csrf
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card rounded-4">
                    <div class="card-body row">
                        <div class="form-group col-12 col-md-6">
                            <label for="student_id" class="form-label">Chọn học viên(Nếu có)</label>
                            <select class="form-select" id="student_id" name="student_id">
                                <option value="">Chọn học viên</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="name" class="form-label">Họ tên</label>
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
                            <label for="phone" class="form-label">SĐT</label>
                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', '') }}" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}" required>
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
                                value="{{ old('dob') ? \Carbon\Carbon::parse(old('dob'))->format('d/m/Y') : '' }}"
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
                            <textarea class="form-control" id="description" name="description" value="{{ old('description') }}"></textarea>
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
                                    <option value="{{ $support->id }}" {{ old('sale_support') == $support->id ? 'selected' : '' }}>{{ $support->name }}</option>
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
    
@endsection
@section('js')

<script>
    function formatDate(isoDate) {
        if (!isoDate) return '';
        const date = new Date(isoDate);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    $('#student_id').on('change', function () {
        const studentId = $(this).val();
        if (!studentId) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: `/api/students/${studentId}`,
            type: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            success: function (response) {
                const data = response.data;
                $('#name').val(data.name || '');
                $('#email').val(data.email || '');
                $('#phone').val(data.phone || '');
                $('#address').val(data.address || '');
                $('#dob').val(formatDate(data.dob));
                $('#lead_source').val(data.lead_source || '');
                // $('#interest_level').val(data.interest_level || '');
                // $('#status_lead').val(data.status_lead || '');
                $('#sale_support').val(data.sale_support || '');
                $('#description').val(data.description || '');
            },
            error: function (xhr, status, error) {
                console.error('Lỗi khi lấy thông tin học viên:', error);
                alert('Không thể lấy thông tin học viên.');
            }
        });
    });
</script>


</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#student_id').select2({
            placeholder: "Chọn học viên",
            allowClear: true
        });
    })
</script>
@endsection
