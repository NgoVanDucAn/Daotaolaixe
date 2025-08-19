<input type="hidden" name="role" value="instructor">
<div class="form-group col-12 col-md-6">
    <label for="ranking_ids">Hạng</label>
    <select name="ranking_ids[]" id="ranking_ids" class="form-control select2" multiple>
        @foreach($rankings as $ranking)
            <option value="{{ $ranking->id }}" {{ (collect(old('ranking_ids'))->contains($ranking->id)) ? 'selected' : '' }}>{{ $ranking->name }}</option>
        @endforeach
    </select>
    @error('ranking_ids')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group col-12 col-md-6">
    <label for="license_number">GPLX</label>
    <input type="text" name="license_number" id="license_number" class="form-control" value="{{ old('license_number') }}">
    @error('license_number')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group col-12 col-md-6">
    <label for="vehicle_id">Biển số xe</label>
    <select name="vehicle_id" class="form-control" id="vehicle_id">
        <option value="">Chọn xe</option>
        @foreach($vehicles as $vehicle)
        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->license_plate }} ({{ $vehicle->model }})</option>
        @endforeach
    </select>
    @error('vehicle_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
