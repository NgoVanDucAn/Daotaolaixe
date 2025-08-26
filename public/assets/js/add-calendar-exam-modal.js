// document.addEventListener("DOMContentLoaded", function () {
//     const dateInput = document.getElementById('date');
//     const timeInput = document.getElementById('time');
//     const examCourseSelect = document.getElementById('exam_course_id');
//     const dateStartField = document.getElementById('date-start-field');
//     const dateEndField = document.getElementById('date-end-field');
//     const examDateTimeField = document.getElementById('exam-date-time');
//     const typeInput = document.getElementById('type');
//     const examCourseTypeInput = document.getElementById('exam_course_type');

//     // Reset modal khi đóng (chỉ phần liên quan đến thi)
//     $('#addActivitieModal').on('hidden.bs.modal', function () {
//         var modalForm = $(this).find('form');
//         modalForm[0].reset();
//         $('#exam_course_id').val('').trigger('change');
//         $('#exam_student_id_select').val('').trigger('change');
//         $('#exam_teacher_id').val('').trigger('change');
//         $('#exam_student_id_select').empty();
//         $('#exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
//         $('#exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
//         $('#student-inputs').empty();
//         $('#alert-message').addClass('d-none');
//         typeInput.value = '';
//         if (examCourseTypeInput) examCourseTypeInput.value = '';
//     });

//     // Khởi tạo modal khi mở (chỉ phần liên quan đến thi)
//     $('#addActivitieModal').on('show.bs.modal', function (event) {
//         var modal = $(this);
//         var button = $(event.relatedTarget);
//         var calendarType = button.data('calendar-type');
//         var examCourseType = button.data('exam-course-type');
//         if (examCourseTypeInput) examCourseTypeInput.value = examCourseType;

//         // Cập nhật tiêu đề modal
//         var modalTitle = 'Thêm Mới Lịch';
//         switch (calendarType) {
//             case 'exam_practice':
//                 modalTitle = 'Thêm Lịch Thi Thực Hành';
//                 break;
//             case 'exam_theory':
//                 modalTitle = 'Thêm Lịch Thi Lý Thuyết';
//                 break;
//             case 'exam_graduation':
//                 modalTitle = 'Thêm Lịch Thi Tốt Nghiệp';
//                 break;
//             case 'exam_certification':
//                 modalTitle = 'Thêm Lịch Thi Sát Hạch';
//                 break;
//         }
//         if (calendarType.includes('exam')) {
            
//             $('#addActivitieModalLabel').text(modalTitle);
//             typeInput.value = 'exam';
//             if (examCourseTypeInput) {
//                 examCourseTypeInput.value = examCourseType || '1';
//             }
//             updateForm('exam');
//             fetchCourses(examCourseSelect);
//         }
//     });

//     // Hàm cập nhật giao diện form cho thi
//     function updateForm(selectedType) {
//         if (selectedType !== 'exam') return;

//         document.querySelectorAll('#study-fields, #exam-fields').forEach(function(field) {
//             field.style.display = 'none';
//         });

//         dateStartField.style.display = 'none';
//         dateEndField.style.display = 'none';
//         examDateTimeField.style.display = 'flex';
//         if (dateInput) dateInput.disabled = false;
//         if (timeInput) timeInput.disabled = false;
//         document.getElementById('exam-fields').style.display = 'block';

//         $('#exam_course_id').select2({
//             dropdownParent: $('#addActivitieModal'),
//             placeholder: "Chọn khóa học",
//             allowClear: true,
//             width: '100%'
//         }).on('change', function () {
//             const date = dateInput ? dateInput.value : '';
//             const time = timeInput ? timeInput.value : '';
//             const courseId = $(this).val();
//             const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
//             fetchAndUpdate(courseId, 'exam', date, time, examCourseType);
//         });

//         $('#exam_student_id_select').select2({
//             dropdownParent: $('#addActivitieModal'),
//             placeholder: "Chọn học viên khóa học",
//             allowClear: true,
//             multiple: true,
//             width: '100%'
//         }).on('change', function () {
//             const selectedOptions = $(this).val() || [];
//             const $container = $('#student-inputs');
//             $container.find('.student-input-group').each(function () {
//                 const studentId = $(this).data('student-id');
//                 if (!selectedOptions.includes(studentId.toString())) {
//                     $(this).remove();
//                 }
//             });

//             selectedOptions.forEach(function (studentId) {
//                 if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
//                     const studentName = $(`#exam_student_id_select option[value="${studentId}"]`).text();
//                     const groupHtml = `
//                         <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
//                             <div class="mb-2 font-semibold">${studentName}</div>
//                             <div class="mb-2">
//                                 <label class="block text-sm mb-1">Số báo danh:</label>
//                                 <input style="width: 100%;" type="text" name="students[${studentId}][exam_number]" class="w-full rounded border-gray-300">
//                             </div>
//                             <div>
//                                 <label class="block text-sm mb-1">
//                                     <input type="checkbox" name="students[${studentId}][pickup]" value="1" class="mr-1">
//                                     Đăng ký đưa đón
//                                 </label>
//                             </div>
//                         </div>
//                     `;
//                     $container.append(groupHtml);
//                 }
//             });
//         });
//     }

//     // Hàm lấy danh sách khóa học
//     function fetchCourses(selectElement) {
//         fetch('/courses-alls')
//             .then(response => response.json())
//             .then(data => {
//                 selectElement.innerHTML = '<option value="">Chọn khóa học</option>';
//                 data.forEach(course => {
//                     const option = document.createElement('option');
//                     option.value = course.id;
//                     option.textContent = course.code;
//                     selectElement.appendChild(option);
//                 });
//                 $(selectElement).select2({
//                     dropdownParent: $('#addActivitieModal'),
//                     placeholder: "Chọn khóa học",
//                     allowClear: true,
//                     width: '100%'
//                 });
//             })
//             .catch(error => console.error('Error fetching courses:', error));
//     }

//     // Hàm lấy dữ liệu môn thi và học viên
//     function fetchAndUpdate(courseId, type, dateStart, dateEnd, holdType) {
//         if (type !== 'exam') return;
//         if (!courseId) {
//             const subjectContainer = document.getElementById('exam_id');
//             subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng chọn khóa học trước --</p>';
//             const studentSelect = document.getElementById('exam_student_id_select');
//             studentSelect.innerHTML = '';
//             $(studentSelect).select2({
//                 dropdownParent: $('#addActivitieModal'),
//                 placeholder: "Chọn học viên khóa học",
//                 allowClear: true,
//                 multiple: true,
//                 width: '100%'
//             });
//             return;
//         }

//         let url = `/course-data/exam/${courseId}?date=${encodeURIComponent(dateStart)}&time=${encodeURIComponent(dateEnd)}&examType=${encodeURIComponent(holdType)}`;
//         fetch(url)
//             .then(response => response.json())
//             .then(data => {
//                 const subjectContainer = document.getElementById('exam_id');
//                 subjectContainer.innerHTML = '';
//                 const subjects = data.course.exam_fields || [];
//                 if (subjects.length > 0) {
//                     const selectAllDiv = document.createElement('div');
//                     selectAllDiv.classList.add('mb-2');
//                     selectAllDiv.innerHTML = `
//                         <label>
//                             <input type="checkbox" id="select_all_exams" /> Chọn tất cả
//                         </label>
//                     `;
//                     subjectContainer.appendChild(selectAllDiv);
//                 }

//                 const rowWrapper = document.createElement('div');
//                 rowWrapper.classList.add('row');
//                 subjects.forEach(subject => {
//                     const col = document.createElement('div');
//                     col.classList.add('form-check', 'col-md-2', 'mb-2');
//                     col.style.margin = '0 .63rem';
//                     col.innerHTML = `
//                         <input type="checkbox" name="exam_id[]" value="${subject.id}" class="form-check-input exam-checkbox" id="exam_${subject.id}">
//                         <label for="exam_${subject.id}" class="form-check-label">${subject.name}</label>
//                     `;
//                     rowWrapper.appendChild(col);
//                 });
//                 subjectContainer.appendChild(rowWrapper);

//                 const selectAll = document.getElementById('select_all_exams');
//                 if (selectAll) {
//                     selectAll.addEventListener('change', function () {
//                         document.querySelectorAll('.exam-checkbox').forEach(cb => {
//                             cb.checked = this.checked;
//                         });
//                     });
//                 }

//                 const studentSelect = document.getElementById('exam_student_id_select');
//                 studentSelect.innerHTML = '';
//                 const availableStudents = data.available_students || [];
//                 availableStudents.forEach(student => {
//                     const option = document.createElement('option');
//                     option.value = student.id;
//                     option.textContent = `${student.name} - ${student.student_code}`;
//                     studentSelect.appendChild(option);
//                 });

//                 if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
//                     $(studentSelect).select2('destroy');
//                 }
//                 $(studentSelect).select2({
//                     dropdownParent: $('#addActivitieModal'),
//                     placeholder: "Chọn học viên khóa học",
//                     allowClear: true,
//                     multiple: true
//                 });
//             })
//             .catch(error => console.error('Error:', error));
//     }

//     // Hàm lấy danh sách giáo viên
//     function fetchAndUpdateUser(date, time) {
//         if (!date || !time) return;
//         const params = new URLSearchParams({
//             type: 'exam',
//             date: date,
//             time: time
//         });

//         fetch(`/users-available?${params.toString()}`)
//             .then(response => response.json())
//             .then(data => {
//                 const teacherSelect = document.getElementById('exam_teacher_id');
//                 if (teacherSelect) {
//                     teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
//                     (data.teachers || []).forEach(user => {
//                         const option = document.createElement('option');
//                         option.value = user.id;
//                         option.textContent = user.name;
//                         teacherSelect.appendChild(option);
//                     });
//                     $('#exam_teacher_id').select2({
//                         dropdownParent: $('#addActivitieModal'),
//                         placeholder: "Chọn giáo viên",
//                         allowClear: true,
//                         width: '100%'
//                     });
//                 }
//             })
//             .catch(error => console.error('Error fetching users:', error));
//     }

//     // Hàm lấy danh sách sân thi
//     function fetchExamSchedules(courseId, examFieldIds, date, time) {
//         if (!courseId || !examFieldIds) return;
//         const params = new URLSearchParams({
//             course_id: courseId,
//             date: date,
//             time: time
//         });
//         if (Array.isArray(examFieldIds)) {
//             examFieldIds.forEach(id => params.append('exam_field_ids[]', id));
//         } else {
//             params.append('exam_field_ids[]', examFieldIds);
//         }

//         fetch(`/exam-schedules-available?${params.toString()}`)
//             .then(response => response.json())
//             .then(data => {
//                 const examScheduleSelect = document.getElementById('exam_schedule_id');
//                 examScheduleSelect.innerHTML = '<option value="">-- Chọn sân thi --</option>';
//                 data.exam_schedules.forEach(schedule => {
//                     const option = document.createElement('option');
//                     const location = schedule.stadium?.location || 'Không rõ địa điểm';
//                     option.value = schedule.id;
//                     option.textContent = `${location} (${schedule.start_time} → ${schedule.end_time})`;
//                     examScheduleSelect.appendChild(option);
//                 });
//                 $('#exam_schedule_id').select2({
//                     dropdownParent: $('#addActivitieModal')
//                 });
//             })
//             .catch(error => console.error('Error:', error));
//     }

//     // Hàm xử lý thay đổi thời gian
//     function handleTimeChange() {
//         const date = dateInput ? dateInput.value : '';
//         const time = timeInput ? timeInput.value : '';
//         const courseId = $('#exam_course_id').val();
//         const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
//         if (date && time) {
//             fetchAndUpdate(courseId, 'exam', date, time, examCourseType);
//             fetchAndUpdateUser(date, time);
//         }
//     }

//     // Hàm xử lý thay đổi thời gian và môn thi để lấy sân thi
//     function handleTimeChangeSchedules() {
//         const date = dateInput ? dateInput.value : '';
//         const time = timeInput ? timeInput.value : '';
//         const courseId = $('#exam_course_id').val();
//         const examFieldIds = Array.from(document.querySelectorAll('.exam-checkbox:checked')).map(cb => cb.value);
//         if (date && time && courseId && examFieldIds) {
//             fetchExamSchedules(courseId, examFieldIds, date, time);
//         }
//     }

//     // Gắn sự kiện thay đổi
//     if (dateInput) dateInput.addEventListener('change', handleTimeChange);
//     if (timeInput) timeInput.addEventListener('change', handleTimeChange);
//     examCourseSelect.addEventListener('change', handleTimeChange);
//     examCourseTypeInput.addEventListener('change', handleTimeChange);
//     $(document).on('change', '.exam-checkbox', function () {
//         handleTimeChangeSchedules();
//     });
// });



document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const vehicleTypeSelect = document.getElementById('vehicle_type_exam')
    const dateStartField = document.getElementById('date-start-field');
    const dateEndField = document.getElementById('date-end-field');
    const examDateTimeField = document.getElementById('exam-date-time');
    const typeInput = document.getElementById('type');
    const examCourseTypeInput = document.getElementById('exam_course_type');

    // Reset modal khi đóng (chỉ phần liên quan đến thi)
    $('#addActivitieModal').on('hidden.bs.modal', function () {
        var modalForm = $(this).find('form');
        modalForm[0].reset();
        $('#vehicle_type').val('').trigger('change');
        $('#exam_student_id_select').val('').trigger('change');
        $('#exam_teacher_id').val('').trigger('change');
        $('#exam_student_id_select').empty();
        // $('#exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
        $('#exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
        $('#student-inputs').empty();
        $('#alert-message').addClass('d-none');
        typeInput.value = '';
        if (examCourseTypeInput) examCourseTypeInput.value = '';
    });
    let globalCalendarType = null;
    // Khởi tạo modal khi mở (chỉ phần liên quan đến thi)
    $('#addActivitieModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var calendarType = button.data('calendar-type');
        var examCourseType = button.data('exam-course-type');
        if (examCourseTypeInput) examCourseTypeInput.value = examCourseType;

        // Cập nhật tiêu đề modal
        var modalTitle = 'Thêm Mới Lịch';
        switch (calendarType) {
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
        }
        if (calendarType.includes('exam')) {
            
            $('#addActivitieModalLabel').text(modalTitle);
            typeInput.value = 'exam';
            if (examCourseTypeInput) {
                examCourseTypeInput.value = examCourseType || '1';
            }
            updateForm('exam', calendarType);
        }
    });

    // Hàm cập nhật giao diện form cho thi
    function updateForm(selectedType, calendarType) {
        if (selectedType !== 'exam') return;

        document.querySelectorAll('#study-fields, #exam-fields').forEach(function(field) {
            field.style.display = 'none';
        });

        dateStartField.style.display = 'none';
        dateEndField.style.display = 'none';
        examDateTimeField.style.display = 'flex';
        if (dateInput) dateInput.disabled = false;
        if (timeInput) timeInput.disabled = false;
        document.getElementById('exam-fields').style.display = 'block';

        $('#vehicle_type_exam').select2({
            dropdownParent: $('#addActivitieModal'),
            placeholder: "Chọn loại hình thức thi",
            allowClear: true,
            width: '100%'
        }).on('change', function () {
            const date = dateInput ? dateInput.value : '';
            const time = timeInput ? timeInput.value : '';
            const vehicleType = $(this).val();
            const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
        
            // 1. Xoá hết các input học viên đang hiển thị
            $('#student-inputs').empty().hide();
            // 2. Reset select học viên
            $('#exam_student_id_select').val(null).select2().trigger('change.select2'); // dùng change.select2 chỉ để update UI, không gọi change của bạn
            // 3. Gọi hàm fetch dữ liệu
            fetchAndUpdate(vehicleType, 'exam', date, time, examCourseType, calendarType);
        });
        
        $('#exam_student_id_select').select2({
            dropdownParent: $('#addActivitieModal'),
            placeholder: "Chọn học viên khóa học",
            allowClear: true,
            multiple: true,
            width: '100%'
        }).on('change', function () {
            const selectedOptions = $(this).val() || [];
            const $container = $('#student-inputs');
        
            // Nếu có chọn học viên thì mới show container
            if (selectedOptions.length > 0) {
                $container.show();
            } else {
                $container.hide();
            }
        
            // Xoá những học viên không còn được chọn
            $container.find('.student-input-group').each(function () {
                const studentId = $(this).data('student-id');
                if (!selectedOptions.includes(studentId.toString())) {
                    $(this).remove();
                }
            });
        
            // Thêm học viên mới được chọn
            selectedOptions.forEach(function (studentId) {
                if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                    const studentName = $(`#exam_student_id_select option[value="${studentId}"]`).text();
                    const groupHtml = `
                        <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
                            <div class="mb-2 font-semibold">${studentName}</div>
                            <div class="mb-2">
                                <label class="block text-sm mb-1">Số báo danh:</label>
                                <input style="width: 100%;" type="text" name="students[${studentId}][exam_number]" class="w-full rounded border-gray-300">
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
    }

    // Hàm lấy dữ liệu môn thi và học viên
    // function fetchAndUpdate(vehicleType, type) {
    //     console.log(vehicleType);
        
    //     if (type !== 'exam') return;
    //     if (!vehicleType) {
    //         const subjectContainer = document.getElementById('exam_id');
    //         subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng chọn loại hình học trước --</p>';
    //         const studentSelect = document.getElementById('exam_student_id_select');
    //         studentSelect.innerHTML = '';
    //         $(studentSelect).select2({
    //             dropdownParent: $('#addActivitieModal'),
    //             placeholder: "Chọn học viên khóa học",
    //             allowClear: true,
    //             multiple: true,
    //             width: '100%'
    //         });
    //         return;
    //     }

    //     let url = `/calendars/infor?vehicle_type=${vehicleType}`;
    //     fetch(url)
    //         .then(response => response.json())
    //         .then(data => {
    //             const subjectContainer = document.getElementById('exam_id');
    //             subjectContainer.innerHTML = '';
    //             const subjects = data.exam_fields || [];
    //             if (subjects.length > 0) {
    //                 const selectAllDiv = document.createElement('div');
    //                 selectAllDiv.classList.add('mb-2');
    //                 selectAllDiv.innerHTML = `
    //                     <label>
    //                         <input type="checkbox" id="select_all_exams" /> Chọn tất cả
    //                     </label>
    //                 `;
    //                 subjectContainer.appendChild(selectAllDiv);
    //             }

    //             const rowWrapper = document.createElement('div');
    //             rowWrapper.classList.add('row');
    //             subjects.forEach(subject => {
    //                 const col = document.createElement('div');
    //                 col.classList.add('form-check', 'col-md-2', 'mb-2');
    //                 col.style.margin = '0 .63rem';
    //                 col.innerHTML = `
    //                     <input type="checkbox" name="exam_id[]" value="${subject.id}" class="form-check-input exam-checkbox" id="exam_${subject.id}">
    //                     <label for="exam_${subject.id}" class="form-check-label">${subject.name}</label>
    //                 `;
    //                 rowWrapper.appendChild(col);
    //             });
    //             subjectContainer.appendChild(rowWrapper);

    //             const selectAll = document.getElementById('select_all_exams');
    //             if (selectAll) {
    //                 selectAll.addEventListener('change', function () {
    //                     document.querySelectorAll('.exam-checkbox').forEach(cb => {
    //                         cb.checked = this.checked;
    //                     });
    //                 });
    //             }

    //             const studentSelect = document.getElementById('exam_student_id_select');
    //             studentSelect.innerHTML = '';
    //             const availableStudents = data.students || [];
    //             availableStudents.forEach(student => {
    //                 const option = document.createElement('option');
    //                 option.value = student.id;
    //                 option.textContent = student.label;
    //                 studentSelect.appendChild(option);
    //             });

    //             if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
    //                 $(studentSelect).select2('destroy');
    //             }
    //             $(studentSelect).select2({
    //                 dropdownParent: $('#addActivitieModal'),
    //                 placeholder: "Chọn học viên khóa học",
    //                 allowClear: true,
    //                 multiple: true
    //             });
    //         })
    //         .catch(error => console.error('Error:', error));
    // }
    function fetchAndUpdate(vehicleType, type, date, time, examCourseType, calendarType) {
        if (type !== 'exam') return;
        console.log(calendarType);

        if(calendarType != 'study_theory' || calendarType != 'study_practice'){
            $('#vehicle_select').prop('disabled', true);
            $('#time_learn').prop('disabled', true);
            $('#date_start').prop('disabled', true);
            $('#date_end').prop('disabled', true);
            $('#learn_teacher_id').prop('disabled', true);

            $('#km').prop('disabled', true);
            $('#hour').prop('disabled', true);
            $('#select_stadium_id').prop('disabled', true);
        }
        
        const subjectContainer = document.getElementById('exam_id');
        const studentSelect = document.getElementById('exam_student_id_select');
    
        if (!vehicleType && vehicleType !== 0) {
            subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng chọn loại hình thức thi trước --</p>';
            studentSelect.innerHTML = '';
            $(studentSelect).select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn học viên khóa học",
                allowClear: true,
                multiple: true,
                width: '100%'
            });
            return;
        }
    
        let url = `/calendars/infor?vehicle_type=${vehicleType}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                subjectContainer.innerHTML = '';
                let subjects = data.exam_fields || [];
    
                // ✅ Lọc theo điều kiện calendarType = 'exam_theory'
                
                
                if (calendarType == 'exam_practice' || calendarType == 'exam_theory') {
                    if (vehicleType == 1) {
                        $('.exam_id').hide();
                        subjects = [];
                    } else if (vehicleType == 2) {
                        $('.exam_id').show();
                        // Lọc môn học dựa theo loại kỳ thi
                        const isPractice = calendarType == 'exam_practice';
                        subjects = subjects.filter(subject => subject.is_practical == (isPractice ? 1 : 0));
                    }
                }
                
                
                // Nếu còn môn thi
                if (subjects.length > 0) {
                    const selectAllDiv = document.createElement('div');
                    selectAllDiv.classList.add('mb-2');
                    selectAllDiv.innerHTML = `
                        <label>
                            <input type="checkbox" id="select_all_exams" /> Chọn tất cả
                        </label>
                    `;
                    subjectContainer.appendChild(selectAllDiv);
                }
    
                const rowWrapper = document.createElement('div');
                rowWrapper.classList.add('row');
                subjects.forEach(subject => {
                    const col = document.createElement('div');
                    col.classList.add('form-check', 'col-md-2', 'mb-2');
                    col.style.margin = '0 .63rem';
                    col.innerHTML = `
                        <input type="checkbox" name="exam_id[]" value="${subject.id}" class="form-check-input exam-checkbox" id="exam_${subject.id}">
                        <label for="exam_${subject.id}" class="form-check-label">${subject.name}</label>
                    `;
                    rowWrapper.appendChild(col);
                });
                subjectContainer.appendChild(rowWrapper);
    
                const selectAll = document.getElementById('select_all_exams');
                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        document.querySelectorAll('.exam-checkbox').forEach(cb => {
                            cb.checked = this.checked;
                        });
                    });
                }
    
                // Xử lý học viên
                studentSelect.innerHTML = '';
                const availableStudents = data.students || [];
                availableStudents.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.label;
                    studentSelect.appendChild(option);
                });
    
                if ($.fn.select2 && $(studentSelect).hasClass('select2-hidden-accessible')) {
                    $(studentSelect).select2('destroy');
                }
                $(studentSelect).select2({
                    dropdownParent: $('#addActivitieModal'),
                    placeholder: "Chọn học viên khóa học",
                    allowClear: true,
                    multiple: true
                });
            })
            .catch(error => console.error('Error:', error));
    }
    

    // Hàm lấy danh sách giáo viên
    function fetchAndUpdateUser(date, time) {
        if (!date || !time) return;
        const params = new URLSearchParams({
            type: 'exam',
            date: date,
            time: time
        });

        fetch(`/users-available?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const teacherSelect = document.getElementById('exam_teacher_id');
                if (teacherSelect) {
                    teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
                    (data.teachers || []).forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        teacherSelect.appendChild(option);
                    });
                    $('#exam_teacher_id').select2({
                        dropdownParent: $('#addActivitieModal'),
                        placeholder: "Chọn giáo viên",
                        allowClear: true,
                        width: '100%'
                    });
                }
            })
            .catch(error => console.error('Error fetching users:', error));
    }

    // Hàm xử lý thay đổi thời gian
    function handleTimeChange() {
        const date = dateInput ? dateInput.value : '';
        const time = timeInput ? timeInput.value : '';
        const vehicleType = $('#vehicle_type_exam').val();
        const studentInputGroup = document.querySelector('.student-input-group');
        if (studentInputGroup) {
            studentInputGroup.style.display = 'none';
        }
        // if (date && time) {
            fetchAndUpdate(vehicleType, 'exam');
            fetchAndUpdateUser(date, time);
        // }
    }

    // Gắn sự kiện thay đổi
    if (dateInput) dateInput.addEventListener('change', handleTimeChange);
    if (timeInput) timeInput.addEventListener('change', handleTimeChange);
    vehicleTypeSelect.addEventListener('change', handleTimeChange);
    examCourseTypeInput.addEventListener('change', handleTimeChange);
});