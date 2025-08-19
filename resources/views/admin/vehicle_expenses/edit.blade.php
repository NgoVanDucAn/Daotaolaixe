@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('vehicle-expenses.update', $vehicleExpense->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6 mb-3">
                    <label for="vehicle_id">Xe</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" required>
                        <option value="">-- Chọn xe --</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $vehicleExpense->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
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
                    <select name="type" id="type" class="form-control" required>
                        <option value="">-- Chọn loại --</option>
                        @foreach (App\Models\VehicleExpense::getTypeOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $vehicleExpense->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="time">Thời gian</label>
                    <input type="datetime-local" name="time" id="time" class="form-control"
                           value="{{ old('time', \Carbon\Carbon::parse($vehicleExpense->time)->format('Y-m-d\TH:i')) }}" required>
                    @error('time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="user_id">Người chi</label>
                    <select name="user_id" id="user_id" class="form-control select2">
                        <option value="">-- Không chọn --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $vehicleExpense->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="amount">Số tiền</label>
                    <input type="text" name="amount" id="amount" class="form-control currency-input"
                           value="{{ old('amount', $vehicleExpense->amount) }}" required>
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="note">Ghi chú</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note', $vehicleExpense->note) }}</textarea>
                    @error('note')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: '-- Chọn --',
            allowClear: true
        });
    });
</script>
@endsection
