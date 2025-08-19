document.addEventListener("DOMContentLoaded", function () {
    let defaultStudentId = '';
    let defaultStudentName = '';
    let defaultCourseId = '';
    let defaultCourseName = '';

    const startTimeInput = document.getElementById('date_start');
    const endTimeInput = document.getElementById('date_end');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const examCourseSelect = document.getElementById('exam_course_id');
    const examSelect = document.getElementById('exam_id');
    const dateStartField = document.getElementById('date-start-field');
    const dateEndField = document.getElementById('date-end-field');
    const examDateTimeField = document.getElementById('exam-date-time');
    const typeInput = document.getElementById('type');
    const examCourseTypeInput = document.getElementById('exam_course_type');

    // Reset modal khi đóng
    $('#addActivitieModal').on('hidden.bs.modal', function () {
        var modalForm = $(this).find('form');
        modalForm[0].reset();
        $('#learn_course_id').val('').trigger('change');
        $('#exam_course_id').val('').trigger('change');
        $('#vehicle_select').val('').trigger('change');
        $('#learn_student_id_select').val('').trigger('change');
        $('#exam_student_id_select').val('').trigger('change');
        $('#learn_teacher_id').val('').trigger('change');
        $('#exam_teacher_id').val('').trigger('change');
        $('#learn_student_id_select').empty();
        $('#exam_student_id_select').empty();
        $('#learn_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
        $('#exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
        $('#learn_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
        $('#exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
        $('#student-inputs').empty();
        $('#alert-message').addClass('d-none');
        typeInput.value = '';
        if (examCourseTypeInput) examCourseTypeInput.value = '';
    });

    // Khởi tạo modal khi mở
    $('#addActivitieModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        defaultStudentId = button.data('student-id');
        defaultStudentName = button.data('student-name') || `Học viên ID ${defaultStudentId}`;
        defaultCourseId = button.data('course-id');
        defaultCourseName = button.data('course-name') || `Khóa học ID ${defaultCourseId}`;
        var calendarType = button.data('calendar-type');
        var examCourseType = button.data('exam-course-type');
        
        var modalTitle = 'Thêm Mới Lịch';
        switch (calendarType) {
            case 'study_practice':
                modalTitle = 'Thêm Lịch Học Thực Hành';
                break;
            case 'study_theory':
                modalTitle = 'Thêm Lịch Học Lý Thuyết';
                break;
            case 'exam_practice':
                modalTitle = 'Thêm Lịch Thi Thực Hành';
                break;
            case 'exam_theory':
                modalTitle = 'Thêm Lịch Thi Lý Thuyết';
                break;
            case 'exam_graduation':
                modalTitle = 'Thêm Lịch Thi Tốt Nghiệp';
                break;
            case 'exam_certification':
                modalTitle = 'Thêm Lịch Thi Sát Hạch';
                break;
            default:
                modalTitle = 'Thêm Mới Lịch';
                break;
        }
        $('#addActivitieModalLabel').text(modalTitle);

        if (calendarType === 'study_practice' || calendarType === 'study_theory') {
            typeInput.value = 'study';
            updateForm('study');
            var selectLearn = modal.find('#learn_student_id_select');
            selectLearn.empty();
            selectLearn.append(new Option(`${defaultStudentName}`, defaultStudentId, true, true));
            var learnSelect = $('#learn_course_id');
            var examSelectA = $('#exam_course_id');
            learnSelect.empty().append(new Option(defaultCourseName, defaultCourseId, true, true));
            examSelectA.prop('disabled', true);
        } else {
            typeInput.value = 'exam';
            if (examCourseTypeInput) {
                examCourseTypeInput.value = examCourseType || '1';
            }
            updateForm('exam');
            var selectExam = modal.find('#exam_student_id_select');
            selectExam.empty();
            selectExam.append(new Option(`${defaultStudentName}`, defaultStudentId, true, true));
            var examSelectA = $('#exam_course_id');
            var learnSelect = $('#learn_course_id');
            examSelectA.empty().append(new Option(defaultCourseName, defaultCourseId, true, true));
            learnSelect.prop('disabled', true);
        }

        if (calendarType === 'study_practice' || calendarType === 'study_theory') {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            const studyTypeChange = calendarType == 'study_practice' ? 1 : (calendarType == 'study_theory' ? 0 : null);
            fetchAndUpdate(defaultCourseId, 'study', startTime, endTime, studyTypeChange);
        } else {
            const date = dateInput.value;
            const time = timeInput.value;
            const examCourseTypeChange = examCourseTypeInput.value;
            fetchAndUpdate(defaultCourseId, 'exam', date, time, examCourseTypeChange);
        }
    });

    function updateForm(selectedType) {
        document.querySelectorAll('#study-fields, #exam-fields').forEach(function(field) {
            field.style.display = 'none';
        });

        startTimeInput.value = '';
        endTimeInput.value = '';
        if (dateInput) dateInput.value = '';
        if (timeInput) timeInput.value = '';
        if (selectedType == 'exam') {
            dateStartField.style.display = 'none';
            dateEndField.style.display = 'none';
            examDateTimeField.style.display = 'flex';
            startTimeInput.disabled = true;
            endTimeInput.disabled = true;
            if (dateInput) dateInput.disabled = false;
            if (timeInput) timeInput.disabled = false;
            document.getElementById('exam-fields').style.display = 'block';
            $('#exam_course_id').select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn khóa học",
                allowClear: true,
                width: '100%'
            }).on('change', function () {
                const date = dateInput.value;
                const time = timeInput.value;
                const courseId = $(this).val();
                fetchAndUpdate(courseId, 'exam', date, time);
            });
            $('#exam_student_id_select').select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn học viên",
                allowClear: true,
                multiple: true,
                width: '100%'
            }).on('change', function () {
                const selectedOptions = $(this).val() || [];

                if (defaultStudentId && selectedOptions.includes(defaultStudentId)) {
                    showAlert(`Học viên "${defaultStudentName}" đã được chọn mặc định. Vui lòng không chọn lại.`);
                    selectedOptions.splice(selectedOptions.indexOf(defaultStudentId), 1);
                    $(this).val(selectedOptions).trigger('change');
                    return;
                }

                const $container = $('#student-inputs');
                $container.find('.student-input-group').each(function () {
                    const studentId = $(this).data('student-id');
                    if (studentId != defaultStudentId && !selectedOptions.includes(studentId.toString())) {
                        $(this).remove();
                    }
                });

                selectedOptions.forEach(function (studentId) {
                    if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                        const studentName = $(`#exam_student_id_select option[value="${studentId}"]`).text();
                        const groupHtml = `
                            <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
                                <div class="mb-2 font-semibold">${studentName}</div>
                                <div class="mb-2">
                                    <label class="block text-sm mb-1">Số báo danh:</label>
                                    <input type="text" name="students[${studentId}][exam_number]" class="w-full rounded border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">
                                        <input type="checkbox" name="students[${studentId}][pickup]" value="1" class="mr-1">
                                        Đăng ký đưa đón
                                    </label>
                                </div>
                            </div>
                        `;
                        $container.append(groupHtml);
                    }
                });
            });
        } else {
            dateStartField.style.display = 'block';
            dateEndField.style.display = 'block';
            examDateTimeField.style.display = 'none';
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
            if (dateInput) dateInput.disabled = true;
            if (timeInput) timeInput.disabled = true;
            document.getElementById('study-fields').style.display = 'block';
            $('#learn_course_id').select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn khóa học",
                allowClear: true,
                width: '100%'
            }).on('change', function () {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                const courseId = $(this).val();
                fetchAndUpdate(courseId, 'study', startTime, endTime);
            });
            $('#learn_student_id_select').select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn học viên",
                allowClear: true,
                multiple: true,
                width: '100%'
            });
        }
    }

    function showAlert(message) {
        const alertDiv = document.getElementById('alert-message');
        alertDiv.textContent = message;
        alertDiv.classList.remove('d-none');
        setTimeout(() => alertDiv.classList.add('d-none'), 5000);
    }

    function fetchAndUpdate(courseId, type, dateStart, dateEnd, holdType) {
        if (!courseId) return;

        let url = `/course-data/${type}/${courseId}`;
        if (type === 'exam') {
            url += `?date=${encodeURIComponent(dateStart)}&time=${encodeURIComponent(dateEnd)}&examType=${encodeURIComponent(holdType)}`;
        } else {
            url += `?date_start=${encodeURIComponent(dateStart)}&date_end=${encodeURIComponent(dateEnd)}&learnType=${encodeURIComponent(holdType)}`;
        }

        fetch(url).then(response => response.json()).then(data => {
            const subjectSelect = document.getElementById(type === 'study' ? 'learning_id' : 'exam_id');
            subjectSelect.innerHTML = (type === 'study')
                ? '<option value="">Chọn môn học</option>'
                : '<option value="">Chọn môn thi</option>';

            const subjects = type === 'study' ? data.course.learning_fields : data.course.exam_fields;
            if (type === 'exam') {
                subjectSelect.multiple = true;
                if ($.fn.select2 && $('#exam_id').hasClass('select2-hidden-accessible')) {
                    $('#exam_id').select2('destroy');
                }
                $('#exam_id').select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn môn thi",
                    allowClear: true
                });
            } else {
                subjectSelect.multiple = false;
            }
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });

            const studentSelect = document.getElementById(type === 'study' ? 'learn_student_id_select' : 'exam_student_id_select');
            studentSelect.innerHTML = '';
            
            const availableStudents = data.available_students || [];
            availableStudents.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = `${student.name} - ${student.student_code}`;
                studentSelect.appendChild(option);
            });
            let defaultStudents = [];
            if (defaultStudentId) {
                const isDefaultStudentAvailable = availableStudents.some(student => student.id == defaultStudentId);
                
                if (!isDefaultStudentAvailable) {
                    showAlert(`Học viên "${defaultStudentName}" đã có lịch trùng vào thời gian này. Vui lòng chọn thời gian khác.`);
                } else {
                    defaultStudents = [defaultStudentId];
                    if (type === 'exam') {
                        const $container = $('#student-inputs');
                        if ($container.find(`.student-input-group[data-student-id="${defaultStudentId}"]`).length === 0) {
                            const groupHtml = `
                                <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${defaultStudentId}">
                                    <div class="mb-2 font-semibold">${defaultStudentName}</div>
                                    <div class="mb-2">
                                        <label class="block text-sm mb-1">Số báo danh:</label>
                                        <input type="text" name="students[${defaultStudentId}][exam_number]" class="w-full rounded border-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">
                                            <input type="checkbox" name="students[${defaultStudentId}][pickup]" value="1" class="mr-1">
                                            Đăng ký đưa đón
                                        </label>
                                    </div>
                                </div>
                            `;
                            $container.append(groupHtml);
                        }
                    }
                }
            }

            if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
                $(studentSelect).select2('destroy');
            }
            $(studentSelect).select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn học viên",
                allowClear: true,
                multiple: true
            }).val(defaultStudents).trigger('change');
        }).catch(error => console.error('Error:', error));
    }

    function fetchAndUpdateVehicles(startTime, endTime) {
        if (!startTime || !endTime) return;
        const params = new URLSearchParams({ start_time: startTime, end_time: endTime });
        fetch(`/vehicles-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const vehicleSelect = document.getElementById('vehicle_select');
                vehicleSelect.innerHTML = '<option value="">Chọn xe</option>';
                if (data.vehicles && data.vehicles.length > 0) {
                    data.vehicles.forEach(vehicle => {
                        const option = document.createElement('option');
                        option.value = vehicle.id;
                        option.textContent = `${vehicle.license_plate} - ${vehicle.model}`;
                        vehicleSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có xe phù hợp';
                    vehicleSelect.appendChild(option);
                }
                if ($.fn.select2 && $('#vehicle_select').hasClass('select2-hidden-accessible')) {
                    $('#vehicle_select').select2('destroy');
                }
                $('#vehicle_select').select2({
                    dropdownParent: $('#addActivitieModal')
                });
            })
            .catch(error => console.error('Error fetching vehicles:', error));
    }

    function fetchAndUpdateUser(dateOrStartTime, timeOrEndTime, type) {
        if (!dateOrStartTime || !timeOrEndTime) return;
        const params = new URLSearchParams({
            type: type
        });
        if (type === 'exam') {
            params.append('date', dateOrStartTime);
            params.append('time', timeOrEndTime);
        } else {
            params.append('start_time', dateOrStartTime);
            params.append('end_time', timeOrEndTime);
        }

        fetch(`/users-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const teacherSelect = document.getElementById(type === 'study' ? 'learn_teacher_id' : 'exam_teacher_id');
                if (teacherSelect) {
                    teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
                    (data.teachers || []).forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        teacherSelect.appendChild(option);
                    });
                    $(`#${type === 'study' ? 'learn_teacher_id' : 'exam_teacher_id'}`).select2({
                        dropdownParent: $('#addActivitieModal'),
                        placeholder: "Chọn giáo viên",
                        allowClear: true,
                        width: '100%'
                    });
                }
            })
            .catch(error => console.error('Error fetching users:', error));
    }

    function fetchExamSchedules(courseId, examFieldIds, dateOrStartTime, timeOrEndTime) {
        const params = new URLSearchParams({
            course_id: courseId,
            date: dateOrStartTime,
            time: timeOrEndTime
        });
        if (Array.isArray(examFieldIds)) {
            examFieldIds.forEach(id => params.append('exam_field_ids[]', id));
        } else {
            params.append('exam_field_ids[]', examFieldIds);
        }

        fetch(`/exam-schedules-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const examScheduleSelect = document.getElementById('exam_schedule_id');
                examScheduleSelect.innerHTML = '<option value="">-- Chọn sân thi --</option>';

                data.exam_schedules.forEach(schedule => {
                    const option = document.createElement('option');
                    const location = schedule.stadium?.location || 'Không rõ địa điểm';
                    option.value = schedule.id;
                    option.textContent = `${location} (${schedule.start_time} → ${schedule.end_time})`;
                    examScheduleSelect.appendChild(option);
                });
                $('#exam_schedule_id').select2({
                    dropdownParent: $('#addActivitieModal')
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function handleTimeChange() {
        const selectedType = typeInput.value;
        if (selectedType === 'exam') {
            const date = dateInput.value;
            const time = timeInput.value;
            const courseId = $('#exam_course_id').val();
            const examCourseTypeChange = examCourseTypeInput.value;
            if (examCourseTypeChange) {
                fetchAndUpdate(courseId, 'exam', date, time, examCourseTypeChange);
            }
            if (!date || !time) return;
            fetchAndUpdate(courseId, 'exam', date, time, examCourseTypeChange);
            fetchAndUpdateUser(date, time, 'exam');
        } else if (selectedType === 'study') {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            const courseId = $('#learn_course_id').val();
            const addActivitieModalLabel = document.getElementById('addActivitieModalLabel').innerText;
            const studyTypeChange = addActivitieModalLabel == 'Thêm Lịch Học Thực Hành' ? 1 : (addActivitieModalLabel == 'Thêm Lịch Học Lý Thuyết' ? 0 : null);
            if (studyTypeChange) {
                fetchAndUpdate(courseId, 'study', date, time, studyTypeChange);
            }
            if (!startTime || !endTime) return;
            fetchAndUpdateVehicles(startTime, endTime);
            fetchAndUpdateUser(startTime, endTime, 'study');
            fetchAndUpdate(courseId, 'study', startTime, endTime, studyTypeChange);
        }
    }

    function handleTimeChangeSchedules() {
        const selectedType = typeInput.value;
        if (selectedType === 'exam') {
            const date = dateInput.value;
            const time = timeInput.value;
            if (!date || !time) return;
            const courseId = $('#exam_course_id').val();
            const examFieldIds = $('#exam_id').val();
            if (examFieldIds && courseId) {
                fetchExamSchedules(courseId, examFieldIds, date, time);
            }
        }
    }

    if (dateInput) dateInput.addEventListener('change', handleTimeChange);
    if (timeInput) timeInput.addEventListener('change', handleTimeChange);
    startTimeInput.addEventListener('change', handleTimeChange);
    endTimeInput.addEventListener('change', handleTimeChange);
    examCourseSelect.addEventListener('change', handleTimeChange);
    $('#exam_id').on('select2:select select2:unselect', handleTimeChangeSchedules);
});