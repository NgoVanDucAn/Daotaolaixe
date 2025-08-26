<div class="modal fade" id="addStudentToCourse" tabindex="-1" aria-labelledby="addStudentToCourseLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentToCourseLabel">Chuyển sang Học viên - Khoá học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body">
                <form id="studentToCourseForm" action="{{ route('leads.addCourse') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <label class="form-label fw-bold">Thông tin học viên <span style="color: red;">*</span></label>
                                <select class="form-select" name="student_id" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <label class="form-label fw-bold">Khoá học <span style="color: red;">*</span></label>
                                <select class="form-select" name="course_id" id="course_id_modal" required>
                                    <option value="">Vui lòng chọn</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" data-tuition-fee="{{ $course->tuition_fee }}" data-vehicle-type="{{ $course->ranking->vehicle_type ?? 3 }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->code }}</option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="p-3 border rounded mt-2 d-none" id="no_code">
                        <div class="row g-3">
                            <div class="col-md-6" id="ranking_wrapper">
                                <label class="form-label fw-bold">Ranking</label>
                                <select class="form-select" name="ranking_id" id="ranking_id">
                                    <option value="">Chọn ranking</option>
                                    @foreach ($rankings as $ranking)
                                        <option value="{{ $ranking->id }}" data-fee-ranking="{{ $ranking->fee_ranking }}" data-vehicle-type="{{ $ranking->vehicle_type }}">
                                            {{ $ranking->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tiền nộp trước</label>
                                <input type="text" name="paid_fee" class="form-control currency-input">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 border rounded mt-2 d-none" id="vehicle_car">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="give_chip_hour" class="form-label fw-bold">Số giờ chip tặng</label>
                                <input placeholder="HH:mm" type="text" name="give_chip_hour" class="form-control time-input" value="{{ old('give_chip_hour') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="reserved_chip_hours" class="form-label fw-bold">Số giờ đặt chip</label>
                                <input placeholder="HH:mm" type="text" name="reserved_chip_hours" class="form-control time-input" value="{{ old('reserved_chip_hours') }}">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 border rounded mt-2">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Học phí <span style="color: red;">*</span></label>
                                <input type="text" name="tuition_fee" class="form-control currency-input" required>
                                @error('tuition_fee')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày khám sức khỏe</label>
                                <input 
                                    type="text" 
                                    placeholder="dd/mm/yyyy"
                                    class="form-control real-date" autocomplete="off" 
                                    id="health_check_date" name="health_check_date" 
                                    value="{{ old('health_check_date') ? \Carbon\Carbon::parse(old('health_check_date'))->format('d/m/Y') : '' }}"
                                >
                                @error('health_check_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày ký hợp đồng</label>
                                <input 
                                    type="text" 
                                    placeholder="dd/mm/yyyy"
                                    class="form-control real-date" autocomplete="off" 
                                    id="contract_date" name="contract_date" 
                                    value="{{ old('contract_date') ? \Carbon\Carbon::parse(old('contract_date'))->format('d/m/Y') : '' }}"
                                >
                                @error('contract_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sân học</label>
                                <select name="stadium_id" id="stadium_id_modal" class="form-select">
                                    <option value="">-- Chọn sân học --</option>
                                    @foreach ($stadiums as $stadium)
                                        <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>{{ $stadium->location }}</option>
                                    @endforeach
                                </select>
                                @error('stadium_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giáo viên</label>
                                <select name="learn_teacher_id" id="learn_teacher_id_modal" class="form-select">
                                    <option value="">-- Chọn giáo viên --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('learn_teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nhân viên Sale</label>
                                <select name="sale_id" id="sale_id_modal" class="form-select">
                                    <option value="">-- Chọn sale --</option>
                                    @foreach ($saleSupports as $sale)
                                        <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                                    @endforeach
                                </select>
                                @error('sale_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Ghi chú</label>
                                <textarea name="note" rows="1" class="form-control">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
                <button type="submit" form="studentToCourseForm" class="btn btn-primary">
                    <i class="bi bi-save"></i> Lưu
                </button>
            </div>

        </div>
    </div>
</div>