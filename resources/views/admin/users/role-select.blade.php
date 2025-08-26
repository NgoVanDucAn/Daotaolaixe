<div class="col-12 col-md-6 mb-3">
    <label for="role">Vai trò</label>
    <select name="role" class="form-control">
        @foreach($roles as $role)
        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
    @error('role')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>