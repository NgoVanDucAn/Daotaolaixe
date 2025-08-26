// document.addEventListener("DOMContentLoaded", function () {
//     const startTimeInput = document.getElementById('date_start');
//     const endTimeInput = document.getElementById('date_end');
//     const dateInput = document.getElementById('date');
//     const timeInput = document.getElementById('time');
//     const examCourseSelect = document.getElementById('exam_course_id');
//     const learnCourseSelect = document.getElementById('learn_course_id');
//     const examSelect = document.getElementById('exam_id');
//     const dateStartField = document.getElementById('date-start-field');
//     const dateEndField = document.getElementById('date-end-field');
//     const examDateTimeField = document.getElementById('exam-date-time');
//     const typeInput = document.getElementById('type');
//     const examCourseTypeInput = document.getElementById('exam_course_type');

//     // Reset modal khi đóng
//     $('#addActivitieModal').on('hidden.bs.modal', function () {
//         var modalForm = $(this).find('form');
//         modalForm[0].reset();
//         $('#learn_course_id').val('').trigger('change');
//         $('#exam_course_id').val('').trigger('change');
//         $('#vehicle_select').val('').trigger('change');
//         $('#learn_student_id_select').val('').trigger('change');
//         $('#exam_student_id_select').val('').trigger('change');
//         $('#learn_teacher_id').val('').trigger('change');
//         $('#exam_teacher_id').val('').trigger('change');
//         $('#learn_student_id_select').empty();
//         $('#exam_student_id_select').empty();
//         $('#learn_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
//         $('#exam_course_id').empty().append(new Option('-- Chọn khóa học --', ''));
//         $('#learn_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
//         $('#exam_teacher_id').empty().append(new Option('Chọn giáo viên', ''));
//         $('#student-inputs').empty();
//         $('#alert-message').addClass('d-none');
//         typeInput.value = '';
//         if (examCourseTypeInput) examCourseTypeInput.value = '';
//     });

//     // Khởi tạo modal khi mở
//     $('#addActivitieModal').on('show.bs.modal', function (event) {
//         var modal = $(this);
//         var button = $(event.relatedTarget);
//         var calendarType = button.data('calendar-type');
//         var examCourseType = button.data('exam-course-type');
//         examCourseTypeInput.value = examCourseType;
//         // Cập nhật tiêu đề modal
        
//         var modalTitle = 'Thêm Mới Lịch';
//         switch (calendarType) {
//             case 'study_practice':
//                 modalTitle = 'Thêm Lịch Học Thực Hành';
//                 const itemShow = document.querySelectorAll('.form-calendar-learn-instructor');
//                 itemShow.forEach(e => {
//                     e.style.display = 'block';
//                 });
                
//                 break;
//             case 'study_theory':
//                 modalTitle = 'Thêm Lịch Học Lý Thuyết';
//                 const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
//                 itemHidden.forEach(e => {
//                     e.style.display = 'none';
//                 });
//                 break;
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
//             default:
//                 modalTitle = 'Thêm Mới Lịch';
//                 break;
//         }
//         $('#addActivitieModalLabel').text(modalTitle);

//         // Cập nhật type và giao diện form
//         if (calendarType === 'study_practice' || calendarType === 'study_theory') {
//             typeInput.value = 'study';
//             updateForm('study');
//             fetchCourses(learnCourseSelect);
//         } else {
//             typeInput.value = 'exam';
//             if (examCourseTypeInput) {
//                 examCourseTypeInput.value = examCourseType || '1';
//             }
//             updateForm('exam');
//             fetchCourses(examCourseSelect);
//         }
//     });

//     // Hàm cập nhật giao diện form
//     function updateForm(selectedType) {
//         document.querySelectorAll('#study-fields, #exam-fields').forEach(function(field) {
//             field.style.display = 'none';
//         });

//         startTimeInput.value = '';
//         endTimeInput.value = '';
//         if (dateInput) dateInput.value = '';
//         if (timeInput) timeInput.value = '';

//         if (selectedType === 'exam') {
//             dateStartField.style.display = 'none';
//             dateEndField.style.display = 'none';
//             examDateTimeField.style.display = 'flex';
//             startTimeInput.disabled = true;
//             endTimeInput.disabled = true;
//             if (dateInput) dateInput.disabled = false;
//             if (timeInput) timeInput.disabled = false;
//             document.getElementById('exam-fields').style.display = 'block';

//             $('#exam_course_id').select2({
//                 dropdownParent: $('#addActivitieModal'),
//                 placeholder: "Chọn khóa học",
//                 allowClear: true,
//                 width: '100%'
//             }).on('change', function () {
//                 const date = dateInput ? dateInput.value : '';
//                 const time = timeInput ? timeInput.value : '';
//                 const courseId = $(this).val();
//                 const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
//                 fetchAndUpdate(courseId, 'exam', date, time, examCourseType);
//             });

//             $('#exam_student_id_select').select2({
//                 dropdownParent: $('#addActivitieModal'),
//                 placeholder: "Chọn học viên",
//                 allowClear: true,
//                 multiple: true,
//                 width: '100%'
//             }).on('change', function () {
//                 const selectedOptions = $(this).val() || [];
//                 const $container = $('#student-inputs');
//                 $container.find('.student-input-group').each(function () {
//                     const studentId = $(this).data('student-id');
//                     if (!selectedOptions.includes(studentId.toString())) {
//                         $(this).remove();
//                     }
//                 });

//                 selectedOptions.forEach(function (studentId) {
//                     if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
//                         const studentName = $(`#exam_student_id_select option[value="${studentId}"]`).text();
//                         const groupHtml = `
//                             <div class="student-input-group border p-4 rounded bg-gray-50" data-student-id="${studentId}">
//                                 <div class="mb-2 font-semibold">${studentName}</div>
//                                 <div class="mb-2">
//                                     <label class="block text-sm mb-1">Số báo danh:</label>
//                                     <input style="width: 100%;" type="text" name="students[${studentId}][exam_number]" class="w-full rounded border-gray-300">
//                                 </div>
//                                 <div>
//                                     <label class="block text-sm mb-1">
//                                         <input type="checkbox" name="students[${studentId}][pickup]" value="1" class="mr-1">
//                                         Đăng ký đưa đón
//                                     </label>
//                                 </div>
//                             </div>
//                         `;
//                         $container.append(groupHtml);
//                     }
//                 });
//             });
//         } else {
//             dateStartField.style.display = 'block';
//             dateEndField.style.display = 'block';
//             examDateTimeField.style.display = 'none';
//             startTimeInput.disabled = false;
//             endTimeInput.disabled = false;
//             if (dateInput) dateInput.disabled = true;
//             if (timeInput) timeInput.disabled = true;
//             document.getElementById('study-fields').style.display = 'block';

//             $('#learn_course_id').select2({
//                 dropdownParent: $('#addActivitieModal'),
//                 placeholder: "Chọn khóa học",
//                 allowClear: true,
//                 width: '100%'
//             }).on('change', function () {
//                 const startTime = startTimeInput.value;
//                 const endTime = endTimeInput.value;
//                 const courseId = $(this).val();
//                 const studyType = $('#addActivitieModalLabel').text() === 'Thêm Lịch Học Thực Hành' ? 1 : 0;
//                 fetchAndUpdate(courseId, 'study', startTime, endTime, studyType);
//             });

//             $('#learn_student_id_select').select2({
//                 dropdownParent: $('#addActivitieModal'),
//                 placeholder: "Chọn học viên",
//                 allowClear: true,
//                 multiple: true,
//                 width: '100%'
//             });
//         }
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

//     // Hàm lấy dữ liệu môn học/môn thi, học viên
//     function fetchAndUpdate(courseId, type, dateStart, dateEnd, holdType) {
//         if (!courseId) {
//             const subjectContainer = document.getElementById('learning_id');
//             subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng chọn khóa học trước --</p>';
    
//             const studentSelect = document.getElementById('learn_student_id_select');
//             studentSelect.innerHTML = '';
//             $(studentSelect).select2({
//                 dropdownParent: $('#addActivitieModal'),
//                 placeholder: "Chọn học viên",
//                 allowClear: true,
//                 multiple: true,
//                 width: '100%'
//             });
//             return;
//         }
    
//         let url = `/course-data/${type}/${courseId}`;
        
//         url += `?date_start=${encodeURIComponent(dateStart)}&date_end=${encodeURIComponent(dateEnd)}&learnType=${encodeURIComponent(holdType)}`;
    
//         fetch(url)
//             .then(response => response.json())
//             .then(data => {
//                 const subjectContainer = document.getElementById('learning_id');
//                 subjectContainer.innerHTML = '';
    
//                 const subjects = data.course.learning_fields;
    
//                 const rowWrapper = document.createElement('div');
//                 rowWrapper.classList.add('row');
    
//                 subjects.forEach(subject => {
//                     const col = document.createElement('div');
//                     col.classList.add('form-check', 'col-md-2', 'mb-2');
//                     col.style.margin = '0 .63rem';
//                     col.innerHTML = `
//                         <input type="radio" name="learning_id" value="${subject.id}" class="form-check-input" id="learning_${subject.id}">
//                         <label for="learning_${subject.id}" class="form-check-label">${subject.name}</label>
//                     `;
//                     rowWrapper.appendChild(col);
//                 });
    
//                 subjectContainer.appendChild(rowWrapper);
    
//                 // Danh sách học viên
//                 const studentSelect = document.getElementById(type === 'study' ? 'learn_student_id_select' : 'exam_student_id_select');
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
//                     placeholder: "Chọn học viên",
//                     allowClear: true,
//                     multiple: true
//                 });
//             })
//             .catch(error => console.error('Error:', error));
//     }
    
    

//     // Hàm lấy danh sách xe
//     function fetchAndUpdateVehicles(startTime, endTime) {
//         if (!startTime || !endTime) return;
//         const params = new URLSearchParams({ start_time: startTime, end_time: endTime });
//         fetch(`/vehicles-available?${params.toString()}`)
//             .then(response => response.json())
//             .then(data => {
//                 const vehicleSelect = document.getElementById('vehicle_select');
//                 vehicleSelect.innerHTML = '<option value="">Chọn xe</option>';
//                 if (data.vehicles && data.vehicles.length > 0) {
//                     data.vehicles.forEach(vehicle => {
//                         const option = document.createElement('option');
//                         option.value = vehicle.id;
//                         option.textContent = `${vehicle.license_plate} - ${vehicle.model}`;
//                         vehicleSelect.appendChild(option);
//                     });
//                 } else {
//                     const option = document.createElement('option');
//                     option.value = '';
//                     option.textContent = 'Không có xe phù hợp';
//                     vehicleSelect.appendChild(option);
//                 }
//                 if ($.fn.select2 && $('#vehicle_select').hasClass('select2-hidden-accessible')) {
//                     $('#vehicle_select').select2('destroy');
//                 }
//                 $('#vehicle_select').select2({
//                     dropdownParent: $('#addActivitieModal')
//                 });
//             })
//             .catch(error => console.error('Error fetching vehicles:', error));
//     }

//     // Hàm lấy danh sách giáo viên
//     function fetchAndUpdateUser(dateOrStartTime, timeOrEndTime, type) {
//         if (!dateOrStartTime || !timeOrEndTime) return;
//         const params = new URLSearchParams({
//             type: type
//         });
//         if (type === 'exam') {
//             params.append('date', dateOrStartTime);
//             params.append('time', timeOrEndTime);
//         } else {
//             params.append('start_time', dateOrStartTime);
//             params.append('end_time', timeOrEndTime);
//         }

//         fetch(`/users-available?${params.toString()}`)
//             .then(response => response.json())
//             .then(data => {
//                 const teacherSelect = document.getElementById(type === 'study' ? 'learn_teacher_id' : 'exam_teacher_id');
//                 if (teacherSelect) {
//                     teacherSelect.innerHTML = '<option value="">Chọn giáo viên</option>';
//                     (data.teachers || []).forEach(user => {
//                         const option = document.createElement('option');
//                         option.value = user.id;
//                         option.textContent = user.name;
//                         teacherSelect.appendChild(option);
//                     });
//                     $(`#${type === 'study' ? 'learn_teacher_id' : 'exam_teacher_id'}`).select2({
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
//     function fetchExamSchedules(courseId, examFieldIds, dateOrStartTime, timeOrEndTime) {
//         if (!courseId || !examFieldIds) return;
//         const params = new URLSearchParams({
//             course_id: courseId,
//             date: dateOrStartTime,
//             time: timeOrEndTime
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
//     function formatToDateTimeLocal(dateTimeStr) {
//         // Tách ngày và giờ
//         const [datePart, timePart] = dateTimeStr.split(' '); // ["16/07/2025", "12:00"]
//         const [day, month, year] = datePart.split('/');       // ["16", "07", "2025"]
//         return `${year}-${month}-${day}T${timePart}`;
//     }


//     function handleTimeChange() {
//         const selectedType = typeInput.value;
//         if (selectedType === 'exam') {
//             const date = dateInput ? dateInput.value : '';
//             const time = timeInput ? timeInput.value : '';
//             const courseId = $('#exam_course_id').val();
//             const examCourseType = examCourseTypeInput ? examCourseTypeInput.value : '';
//             if (date && time) {
//                 fetchAndUpdate(courseId, 'exam', date, time, examCourseType);
//                 fetchAndUpdateUser(date, time, 'exam');
//             }
//         } else if (selectedType === 'study') {
//             const startTime = startTimeInput.value;
//             const endTime = endTimeInput.value;
//             const courseId = $('#learn_course_id').val();
//             const studyType = $('#addActivitieModalLabel').text() === 'Thêm Lịch Học Thực Hành' ? 1 : 0;
//             const formatStartTime = formatToDateTimeLocal(startTime)
//             const formatEndTime = formatToDateTimeLocal(endTime)
            
//             if (startTime && endTime) {
//                 fetchAndUpdate(courseId, 'study', startTime, endTime, studyType);
//                 fetchAndUpdateVehicles(formatStartTime, formatEndTime);
//                 fetchAndUpdateUser(startTime, endTime, 'study');
//             }
//         }
//     }

//     // Hàm xử lý thay đổi thời gian và môn thi để lấy sân thi
//     function handleTimeChangeSchedules() {
//         const selectedType = typeInput.value;
//         if (selectedType === 'exam') {
//             const date = dateInput ? dateInput.value : '';
//             const time = timeInput ? timeInput.value : '';
//             const courseId = $('#exam_course_id').val();
//             const examFieldIds = $('#exam_id').val();
//             if (date && time && courseId && examFieldIds) {
//                 fetchExamSchedules(courseId, examFieldIds, date, time);
//             }
//         }
//     }

//     // Gắn sự kiện thay đổi
//     if (dateInput) dateInput.addEventListener('change', handleTimeChange);
//     if (timeInput) timeInput.addEventListener('change', handleTimeChange);
//     startTimeInput.addEventListener('change', handleTimeChange);
//     endTimeInput.addEventListener('change', handleTimeChange);
//     learnCourseSelect.addEventListener('change', handleTimeChange);
//     examCourseSelect.addEventListener('change', handleTimeChange);
//     examCourseTypeInput.addEventListener('change', handleTimeChange);
//     $('#exam_id').on('select2:select select2:unselect', handleTimeChangeSchedules);
// });

document.addEventListener("DOMContentLoaded", function () {
    const startTimeInput = document.getElementById('date_start');
    const endTimeInput = document.getElementById('date_end');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const vehicleTypeSelect = document.getElementById('vehicle_type_learn')
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
        var calendarType = button.data('calendar-type');
        var examCourseType = button.data('exam-course-type');
        examCourseTypeInput.value = examCourseType;
        $('#status_id').prop('disabled', true);
        $('#vehicle_type_exam').prop('disabled', true);
        // Cập nhật tiêu đề modal
        
        var modalTitle = 'Thêm Mới Lịch';
        switch (calendarType) {
            case 'study_practice':
                modalTitle = 'Thêm Lịch Học Thực Hành';
                const itemShow = document.querySelectorAll('.form-calendar-learn-instructor');
                itemShow.forEach(e => {
                    e.style.display = 'block';
                });
                
                break;
            case 'study_theory':
                modalTitle = 'Thêm Lịch Học Lý Thuyết';
                const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
                itemHidden.forEach(e => {
                    e.style.display = 'none';
                });
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

        // Cập nhật type và giao diện form
        typeInput.value = 'study';
        updateForm('study', calendarType);
    });

    // Hàm cập nhật giao diện form
    function updateForm(selectedType, calendarType) {
        document.querySelectorAll('#study-fields, #exam-fields').forEach(function(field) {
            field.style.display = 'none';
        });

        startTimeInput.value = '';
        endTimeInput.value = '';
        if (dateInput) dateInput.value = '';
        if (timeInput) timeInput.value = '';

        dateStartField.style.display = 'block';
        dateEndField.style.display = 'block';
        examDateTimeField.style.display = 'none';
        startTimeInput.disabled = false;
        endTimeInput.disabled = false;
        if (dateInput) dateInput.disabled = true;
        if (timeInput) timeInput.disabled = true;
        document.getElementById('study-fields').style.display = 'block';

        $('#learn_student_id_select').select2({
            dropdownParent: $('#addActivitieModal'),
            placeholder: "Chọn học viên",
            allowClear: true,
            multiple: true,
            width: '100%'
          }).on('change', function () {
            const selectedOptions = $(this).val() || [];
            const $container = $('.learn-container');

            $container.find('.student-input-group').each(function () {
              const sid = String($(this).data('student-id'));
              if (!selectedOptions.includes(sid)) {
                $(this).remove();
              }
            });
          
            selectedOptions.forEach(function (studentId) {
              studentId = String(studentId);

                
              if ($container.find(`.student-input-group[data-student-id="${studentId}"]`).length === 0) {
                const groupHtml = `
                  <div class="student-input-group border p-4 rounded bg-gray-50 mt-3" data-student-id="${studentId}">
                    <div class="row">
                      <div class="col-md-6">
                        <label for="km_${studentId}">Km</label>
                        <input type="number" id="km_${studentId}" class="form-control" min="0" name="km[${studentId}]" value="">
                      </div>
                      <div class="col-md-6">
                        <label for="hour_${studentId}">Số giờ chạy được</label>
                        <input type="text" id="hour_${studentId}" placeholder="HH:mm" class="form-control time-input" name="hour[${studentId}]" value="">
                      </div>
                    </div>
          
                    <div class="row mt-4">
                      <div class="col-md-6 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="learn_auto_${studentId}" name="learn_auto[${studentId}]">
                        <label class="ms-2 mb-0" for="learn_auto_${studentId}">Học tự động</label>
                      </div>
          
                      <div class="col-md-6 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="learn_night_${studentId}" name="learn_night[${studentId}]">
                        <label class="ms-2 mb-0" for="learn_night_${studentId}">Học ban đêm</label>
                      </div>
                    </div>
                  </div>
                `;
                $container.append(groupHtml);
                flatpickr(`#hour_${studentId}`, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
              }
            });
          });

        $('#vehicle_type_learn').select2({
            dropdownParent: $('#addActivitieModal'),
            placeholder: "Chọn loại hình thức học",
            allowClear: true,
            width: '100%'
        }).on('change', function () {
            const vehicleType = $(this).val();
            fetchAndUpdate(vehicleType, 'study', calendarType);
        });
    }

    // Hàm lấy dữ liệu môn học/môn thi, học viên
    function fetchAndUpdate(vehicleType, type, calendarType) {
        if (!vehicleType) {
            const subjectContainer = document.getElementById('learning_id');
            subjectContainer.innerHTML = '<p class="text-muted">-- Vui lòng hình thức học trước --</p>';
    
            const studentSelect = document.getElementById('learn_student_id_select');
            studentSelect.innerHTML = '';
            $(studentSelect).select2({
                dropdownParent: $('#addActivitieModal'),
                placeholder: "Chọn học viên",
                allowClear: true,
                multiple: true,
                width: '100%'
            });
            return;
        }
        const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
        if(vehicleType == 1){
            itemHidden.forEach(e => {
                e.style.display = 'none';
            });
        } else {
            itemHidden.forEach(e => {
                e.style.display = 'block';
            });
        }
        let url = `/calendars/infor?vehicle_type=${vehicleType}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const subjectContainer = document.getElementById('learning_id');
                subjectContainer.innerHTML = '';
    
                let subjects = data.learning_fields;
                console.log(calendarType);
                
                if (calendarType === 'study_theory' || 
                    (calendarType === 'study_practice' && vehicleType === '1')) {
                    $('.vehicle_select_container').hide();
                    $('#vehicle_select').prop('disabled', true);
                } else {
                    $('.vehicle_select_container').show();
                    $('#vehicle_select').prop('disabled', false);
                }
                
                if (calendarType == 'study_theory' || calendarType == 'study_practice') {
                    if (vehicleType == 1) {
                        $('.learning_id').hide();
                        
                        subjects = [];
                    } else if (vehicleType == 2) {
                        $('.learning_id').show();
                        // Lọc môn học dựa theo loại kỳ thi
                        const isPractice = calendarType == 'study_practice';
                        subjects = subjects.filter(subject => subject.is_practical == (isPractice ? 1 : 0));
                    }
                }
                
                // Nếu còn môn thi
                // if (subjects.length > 0) {
                //     const selectAllDiv = document.createElement('div');
                //     selectAllDiv.classList.add('mb-2');
                //     selectAllDiv.innerHTML = `
                //         <label>
                //             <input type="checkbox" id="select_all_exams" /> Chọn tất cả
                //         </label>
                //     `;
                //     subjectContainer.appendChild(selectAllDiv);
                // }
    
                const rowWrapper = document.createElement('div');
                rowWrapper.classList.add('row');
                
                subjects.forEach(subject => {
                    const col = document.createElement('div');
                    col.classList.add('form-check', 'col-md-2', 'mb-2');
                    col.style.margin = '0 .63rem';
                    col.innerHTML = `
                        <input type="radio" name="learning_id" value="${subject.id}" class="form-check-input" id="learning_${subject.id}">
                        <label for="learning_${subject.id}" class="form-check-label">${subject.name}</label>
                    `;
                    rowWrapper.appendChild(col);
                });
    
                subjectContainer.appendChild(rowWrapper);
    
                // Danh sách học viên
                const studentSelect = document.getElementById('learn_student_id_select');
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
    
    // Hàm lấy danh sách xe
    function fetchAndUpdateVehicles(startTime, endTime) {
        if (!startTime || !endTime) return;
        if (new Date(endTime) < new Date(startTime)) {
            const vehicleSelect = document.getElementById('vehicle_select');
            vehicleSelect.innerHTML = '<option value="">Thời gian kết thúc không hợp lệ</option>';
        }
        
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

    // Hàm lấy danh sách giáo viên
    function fetchAndUpdateUser(dateOrStartTime, timeOrEndTime, type) {
        if (!dateOrStartTime || !timeOrEndTime) return;
        const params = new URLSearchParams({
            type: type
        });
        params.append('start_time', dateOrStartTime);
        params.append('end_time', timeOrEndTime);

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

    // Hàm xử lý thay đổi thời gian
    function formatToDateTimeLocal(dateTimeStr) {
        // Tách ngày và giờ
        const [datePart, timePart] = dateTimeStr.split(' '); // ["16/07/2025", "12:00"]
        const [day, month, year] = datePart.split('/');       // ["16", "07", "2025"]
        return `${year}-${month}-${day}T${timePart}`;
    }


    function handleTimeChange() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const vehicleType = $('#vehicle_type_learn').val();
        const formatStartTime = formatToDateTimeLocal(startTime)
        const formatEndTime = formatToDateTimeLocal(endTime)
        const studentInputGroup = document.querySelector('.student-input-group');
        if (studentInputGroup) {
            studentInputGroup.style.display = 'none';
        }

        const itemHidden = document.querySelectorAll('.form-calendar-learn-instructor');
        if(vehicleType == 1){
            itemHidden.forEach(e => {
                e.style.display = 'none';
            });
        } else {
            itemHidden.forEach(e => {
                e.style.display = 'block';
            });
        }
    
        fetchAndUpdate(vehicleType, 'study');
        fetchAndUpdateVehicles(formatStartTime, formatEndTime);
        fetchAndUpdateUser(startTime, endTime, 'study');
    }

    // Gắn sự kiện thay đổi
    if (dateInput) dateInput.addEventListener('change', handleTimeChange);
    if (timeInput) timeInput.addEventListener('change', handleTimeChange);
    startTimeInput.addEventListener('change', handleTimeChange);
    vehicleTypeSelect.addEventListener('change', handleTimeChange);
    endTimeInput.addEventListener('change', handleTimeChange);
});