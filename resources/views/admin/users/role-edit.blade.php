<div class="col-12 col-md-6 mb-3">
    <label for="roles">Vai trò</label>
    <select name="role" class="form-control">
        @foreach($roles as $role)
        <option value="{{ $role->name }}" {{ old('role', $user->roles->pluck('name')->first() ?? '') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
    @error('role')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>