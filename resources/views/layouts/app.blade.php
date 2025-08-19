<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang quản lý')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        a {
            text-decoration: none;
        }
        .menu-title {
            font-size: 18px;
            font-weight: bold;
            color: white;
            padding-left: 15px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .menu-title {
            display: flex;
            align-items: center;
        }

        /* Hiển thị icon ngay cả khi sidebar bị thu gọn */
        .sidebar.collapsed .menu-title i,
        .sidebar.collapsed .nav-link i {
            display: inline-block; /* Đảm bảo icon luôn hiển thị */
            margin-right: 10px; /* Khoảng cách giữa icon và văn bản */
        }

        /* Ẩn văn bản khi sidebar bị thu gọn */
        .sidebar.collapsed .menu-title span,
        .sidebar.collapsed .nav-link span {
            display: none;
        }

        /* Hiển thị văn bản khi sidebar mở rộng */
        .sidebar .menu-title span,
        .sidebar .nav-link span {
            display: inline;
        }

        /* Các hiệu ứng hover cho các mục menu */
        .nav-link:hover {
            background-color: rgb(233, 106, 106);
            border-radius: 5px;
        }

        .nav-link i {
            font-size: 18px; /* Điều chỉnh kích thước icon */
        }

        .sidebar.collapsed {
            width: 80px !important;
        }

        /* Điều chỉnh padding cho các mục menu */
        .sidebar .nav-link {
            padding-left: 10px;
        }

        /* Điều chỉnh kích thước icon trong các mục menu */
        .sidebar .nav-link i {
            width: 25px;
        }

        .back-to-top {
        position: fixed;
        bottom: 20%;
        right: 1%;
        display: none;
        z-index: 99;
        width: 50px;
        height: 50px;
        padding: 0;
        font-size: 24px;
        text-align: center;
        line-height: 50px;
    }
    </style>
</head>
<body>
    <button type="button" class="btn btn-primary btn-lg rounded-circle back-to-top" id="btn-back-to-top">
        <i class="fas fa-long-arrow-alt-up"></i>
    </button>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Quản lý trung tâm lái xe</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cài đặt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div x-data="{ sidebarOpen: true }" class="d-flex">
        <!-- Sidebar Menu -->
        <div :class="{ 'sidebar': true, 'collapsed': !sidebarOpen }" class="bg-dark text-white" style="min-height: 100vh; transition: all 0.3s ease;">
            <div class="text-end mb-3">
                <div class="d-flex justify-content-between">
                    <h1 class="menu-title text-white"><span>MENU</span></h1>
                    <button class="btn btn-sm btn-outline-light me-3" @click="sidebarOpen = !sidebarOpen">
                        <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'" title="MENU"></i>
                    </button>
                </div>
            </div>
            <ul class="nav flex-column">
                <div class="menu-title"><i class="fas fa-users" title="QUẢN LÝ NHÂN VIÊN"></i> <span>QUẢN LÝ NHÂN VIÊN</span></div>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog" title="Quản lý admin"></i> <span>Quản lý admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('sale.index') }}">
                        <i class="fas fa-user-tie" title="Quản lý sale"></i> <span>Quản lý sale</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('instructor.index') }}">
                        <i class="fas fa-chalkboard-teacher" title="Quản lý giáo viên"></i> <span>Quản lý giáo viên</span>
                    </a>
                </li>
                <div class="menu-title"><i class="fas fa-user-graduate" title="QUẢN LÝ HỌC VIÊN"></i> <span>QUẢN LÝ HỌC VIÊN</span></div>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('students.index') }}">
                        <i class="fas fa-user-graduate me-2" title="Danh sách học viên"></i><span>Danh sách học viên</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('courses.index') }}">
                        <i class="fa-solid fa-graduation-cap me-2" title="Danh sách khóa học"></i> <span>Danh sách khóa học</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('leads.index') }}">
                        <i class="fas fa-user-plus me-2" title="Danh sách khách hàng tiềm năng"></i> <span>Danh sách khách hàng tiềm năng</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('leadSource.index') }}">
                        <i class="fas fa-filter me-2" title="Quản lý nguồn"></i> <span>Quản lý nguồn</span>
                    </a>
                </li>
                <div class="menu-title">QUẢN LÝ LỊCH</div>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#menu3" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menu3">
                        <i class="fa-solid fa-calendar-check" title="Quản lý sát hạch"></i> <span>Quản lý sát hạch</span>
                    </a>
                    <div class="collapse" id="menu3">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('calendars.index') }}">Lịch thi sát hạch</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('exam-schedules.index') }}">Kế hoạch thi các trường</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('calendars.index') }}">
                        <i class="fa-solid fa-calendar me-2" title="Danh sách lịch"></i> <span>Danh sách lịch</span>
                    </a>
                </li>
                <div class="menu-title">QUẢN LÝ CHUNG</div>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('fees.index') }}">
                        <i class="fa-solid fa-money-check-dollar" title="Quản lý học phí"></i> <span>Danh sách học phí</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#menu6" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menu6">
                        <i class="fa-solid fa-car" title="Xe tập lái"></i><span>Xe tập lái</span>
                    </a>
                    <div class="collapse" id="menu6">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('vehicles.index') }}">Quản lý xe</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('vehicle-expenses.index') }}">Chi phí xe</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('stadiums.index') }}">
                        <i class="fa-solid fa-building" title="Sân thi"></i><span>Sân thi</span>
                    </a>
                </li>
                <div class="menu-title">
                    <i class="fas fa-book me-2" title="QUẢN LÝ TÀI LIỆU"></i> <span>QUẢN LÝ TÀI LIỆU</span>
                </div>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#menu13" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menu13">
                        <i class="fa-solid fa-person-chalkboard me-2" title="Quản lý phân môn"></i><span>Quản lý phân môn</span>
                    </a>
                    <div class="collapse" id="menu13">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('learning_fields.index') }}">Quản lý môn học</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('exam_fields.index') }}">Quản lý môn thi</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('rankings.index') }}">
                        <i class="fa-regular fa-id-card" title="Quản lý hạng bằng"></i> <span>Quản lý hạng bằng</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#menu19" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menu19">
                        <i class="fa-solid fa-book" title="Quản lý giáo trình"></i><span>Quản lý giáo trình</span>
                    </a>
                    <div class="collapse" id="menu19">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('curriculums.index') }}">
                                    <i class="fa-solid fa-book" title="Quản lý giáo trình"></i><span>Quản lý giáo trình</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('lessons.index') }}">
                                    <i class="fa-solid fa-book-bookmark" title="Danh sách bài học"></i><span>Danh sách bài học</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('exam_sets.index') }}">
                                    <i class="fa-solid fa-receipt" title="Danh sách bộ đề"></i><span>Danh sách bộ đề</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('traffic-signs.index') }}">
                        <i class="fa-solid fa-traffic-light" title="Quản lý biển báo"></i> <span>Quản lý biển báo</span>
                    </a>
                </li>
                <div class="menu-title">QUẢN LÝ PHÂN QUYỀN</div>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#menu5" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menu5">
                        <i class="fa-solid fa-anchor-circle-check me-2" title="Quản lý phân quyền"></i> <span>Quản lý phân quyền</span>
                    </a>
                    <div class="collapse" id="menu5">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('roles.index') }}">Danh sách vai trò</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('roles.create1') }}">Thêm mới vai trò</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        {{-- <div style="flex-grow: 1; max-width: none; overflow-x: auto; padding: 15px;">
            <x-breadcrumb />
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorMessage">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div> --}}
        <div style="flex-grow: 1; max-width: none; overflow-x: auto; padding: 15px;">
            <x-breadcrumb />
        
            {{-- Toast container (vẫn nằm trong main content) --}}
            <div aria-live="polite" aria-atomic="true" class="position-relative">
                <div class="toast-container position-fixed top-0 end-0 p-3">
        
                    @if(session('success'))
                        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
                            <div class="d-flex">
                                <div class="toast-body">
                                    {{ session('success') }}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
        
                    @if(session('error'))
                        <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
                            <div class="d-flex">
                                <div class="toast-body">
                                    {{ session('error') }}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
        
                </div>
            </div>
        
            @yield('content')
            @yield('job')
        </div>
        
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Trung tâm đào tạo lái xe. All rights reserved.</p>
    </footer>
        
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            // Ẩn thông báo sau 10 giây (10000ms)
            setTimeout(function() {
                $('#successMessage').fadeOut('slow');
                $('#errorMessage').fadeOut('slow');
            }, 5000);
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Reset all fields when modal is closed
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function () {
                    const form = modal.querySelector('form');
                    if (form) {
                        form.reset();
                        // Clear errors
                        form.querySelectorAll('.text-danger').forEach(el => el.innerHTML = '');
                    }
                });
            });
    
            // 2. Hiển thị lại modal nếu có lỗi validate từ backend
            @if(session('open_modal'))
                const errorModalId = "{{ session('open_modal') }}";
                const errorModalEl = document.getElementById(errorModalId);
                if (errorModalEl) {
                    const errorModal = new bootstrap.Modal(errorModalEl);
                    errorModal.show();
    
                    // Scroll đến lỗi đầu tiên nếu có
                    const firstError = errorModalEl.querySelector('.text-danger');
                    if (firstError) {
                        const input = firstError.closest('.form-group, .form-control');
                        if (input) {
                            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                }
            @endif
    
            // 3. Chặn đóng modal nếu submit bị lỗi 422 hoặc 429
            const forms = document.querySelectorAll('form');
    
            forms.forEach(form => {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('[type="submit"]');
                    const formData = new FormData(form);
    
                    try {
                        const response = await fetch(form.action, {
                            method: form.method,
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });
    
                        if (response.ok) {
                            location.reload(); // hoặc redirect nếu thành công
                        } else if (response.status === 422 || response.status === 429) {
                            const data = await response.json();
                            const errors = data.errors;
    
                            // Clear previous errors
                            form.querySelectorAll('.text-danger').forEach(el => el.innerHTML = '');
    
                            // Show errors
                            Object.keys(errors).forEach(field => {
                                const errorContainer = form.querySelector(`[name="${field}"]`)?.closest('.form-group')?.querySelector('.text-danger');
                                if (errorContainer) {
                                    errorContainer.innerHTML = errors[field][0];
                                }
                            });
    
                            // Scroll to first error
                            const firstError = form.querySelector('.text-danger');
                            if (firstError && firstError.innerText.trim() !== '') {
                                const input = firstError.closest('.form-group, .form-control');
                                if (input) {
                                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }
                        }
                    } catch (err) {
                        alert('Đã xảy ra lỗi mạng. Vui lòng thử lại.');
                    }
                });
            });
        });
    </script>
    
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="date"]').forEach(function (realInput) {
                const parent = realInput.closest('.position-relative');
    
                // Tạo input giả
                const fakeInput = document.createElement('input');
                fakeInput.type = 'text';
                fakeInput.placeholder = 'dd/mm/yyyy';
                fakeInput.className = realInput.className + ' fake-date';
    
                // Ẩn input thật
                realInput.style.position = 'absolute';
                realInput.style.top = '50px';
                realInput.style.width = '0';
                realInput.style.height = '0';
                realInput.style.opacity = '0';
                realInput.style.pointerEvents = 'none';
    
                // Thêm fakeInput vào DOM
                realInput.parentNode.insertBefore(fakeInput, realInput.nextSibling);
    
                // Gán giá trị từ realInput nếu có
                if (realInput.value) {
                    const [year, month, day] = realInput.value.split('-');
                    fakeInput.value = `${day}/${month}/${year}`;
                }
    
                // Ngăn nhập ký tự không phải số hoặc "/"
                fakeInput.addEventListener('keydown', function (e) {
                    const allowedKeys = [
                        'Backspace', 'ArrowLeft', 'ArrowRight', 'Tab',
                        'Delete', '/', 'Home', 'End'
                    ];
                    if (allowedKeys.includes(e.key)) return;
                    if (e.key >= '0' && e.key <= '9') return;
                    e.preventDefault();
                });
    
                // Xử lý nhập và tự động chèn /
                fakeInput.addEventListener('input', function () {
                    let raw = this.value.replace(/[^\d]/g, ''); // chỉ lấy số
    
                    if (raw.length === 0) {
                        this.value = '';
                        realInput.value = '';
                        realInput.dispatchEvent(new Event('change'));
                        return;
                    }
    
                    // Chèn dấu `/` theo định dạng dd/mm/yyyy
                    if (raw.length <= 2) {
                        this.value = raw;
                    } else if (raw.length <= 4) {
                        this.value = raw.slice(0, 2) + '/' + raw.slice(2);
                    } else {
                        this.value = raw.slice(0, 2) + '/' + raw.slice(2, 4) + '/' + raw.slice(4, 8);
                    }
    
                    // Nếu nhập đủ dd/mm/yyyy thì sync về realInput
                    const match = this.value.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
                    if (match) {
                        let [_, day, month, year] = match;
    
                        // Giới hạn ngày và tháng
                        day = Math.min(+day, 31).toString().padStart(2, '0');
                        month = Math.min(+month, 12).toString().padStart(2, '0');
    
                        this.value = `${day}/${month}/${year}`;
                        realInput.value = `${year}-${month}-${day}`;
                        realInput.dispatchEvent(new Event('change'));
                    }
                });
    
                // Khi chọn ngày từ picker → cập nhật input giả
                realInput.addEventListener('change', function () {
                    const [year, month, day] = this.value.split('-');
                    if (year && month && day) {
                        fakeInput.value = `${day}/${month}/${year}`;
                    }
                });
    
                // Nút 📅 gọi date picker
                const dateBtn = parent.querySelector('.datepicker-button');
                if (dateBtn) {
                    dateBtn.addEventListener('click', function () {
                        if (realInput.showPicker) realInput.showPicker();
                    });
                }
            });
        });
    </script> --}}
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Flatpickr core -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<!-- Flatpickr Vietnamese locale -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/vn.js"></script>

    
<script>
    // ✅ Gán tiếng Việt toàn cục
    flatpickr.localize(flatpickr.l10ns.vn);

    $(document).ready(function () {
        $('.real-date').each(function () {
            const input = this;

            const fp = flatpickr(input, {
                dateFormat: "d/m/Y",
                allowInput: true,
                clickOpens: true,
                altInput: false,

                onChange: function (selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        const formattedDate = formatDate(selectedDates[0]);
                        $(input).val(formattedDate);
                    }
                },
                onValueUpdate: function (selectedDates, dateStr) {
                    $(input).val(dateStr || '');
                }
            });

            let isComposing = false;
            input.addEventListener('compositionstart', () => isComposing = true);
            input.addEventListener('compositionend', () => {
                isComposing = false;
                formatInputText(input, fp);
            });
            input.addEventListener('input', function () {
                if (!isComposing) formatInputText(input, fp);
            });
        });

        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function formatInputText(input, fp) {
            let raw = input.value.replace(/[^\d]/g, '').slice(0, 8);
            let day = raw.slice(0, 2);
            let month = raw.slice(2, 4);
            let year = raw.slice(4, 8);

            if (day.length === 2) day = Math.min(+day, 31).toString().padStart(2, '0');
            if (month.length === 2) month = Math.min(+month, 12).toString().padStart(2, '0');

            let formatted = '';
            if (day) formatted += day;
            if (month) formatted += '/' + month;
            if (year) formatted += '/' + year;

            input.value = formatted;

            if (day.length === 2 && month.length === 2 && year.length === 4) {
                const final = `${day}/${month}/${year}`;
                fp.setDate(final, true, "d/m/Y");
            }
        }
    });
</script>
        
      
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successToastEl = $('#successToast');
            const errorToastEl = $('#errorToast');
    
            if (successToastEl) {
                const successToast = new bootstrap.Toast(successToastEl, { delay: 5000 });
                successToast.show();
            }
    
            if (errorToastEl) {
                const errorToast = new bootstrap.Toast(errorToastEl, { delay: 5000 });
                errorToast.show();
            }
        });
    </script>
    

    <script>
        const btnBackToTop = document.getElementById("btn-back-to-top");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) {
                btnBackToTop.style.display = "block";
            } else {
                btnBackToTop.style.display = "none";
            }
        });

        btnBackToTop.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>

    @yield('js')
</body>
</html>
