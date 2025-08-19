@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="filter-options mb-3">
            
            <form id="examSchedulesFilterForm" class="row" method="GET">
                <div class="col-12 col-lg-3 col-md-4 mb-3">
                    <label class="form-label fw-bold">Biển số</label>

                    <select name="license_plate" id='license_plate_filter' class="form-select">
                        <option value="">-- Chọn xe --</option>
                        @foreach ($vehiclesAll as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('license_plate') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->license_plate }}</option>
                        @endforeach
                    </select>

                    {{-- <input type="text" name="license_plate" class="form-control" placeholder="Nhập biển số" value="{{ request('license_plate') }}"> --}}
                </div>
                <div class="col-6 col-lg-3 col-md-4 mb-4 position-relative">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input type="text" placeholder="dd/mm/yyyy"name="start_date" value="{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '' }}" class="form-control real-date" autocomplete="off" placeholder="Ngày bắt đầu">
                </div>
                <div class="col-6 col-lg-3 col-md-4 mb-4 position-relative">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input type="text" placeholder="dd/mm/yyyy"name="end_date" value="{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '' }}" class="form-control real-date" autocomplete="off" placeholder="Ngày kết thúc">
                </div>


                <div class="col-12 col-lg-3 col-md-12 mb-2">
                    <label for="">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mb-1">
                            <b>Tìm Kiếm</b>
                        </button>
                        <div class="ms-2">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-danger mb-1" id="clearFilters"><b><i class="mdi mdi-sync me-1"></i>Refresh</b></a>
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
            href="{{ route('vehicles.create') }}"
            type="button" 
            class="btn"
            style="color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 8px;" 
        >
            <span style="font-size: 20px; margin:0 4px 2px 0;">+</span> <span>Tạo xe</span>
        </a>
    </div>
@endsection

<div class="card">
    <div class="card-body">
        <a href="{{ route('vehicles.create') }}" 
            class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center position-fixed"
            style="width: 50px; height: 50px; font-size: 30px; bottom: 10%; right: 1%; z-index: 99;">
                +
        </a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">STT</th>
                        <th rowspan="2" class="align-middle">Biển số</th>
                        <th rowspan="2" class="align-middle">Model</th>
                        <th rowspan="2" class="align-middle">Hạng</th>
                        <th rowspan="2" class="align-middle">Loại</th>
                        <th rowspan="2" class="align-middle">Màu sắc</th>
                        <th rowspan="2" class="align-middle">Số GPTL</th>
                        <th rowspan="2" class="align-middle">Năm SX</th>
                        <th colspan={{ count($practicalFields) }}>Số giờ chạy được</th>
                        <th rowspan="2" class="align-middle">Ghi chú</th>
                        <th rowspan="2" class="align-middle">Hành động</th>
                    </tr>
                    <tr>
                        @foreach ($practicalFields as $field)
                            <th>{{ $field->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="min-width: 100px">{{ $vehicle->license_plate }}</td>
                            <td>{{ $vehicle->model }}</td>
                            <td style="min-width: 80px">{{ $vehicle->ranking->name ?? '-' }}</td>
                            <td style="min-width: 80px">{{ $vehicle->type }}</td>
                            <td>{{ $vehicle->color }}</td>
                            <td>{{ $vehicle->training_license_number }}</td>
                            <td>{{ $vehicle->manufacture_year }}</td>
                            @foreach ($practicalFields as $fieldName)
                                <td>
                                    {{ $vehicle->practical_hours_by_field[$fieldName->name] ?? 0 }}
                                </td>
                            @endforeach
                            <td>{{ $vehicle->description }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning ps-2 pe-2 pt-0 pb-0">
                                        <i class="mdi mdi mdi-square-edit-outline fs-4"></i>
                                    </a>
                                    <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-sm btn-danger ps-2 pe-2 pt-0 pb-0">
                                            <i class="mdi mdi-trash-can-outline fs-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $vehicles->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            const selectIds = ['#license_plate_filter'];

            selectIds.forEach(function(id) {
                $(id).select2({
                    placeholder: '-- Chọn --',
                    allowClear: true,
                    width: '100%'
                });
            });
        }); 
    </script>
    
@endsection
