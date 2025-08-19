// document.addEventListener('DOMContentLoaded', function () {
//     const modal = document.getElementById('addStudentToCourse');
//     if (!modal) return;
//     modal.addEventListener('show.bs.modal', function (event) {
//         const button = event.relatedTarget;
//         const studentId = button.getAttribute('data-student-id');
//         const studentName = button.getAttribute('data-student-name');
//         const tuitionFeeInput = modal.querySelector('input[name="tuition_fee"]');
//         const studentSelect = modal.querySelector('select[name="student_id"]');
//         const noCode = document.getElementById('no_code');
//         const vehicleCar = document.getElementById('vehicle_car');
//         const rankingWrapper = modal.querySelector('#ranking_wrapper');
//         const rankingSelect = modal.querySelector('#ranking_id');

//         function resetInputsIn(element) {
//             if (!element) return;
//             element.querySelectorAll('input, select').forEach(el => {
//                 if (el.tagName === 'SELECT') {
//                     $(el).val(null).trigger('change');
//                 } else {
//                     el.value = '';
//                 }
//             });
//         }

//         if (studentSelect && studentId && studentName) {
//             studentSelect.innerHTML = '';
//             const option = document.createElement('option');
//             option.value = studentId;
//             option.textContent = studentName;
//             studentSelect.appendChild(option);

//             studentSelect.addEventListener('mousedown', function (e) {
//                 e.preventDefault();
//                 this.blur();
//             });
//         }
          
//         $('#course_id_modal').select2({
//             dropdownParent: $('#addStudentToCourse'),
//             placeholder: 'Vui lòng chọn',
//             width: '100%'
//         }).on('change', function () {
//             const selectedCourse = $(this).find(':selected');
//             const selectedId = parseInt($(this).val());
//             const vehicleType = parseInt(selectedCourse.data('vehicle-type') || 0);
//             if (selectedId === 99999999) {
//                 noCode.classList.remove('d-none');
//                 rankingWrapper.classList.remove('d-none');
//                 vehicleCar.classList.add('d-none');
//                 resetInputsIn(vehicleCar);
//             } else {
//                 $(rankingSelect).val(null).trigger('change');
//                 if (vehicleType === 1) {
//                     vehicleCar.classList.remove('d-none');
//                     noCode.classList.remove('d-none');
//                     rankingWrapper.classList.add('d-none');
//                 } else {
//                     noCode.classList.remove('d-none');
//                     vehicleCar.classList.add('d-none');
//                     rankingWrapper.classList.add('d-none');
//                     resetInputsIn(noCode);
//                     resetInputsIn(vehicleCar);
//                 }
//             }
//             const fee = $(this).find(':selected').data('tuition-fee') || '';
//             tuitionFeeInput.value = formatNumber(fee);
//         });

//         $('#ranking_id').select2({
//             dropdownParent: $('#addStudentToCourse'),
//             placeholder: 'Chọn ranking',
//             width: '100%'
//         }).on('change', function () {
//             const vehicleType = parseInt($(this).find(':selected').data('vehicle-type') || 0);
//             const fee = $(this).find(':selected').data('fee-ranking') || '';
//             tuitionFeeInput.value = formatNumber(fee);
            
//             if (vehicleType === 1) {
//                 vehicleCar.classList.remove('d-none');
//             } else {
//                 vehicleCar.classList.add('d-none');
//                 resetInputsIn(vehicleCar);
//             }
//         });

//         $('#stadium_id_modal').select2({
//             dropdownParent: $('#addStudentToCourse'),
//             placeholder: 'Chọn sân học',
//             width: '100%'
//         });

//         $('#learn_teacher_id_modal').select2({
//             dropdownParent: $('#addStudentToCourse'),
//             placeholder: 'Chọn giáo viên',
//             width: '100%'
//         });

//         $('#sale_id_modal').select2({
//             dropdownParent: $('#addStudentToCourse'),
//             placeholder: 'Chọn sale',
//             width: '100%'
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addStudentToCourse');
    if (!modal) return;

    function initSelect2(selector, placeholder) {
        if ($.fn.select2) {
            const $el = $(selector);
            const currentVal = $el.val();
            if ($el.data('select2')) {
                $el.select2('destroy');
            }
            $el.select2({
                dropdownParent: $('#addStudentToCourse .modal-content'),
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
            if (currentVal) {
                $el.val(currentVal).trigger('change');
                console.log(currentVal);
                
            }
        }
    }

    modal.addEventListener('shown.bs.modal', function (event) {
        const button = event.relatedTarget;
        const studentId = button.getAttribute('data-student-id');
        const studentName = button.getAttribute('data-student-name');
        const tuitionFeeInput = modal.querySelector('input[name="tuition_fee"]');
        const studentSelect = modal.querySelector('select[name="student_id"]');
        const noCode = document.getElementById('no_code');
        const vehicleCar = document.getElementById('vehicle_car');
        const rankingWrapper = modal.querySelector('#ranking_wrapper');
        const rankingSelect = modal.querySelector('#ranking_id');

        function resetInputsIn(element) {
            if (!element) return;
            element.querySelectorAll('input, select').forEach(el => {
                if (el.tagName === 'SELECT') {
                    $(el).val(null).trigger('change');
                } else {
                    el.value = '';
                }
            });
        }

        if (studentSelect && studentId && studentName) {
            studentSelect.innerHTML = '';
            const option = document.createElement('option');
            option.value = studentId;
            option.textContent = studentName;
            studentSelect.appendChild(option);

            studentSelect.addEventListener('mousedown', function (e) {
                e.preventDefault();
                this.blur();
            });
        }

        // init course select2
        initSelect2('#course_id_modal', 'Chọn khoá học');
        $('#course_id_modal').off('change').on('change', function () {
            const selectedCourse = $(this).find(':selected');
            const selectedId = parseInt($(this).val());
            const vehicleType = parseInt(selectedCourse.data('vehicle-type') || 0);

            if (selectedId === 99999999) {
                noCode.classList.remove('d-none');
                rankingWrapper.classList.remove('d-none');
                vehicleCar.classList.add('d-none');
                resetInputsIn(vehicleCar);
            } else {
                $(rankingSelect).val(null).trigger('change');
                if (vehicleType === 1) {
                    vehicleCar.classList.remove('d-none');
                    noCode.classList.remove('d-none');
                    rankingWrapper.classList.add('d-none');
                } else {
                    noCode.classList.remove('d-none');
                    vehicleCar.classList.add('d-none');
                    rankingWrapper.classList.add('d-none');
                    resetInputsIn(noCode);
                    resetInputsIn(vehicleCar);
                }
            }

            const fee = selectedCourse.data('tuition-fee') || '';
            tuitionFeeInput.value = formatNumber(fee);
        });

        // init ranking select2
        initSelect2('#ranking_id', 'Chọn ranking');
        $('#ranking_id').off('change').on('change', function () {
            const vehicleType = parseInt($(this).find(':selected').data('vehicle-type') || 0);
            const fee = $(this).find(':selected').data('fee-ranking') || '';
            tuitionFeeInput.value = formatNumber(fee);

            if (vehicleType === 1) {
                vehicleCar.classList.remove('d-none');
            } else {
                vehicleCar.classList.add('d-none');
                resetInputsIn(vehicleCar);
            }
        });

        // init các select2 khác
        initSelect2('#stadium_id_modal', 'Chọn sân học');
        initSelect2('#learn_teacher_id_modal', 'Chọn giáo viên');
        initSelect2('#sale_id_modal', 'Chọn nhân viên sale');
    });
});
