@extends('layouts.admin')
@section('css')
<link href="{{ asset("assets/libs/RWD-Table-Patterns/css/rwd-table.min.css") }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="card">
    <div class="card-body">

        <form method="GET" action="{{ route('leads.index') }}" class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2">
                <label class="form-label fw-bold">Tên</label>
                <select name="name" id="name" class="form-select">
                    <option value=""></option>
                    @foreach ($leadsAll as $lead)
                        <option value="{{ $lead->id }}" {{ request('name') == $lead->id ? 'selected' : '' }}>{{ $lead->name }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ request('name') }}"> --}}
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2">
                <label class="form-label fw-bold">Người phụ trách</label>
                <select name="assigned_to" class="form-select mb-2" id="assigned_to">
                    <option value="">Chọn người phụ trách</option>
                    @foreach ($saleSupports as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2 position-relative">
                <label class="form-label fw-bold">Ngày bắt đầu</label>
                <input 
                    type="text" 
                    name="start_date"
                    value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}"
                    class="form-control real-date" autocomplete="off"
                    placeholder="dd/mm/yyyy" 
                />

                {{-- <div class="datepicker-button" style="position: absolute; right: 20px; top: 38px; cursor: pointer;">📅</div> --}}
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2 position-relative">
                <label class="form-label fw-bold">Ngày kết thúc</label>
                <input 
                    type="text" 
                    placeholder="dd/mm/yyyy" 
                    name="end_date" 
                    value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}"
                    class="form-control real-date" autocomplete="off">
                {{-- <div class="datepicker-button" style="position: absolute; right: 20px; top: 38px; cursor: pointer;">📅</div> --}}
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2">
                <label class="form-label fw-bold">Mức độ quan tâm</label>
                <select name="interest_level" class="form-select mb-2">
                    <option value="">Chọn mức độ quan tâm</option>
                    @foreach (App\Models\Student::getOptions('interest_level') as $key => $label)
                        <option value="{{ $key }}" {{ request('interest_level') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-2 mt-2 mb-2">
                <label for="">&nbsp;</label>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mb-1">
                        <b>Tìm Kiếm</b>
                    </button>
                    <div class="ms-2">
                        <a href="{{ route('leads.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('page-action-add-button')
    <div class="d-flex justify-content-between align-items-center">
        <a type="button"
            href="{{ route('leads.create') }}"  
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;"
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo Lead</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <a href="{{ route('leads.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <div class="responsive-table-plugin">
            <div class="table-responsive">
                <table id="tech-companies-1" class="table table-striped">
                {{-- <table class="table mt-3 table-bordered" style="width: max-content; min-width: 100%;"> --}}
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">STT</th>
                            {{-- <th>Mã học viên</th> --}}
                            <th class="text-white">Họ tên</th>
                            <th class="text-white">Người phụ trách</th>
                            <th class="text-white">Email</th>
                            <th class="text-white">SĐT</th>
                            <th class="text-white">Nguồn</th>
                            <th class="text-white">Mức độ quan tâm</th>
                            <th class="text-white">Trạng thái</th>
                            {{-- <th class="text-white">HV-KH</th> --}}
                            {{-- <th class="text-white">Giới tính</th> --}}
                            {{-- <th class="text-white">Địa chỉ</th> --}}
                            <th class="text-white">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $key = ($leads->currentPage() - 1) * $leads->perPage();
                        @endphp
                        @foreach ($leads as $lead)
                            <tr>
                                <td>{{ ++$key }}</td>
                                {{-- <td>{{ $lead->student_code }}</td> --}}
                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->saleSupport->name ?? '-' }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->leadSource->name ?? '-' }}</td>
                                {{-- <td>{{ $lead->gender == 'male' ? "Nam" : "Nữ" }}</td> --}}
                                {{-- <td>{{ $lead->address ?? '-' }}</td> --}}
                                <td>
                                    <span class="badge 
                                        @switch($lead->interest_level)
                                            @case(1) bg-secondary @break
                                            @case(2) bg-warning text-dark @break
                                            @case(3) bg-danger @break
                                            @default bg-light text-dark
                                        @endswitch">
                                        {{ $lead->interest_level_label}}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @switch($lead->status_lead)
                                            @case(1) bg-primary @break
                                            @case(2) bg-info @break
                                            @case(3) bg-success @break
                                            @case(4) bg-danger @break
                                            @case(5) bg-secondary @break
                                            @default bg-light text-dark
                                        @endswitch">
                                        {{ $lead->status_lead_label}}
                                    </span>
                                </td>
                                {{-- <td></td> --}}
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <a href="{{ route('leads.show', $lead->id) }}" class="btn btn-sm btn-info ps-2 pe-2 pt-0 pb-0"><i class="mdi mdi-eye-outline fs-4"></i></a>
                                        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0"><i class="mdi mdi mdi-square-edit-outline fs-4"></i></a>
                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ps-2 pe-2 pt-0 pb-0"><i class="mdi mdi-trash-can-outline fs-4"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $leads->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
@section('js')
<script>
    $('#assigned_to').select2({
            placeholder: 'Chọn người phụ trách',
            width: '100%',
            allowClear: true
        });
</script>
<script>
    $(document).ready(function() {   
        $('#name').select2({
            placeholder: "-- Chọn lead --",
            allowClear: true,
        });
    });
</script>
<script src="{{ asset("assets/libs/RWD-Table-Patterns/js/rwd-table.min.js") }}"></script>
<script src="{{ asset("assets/js/pages/responsive-table.js") }}"></script>
@endsection
