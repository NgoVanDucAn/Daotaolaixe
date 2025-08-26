<!DOCTYPE html>
<html lang="en">
<!-- <html lang="en" data-layout="topnav"> -->

<head>
    <meta charset="utf-8" />
    <title>{{ isset($page_title) ? $page_title : 'Trang quản trị' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    
    <style>
        .side-nav-title {
            margin-top: 20px;
            margin-left: -10px;
            font-size: 0.9rem !important;
            line-height: 0.1;
        }
        .back-to-top {
            position: fixed;
            bottom: 3%;
            right: 1%;
            display: none;
            z-index: 99;
            width: 40px;
            height: 40px;
            padding: 0;
            font-size: 18px;
            text-align: center;
            line-height: 40px;
        }
        table {
            border: 1px solid #8f8b8b !important;
            border-collapse: collapse;
        }
        th,
        td,
        tr {
            border: 1px solid #8f8b8b;
        }

        th,
        td {
            padding-left: 5px !important;
            padding-right: 5px !important;
            text-align: center;
        }

        td {
            font-weight: bold;
        }
        th {
            white-space: nowrap;
        }
    </style>
    @yield('css')
</head>

<body>
    <button type="button" class="btn btn-primary btn-lg rounded-circle back-to-top" id="btn-back-to-top">
        <i class="fas fa-long-arrow-alt-up"></i>
    </button>
    <!-- Begin page -->
    <div class="wrapper">

        
        <!-- Sidenav Menu Start -->
        <div class="sidenav-menu">

            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="logo" style="padding: 0">
                <span class="logo-light">
                    <span class="logo-lg">
                        <img style="height: 70px;" src="{{ asset('assets/images/logoPhucAn.png') }}" alt="logo">
                    </span>
                    <span class="logo-sm">
                        <img style="height: 48px;" src="{{ asset('assets/images/logoPhucAn.png') }}" alt="small logo">
                    </span>
                </span>
                
                <span class="logo-dark">
                    <span class="logo-lg">
                        <img style="height: 70px;" src="{{ asset('assets/images/logoPhucAn.png') }}" alt="logo">
                    </span>
                    <span class="logo-sm">
                        <img style="height: 48px;" src="{{ asset('assets/images/logoPhucAn.png') }}" alt="small logo">
                    </span>
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <button class="button-sm-hover">
                <i class="ti ti-circle align-middle"></i>
            </button>

            <!-- Full Sidebar Menu Close Button -->
            <button class="button-close-fullsidebar">
                <i class="ti ti-x align-middle"></i>
            </button>

            <div data-simplebar>

                <!--- Sidenav Menu -->
                <ul class="side-nav">
                    <li class="side-nav-title">Navigation</li>
                    <li class="side-nav-item">
                        <a href="{{ route('dashboard') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-view-dashboard text-success"></i></span>
                            <span class="menu-text"> Dashboard </span>
                            <span class="badge bg-success rounded-pill">5</span>
                        </a>
                    </li>
                    <li class="side-nav-title">QUẢN LÝ SALES</li>
                    <li class="side-nav-item">
                        <a href="{{ route('leads.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-star-box  text-primary"></i></span>
                            <span class="menu-text">Quản lý Leads</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('sale.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-account-group-outline  text-primary"></i></span>
                            <span class="menu-text">Danh sách sale</span>
                        </a>
                    </li>

                    <li class="side-nav-title">LỊCH THI SÁT HẠCH Ô TÔ</li>
                    {{-- <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarTestManagement" aria-expanded="false" aria-controls="sidebarTestManagement" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-clipboard-text"></i></span>
                            <span class="menu-text"> Quản lý sát hạch </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarTestManagement">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{{ route('calendars.index', ['type' => 'exam', 'level_filter' => 2]) }}" class="side-nav-link">
                                        <span class="menu-text">Lịch thi sát hạch</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('exam-schedules.index') }}" class="side-nav-link">
                                        <span class="menu-text">Kế hoạch thi các trường</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li> --}}
                    <li class="side-nav-item">
                        <a href="{{ route('calendars.exam-sh') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="mdi mdi-calendar-check text-warning"></i></span>
                            <span class="menu-text">Lịch thi sát hạch</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('calendars.exam-tn') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="mdi mdi-calendar-check text-warning"></i></span>
                            <span class="menu-text">Lịch thi tốt nghiệp</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('calendars.exam-hmlt') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="mdi mdi-calendar-check text-warning"></i></span>
                            <span class="menu-text">Lịch thi hết môn lý thuyết</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('calendars.exam-hmth') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="mdi mdi-calendar-check text-warning"></i></span>
                            <span class="menu-text">Lịch thi hết môn thực hành</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('exam-schedules.index') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="mdi mdi-calendar-text text-danger"></i></span>
                            <span class="menu-text">Kế hoạch thi các trường</span>
                        </a>
                    </li>

                    <li class="side-nav-title">QUẢN LÝ GIÁO VIÊN</li>
                    <li class="side-nav-item">
                        <a href="{{ route('instructor.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-lock-outline text-warning"></i></span>
                            <span class="menu-text">Danh sách giáo viên</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('calendars.study-th') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-calendar-alert-outline text-warning"></i></span>
                            <span class="menu-text">Lịch làm việc giáo viên</span>
                        </a>
                    </li>

                    <li class="side-nav-title">QUẢN LÝ HỌC VIÊN</li>
                    <li class="side-nav-item">
                        <a href="{{ route('courses.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-notebook text-warning"></i></span>
                            <span class="menu-text"> Danh sách khóa học </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('students.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-account-outline text-warning"></i></span>
                            <span class="menu-text"> Quản lý học viên </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('students.index-moto') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-account-details text-warning"></i></span>
                            <span class="menu-text"> Học viên học xe máy </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('students.index-car') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-account-details text-warning"></i></span>
                            <span class="menu-text"> Học viên học ô tô </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('calendars.study-lt') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="mdi mdi-calendar-check text-warning"></i></span>
                            <span class="menu-text">Lịch học lý thuyết - cabin</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('fees.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-cash-fast text-warning"></i></span>
                            <span class="menu-text">Lịch sử nộp tiền</span>
                        </a>
                    </li>

                    <li class="side-nav-title">QUẢN LÝ CHUNG</li>
                    <li class="side-nav-item">
                        <a href="{{ route('stadiums.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-road text-success"></i></span>
                            <span class="menu-text">Quản lý sân học và thi </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('leadSource.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-source-branch text-primary"></i></span>
                            <span class="menu-text"> Quản lý Lead Source</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('vehicles.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-car-back text-danger"></i></span>
                            <span class="menu-text">Quản lý xe</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('vehicle-expenses.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-cash-fast text-success"></i></span>
                            <span class="menu-text">Quản lý chi phí xe</span>
                        </a>
                    </li>

                    <li class="side-nav-title">QUẢN LÝ TÀI LIỆU</li>
                    <li class="side-nav-item">
                        <a href="{{ route('rankings.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-card-account-details text-warning"></i></span>
                            <span class="menu-text"> Quản lý hạng bằng </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('traffic-signs.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-traffic-light text-warning"></i></span>
                            <span class="menu-text"> Quản lý biển báo </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarSubject" aria-expanded="false" aria-controls="sidebarSubject" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-star-box-multiple-outline text-warning"></i></span>
                            <span class="menu-text"> Quản lý phân môn </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarSubject">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{{ route('learning_fields.index') }}" class="side-nav-link">Quản lý môn học</a>
                                </li>   
                                <li class="side-nav-item">
                                    <a href="{{ route('exam_fields.index') }}" class="side-nav-link">Quản lý môn thi</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarCuriculum" aria-expanded="false" aria-controls="sidebarCuriculum" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-star-box-multiple-outline text-warning"></i></span>
                            <span class="menu-text"> Quản lý giáo trình </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarCuriculum">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{{ route('lessons.index') }}" class="side-nav-link">Danh sách bài học</a>
                                </li>   
                                <li class="side-nav-item">
                                    <a href="{{ route('exam_sets.index') }}" class="side-nav-link">Danh sách bộ đề</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-title">BẢO MẬT</li>
                    <li class="side-nav-item">
                        <a href="{{ route('users.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-account-key-outline text-primary"></i></span>
                            <span class="menu-text">Người dùng</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('roles.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-account-group text-primary"></i></span>
                            <span class="menu-text">Quản lý Permission</span>
                        </a>
                    </li>
                    {{-- <li class="side-nav-item">
                        <a href="{{ route('calendars.index') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="mdi mdi-calendar-lock"></i></span>
                            <span class="menu-text"> Danh sách lịch học</span>
                        </a>
                    </li> --}}
                </ul>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- Sidenav Menu End -->

        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">

                    <!-- Brand Logo -->
                    <a href="/" class="logo">
                        <span class="logo-light">
                            <span class="logo-lg"><img src="{{ asset('assets/images/logo-light.png') }}" alt="logo"></span>
                            <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm-light.png') }}" alt="small logo"></span>
                        </span>
                        <span class="logo-dark">
                            <span class="logo-lg"><img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo"></span>
                            <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo"></span>
                        </span>
                    </a>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="sidenav-toggle-button px-2">
                        <i class="mdi mdi-menu font-24"></i>
                    </button>

                    <!-- Horizontal Menu Toggle Button -->
                    <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="mdi mdi-menu font-22"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Light/Dark Toggle Button  -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" id="light-dark-mode" type="button">
                            <i class="ti ti-moon font-22"></i>
                        </button>
                    </div>

                    <!-- Notification Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown position-relative">
                            <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,25" type="button" data-bs-auto-close="outside" aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-bell-outline font-22"></i>
                                <span class="noti-icon-badge"></span>
                            </button>

                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">
                                <div class="p-3 border-bottom bg-primary rounded-top-2">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 font-16 fw-medium text-white"> Notifications</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="position-relative z-2" style="max-height: 300px;" data-simplebar>
                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap active" id="notification-1">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-title bg-success rounded-circle">
                                                        <i class="mdi mdi-cog-outline font-20"></i>
                                                    </span>
                                                </div>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <p class="fw-medium mb-0 text-dark">New settings</p>
                                                <span class="font-12">There are new settings available</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-1">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-2">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-title bg-info rounded-circle">
                                                        <i class="mdi mdi-bell-outline font-20"></i>
                                                    </span>
                                                </div>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <p class="fw-medium mb-0 text-dark">Updates</p>
                                                <span class="font-12">There are 2 new updates available</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-1">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-3">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-title bg-danger rounded-circle">
                                                        <i class="mdi mdi-account-plus-outline font-20"></i>
                                                    </span>
                                                </div>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <p class="fw-medium mb-0 text-dark">New Users</p>
                                                <span class="font-12">You have 10 unread messages</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-1">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-4">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-title bg-primary rounded-circle">
                                                        <i class="mdi mdi-comment-account-outline font-20"></i>
                                                    </span>
                                                </div>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <p class="fw-medium mb-0 text-dark">Caleb Flakelar commented on Admin</p>
                                                <span class="font-12">4 days ago</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-1">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap mb-5" id="notification-5">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-title bg-info rounded-circle">
                                                        <i class="mdi mdi-bell-outline font-20"></i>
                                                    </span>
                                                </div>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <p class="fw-medium mb-0 text-dark">Updates</p>
                                                <span class="font-12">There are 2 new updates available</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-1">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>
                                </div>


                                <!-- All-->
                                <a href="javascript:void(0);" class="dropdown-item notification-item position-fixed z-2 bottom-0 text-center text-reset text-decoration-underline link-offset-2 fw-bold notify-item border-top border-light py-2">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Email Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown position-relative">
                            <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,25" type="button" data-bs-auto-close="outside" aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-email-outline font-22"></i>
                                <span class="noti-icon-badge"></span>
                            </button>

                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">
                                <div class="p-3 border-bottom bg-primary rounded-top-2">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 font-16 fw-medium text-white"> Notifications</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="position-relative z-2" style="max-height: 300px;" data-simplebar>
                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap active" id="notification-1">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}" class="avatar-md rounded-circle" alt="" />
                                                <span class="position-absolute rounded-pill bg-danger notification-badge">
                                                    <i class="mdi mdi-message-check-outline"></i>
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Glady Haid</span> commented on <span class="fw-medium text-body">Uplon admin status</span>
                                                <br />
                                                <span class="font-12">25m ago</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-1">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-2">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-4.jpg') }}" class="avatar-md rounded-circle" alt="" />
                                                <span class="position-absolute rounded-pill bg-info notification-badge">
                                                    <i class="mdi mdi-currency-usd"></i>
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Tommy Berry</span> donated <span class="text-success">$100.00</span> for <span class="fw-medium text-body">Carbon removal program</span>
                                                <br />
                                                <span class="font-12">58m ago</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-2">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-3">
                                        <span class="d-flex align-items-center">
                                            <div class="avatar-md flex-shrink-0 me-3">
                                                <span class="avatar-title bg-success-subtle text-success rounded-circle font-22">
                                                    <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <span class="flex-grow-1 text-muted">
                                                You withdraw a <span class="fw-medium text-body">$500</span> by <span class="fw-medium text-body">New York ATM</span>
                                                <br />
                                                <span class="font-12">2h ago</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-3">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>

                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-4">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-7.jpg') }}" class="avatar-md rounded-circle" alt="" />
                                                <span class="position-absolute rounded-pill bg-secondary notification-badge">
                                                    <i class="mdi mdi-plus"></i>
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Richard Allen</span> followed you in <span class="fw-medium text-body">Facebook</span>
                                                <br />
                                                <span class="font-12">3h ago</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-4">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>



                                    <!-- item-->
                                    <div class="dropdown-item notification-item py-2 text-wrap mb-5" id="notification-5">
                                        <span class="d-flex align-items-center">
                                            <span class="me-3 position-relative flex-shrink-0">
                                                <img src="{{ asset('assets/images/users/avatar-10.jpg') }}" class="avatar-md rounded-circle" alt="" />
                                                <span class="position-absolute rounded-pill bg-danger notification-badge">
                                                    <i class="mdi mdi-heart"></i>
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Victor Collier</span> liked you recent photo in <span class="fw-medium text-body">Instagram</span>
                                                <br />
                                                <span class="font-12">10h ago</span>
                                            </span>
                                            <span class="notification-item-close">
                                                <button type="button" class="btn btn-ghost-danger rounded-circle btn-sm btn-icon" data-dismissible="#notification-5">
                                                    <i class="mdi mdi-close font-16"></i>
                                                </button>
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <!-- All-->
                                <a href="javascript:void(0);" class="dropdown-item notification-item position-fixed z-2 bottom-0 text-center text-reset text-decoration-underline link-offset-2 fw-bold notify-item border-top border-light py-2">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>


                    <!-- User Dropdown -->
                    @if (Auth::check())
                        <div class="topbar-item nav-user">
                            <div class="dropdown">
                                <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" data-bs-offset="0,25" type="button" aria-haspopup="false" aria-expanded="false">
                                    @if (!empty(optional(Auth::user())->image))
                                        <img src="{{ asset('assets/images/users/' . Auth::user()->image) }}" width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image">
                                    @else
                                        @php
                                            $user = Auth::user();
                                            $name = $user->name ?? 'Khách';
                                            $nameParts = explode(' ', $name);
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                                        @endphp
                                        <div class="rounded-circle me-lg-2 d-flex bg-success text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <span class="d-lg-flex flex-column gap-1 d-none">
                                        <h6 class="my-0">{{ Auth::user()->name ?? 'Chưa đăng nhập' }}</h6>
                                    </span>
                                    <i class="mdi mdi-chevron-down d-none d-lg-block align-middle ms-2"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <div class="dropdown-header bg-primary mt-n3 rounded-top-2">
                                        <h6 class="text-overflow text-white m-0">Welcome !</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span>Profile</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <!-- item-->
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="dropdown-item notify-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout-variant"></i>
                                        <span>Đăng xuất</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Button Trigger Customizer Offcanvas -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" type="button">
                            <i class="mdi mdi-cog-outline font-22"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="page-content">

            <div class="page-container">

                {{-- @if (isset($breadcrumb) && isset($page_title))
                    <div class="page-title-box">             
                        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                            <div class="flex-grow-1">
                                <h4 class="font-18 mb-0">{{ $page_title }}</h4>
                            </div>
                            <div class="text-end">
                                <ol class="breadcrumb m-0 py-0">
                                    @foreach ($breadcrumb->getItems() as $item)
                                        @if ($item['url'])
                                            <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                        @else
                                            <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                @endif --}}

                @if (isset($breadcrumb) && isset($page_title))
                    <div class="page-title-box fs-5">             
                        <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2">
                            <div class="text-start">
                                <ol class="breadcrumb m-0 py-0">
                                    <li class="breadcrumb-item"><a href="/dashboard"><i class="mdi mdi-home-variant-outline fs-5 text-primary"></i></a></li>
                                    @foreach ($breadcrumb->getItems() as $index => $item)
                                        @if ($index > 0)
                                            <span class="breadcrumb-separator"> > </span>
                                        @endif
                                        @if ($item['url'])
                                            <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                        @else
                                            <li class="breadcrumb-item">{{ $item['title'] }}</li>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                            {{-- Button tùy chọn nếu có --}}
                            <div class="action-btn d-flex">
                                <div class="text-end me-2">
                                    @yield('page-action-back-button')
                                </div>
                                <div class="text-end">
                                    @yield('page-action-add-button')
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorMessage">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content') --}}
                
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

            </div> <!-- container -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="page-container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <script>document.write(new Date().getFullYear())</script> © AHV - By <a href="https://ahvtech.com/"><span class="fw-semibold text-decoration-underline text-primary">AHV HOLDING</span></a>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas" style="width: 210px;">
        <div class="bg-primary d-flex align-items-center gap-2 p-4 offcanvas-header">
            <h5 class="flex-grow-1 text-white mb-0" style="padding-bottom: 2px;">Theme Settings</h5>

            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-4 h-100" data-simplebar>
            <div class="mb-3">
                <h5 class="mb-3 font-16 fw-bold">Color Scheme</h5>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light" value="light">
                    <label class="form-check-label" for="layout-color-light">Light</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark" value="dark">
                    <label class="form-check-label" for="layout-color-dark">Dark</label>
                </div>
            </div>


            <div class="mb-3">
                <h5 class="mb-3 font-16 fw-bold">Topbar Color</h5>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-light" value="light">
                    <label class="form-check-label" for="topbar-color-light">Light</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-dark" value="dark">
                    <label class="form-check-label" for="topbar-color-dark">Dark</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-topbar-color" id="topbar-color-brand" value="brand">
                    <label class="form-check-label" for="topbar-color-brand">Brand</label>
                </div>
            </div>

            <div class="mb-3">
                <h5 class="mb-3 font-16 fw-bold">Menu Color</h5>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-light" value="light">
                    <label class="form-check-label" for="sidenav-color-light">Light</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-dark" value="dark">
                    <label class="form-check-label" for="sidenav-color-dark">Dark</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-menu-color" id="sidenav-color-brand" value="brand">
                    <label class="form-check-label" for="sidenav-color-brand">Brand</label>
                </div>
            </div>

            <div class="mb-3" id="sidebarSize">
                <h5 class="mb-3 font-16 fw-bold">Sidebar Size</h5>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-default" value="default">
                    <label class="form-check-label" for="sidenav-size-default">Default</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-compact" value="compact">
                    <label class="form-check-label" for="sidenav-size-compact">Compact</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-small" value="condensed">
                    <label class="form-check-label" for="sidenav-size-small"> Condensed</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-small-hover" value="sm-hover">
                    <label class="form-check-label" for="sidenav-size-small-hover">Hover View</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-full" value="full">
                    <label class="form-check-label" for="sidenav-size-full">Full Layout</label>
                </div>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="data-sidenav-size" id="sidenav-size-fullscreen" value="fullscreen">
                    <label class="form-check-label" for="sidenav-size-fullscreen">Hidden</label>
                </div>
            </div>

            <div class="mb-3">
                <h5 class="mb-3 font-16 fw-bold">Layout Mode</h5>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-fluid" value="fluid">
                    <label class="form-check-label" for="layout-mode-fluid">Fluid</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-boxed" value="boxed">
                    <label class="form-check-labe" for="layout-mode-boxed">Boxed</label>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center px-3 py-2 offcanvas-header border-top border-dashed">
            <button type="button" class="btn w-50 btn-soft-danger" id="reset-layout">Reset</button>
        </div>
    </div>

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
    {{-- <script>
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
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Reset form và xóa lỗi khi modal đóng
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function () {
                    const form = modal.querySelector('form');
                    if (form) {
                        form.reset();
    
                        // Xóa các lỗi hiển thị
                        // form.querySelectorAll('.text-danger').forEach(el => el.innerHTML = '');
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
                fakeInput.setAttribute('lang', 'en');
                fakeInput.setAttribute('inputmode', 'numeric'); // mobile sẽ hiện bàn phím số

    
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
                    // Bước 1: Lấy giá trị hiện tại của input (IME có thể ghi đè)
                    let current = this.value;

                    // Bước 2: Lọc toàn bộ số
                    let digits = current.replace(/[^\d]/g, '').slice(0, 8);

                    let day = digits.slice(0, 2);
                    let month = digits.slice(2, 4);
                    let year = digits.slice(4, 8);

                    // Bước 3: Giới hạn ngày / tháng nếu đủ ký tự
                    if (day.length === 2) {
                        day = Math.min(Math.max(+day, 1), 31).toString().padStart(2, '0');
                    }
                    if (month.length === 2) {
                        month = Math.min(Math.max(+month, 1), 12).toString().padStart(2, '0');
                    }

                    // Bước 4: Gắn lại định dạng
                    let formatted = '';
                    if (day) formatted += day;
                    if (month) formatted += '/' + month;
                    if (year) formatted += '/' + year;
                    this.value = formatted;

                    // Bước 5: Đồng bộ real input nếu đủ
                    if (day.length === 2 && month.length === 2 && year.length === 4) {
                        realInput.value = `${year}-${month}-${day}`;
                        realInput.dispatchEvent(new Event('change'));
                    } else {
                        realInput.value = '';
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Flatpickr core -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<!-- Flatpickr Vietnamese locale -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/vn.js"></script>


<script>
    $(document).ready(function () {
        $('.real-date, .datetime-local, .time-input').each(function () {
            const input = this;
            let isManuallySetting = false;
            const isDatetime = $(input).hasClass('datetime-local');
            const isTimeOnly = $(input).hasClass('time-input');

            const fp = flatpickr(input, {
                dateFormat: isDatetime ? "d/m/Y H:i" : isTimeOnly ? "H:i" : "d/m/Y",
                enableTime: isDatetime || isTimeOnly,
                noCalendar: isTimeOnly,
                time_24hr: true,
                allowInput: true,
                clickOpens: true,
                altInput: false,

                onChange: function (selectedDates) {
                    if (isManuallySetting || !selectedDates[0]) return;
                    const formattedDate = formatDate(selectedDates[0], isDatetime, isTimeOnly);
                }
            });

            // Hỗ trợ gõ nhanh bằng tay
            let isComposing = false;
            input.addEventListener('compositionstart', () => isComposing = true);
            input.addEventListener('compositionend', () => {
                isComposing = false;
                formatInputText(input, fp, isDatetime, isTimeOnly);
            });
            input.addEventListener('input', function () {
                if (!isComposing) formatInputText(input, fp, isDatetime, isTimeOnly);
            });

            function formatDate(date, includeTime = false, onlyTime = false) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');

                if (onlyTime) return `${hours}:${minutes}`;
                if (includeTime) return `${day}/${month}/${year} ${hours}:${minutes}`;
                return `${day}/${month}/${year}`;
            }

            function formatInputText(input, fp, includeTime = false, onlyTime = false) {
                let raw = input.value.replace(/[^\d]/g, '');
                let day = '', month = '', year = '', hour = '', minute = '';
                if (onlyTime) {
                    raw = raw.slice(0, 4); // hhmm
                    hour = raw.slice(0, 2);
                    minute = raw.slice(2, 4);

                    if (hour.length === 2) hour = Math.min(+hour, 23).toString().padStart(2, '0');
                    if (minute.length === 2) minute = Math.min(+minute, 59).toString().padStart(2, '0');

                    let formatted = '';
                    if (hour) formatted += hour;
                    if (minute) formatted += ':' + minute;

                    input.value = formatted;

                    if (hour.length === 2 && minute.length === 2) {
                        const now = new Date();
                        now.setHours(hour);
                        now.setMinutes(minute);
                        now.setSeconds(0);

                        const final = `${hour}:${minute}`;
                        if (fp.input.value !== final) {
                            isManuallySetting = true;
                            fp.setDate(now, true, "H:i");
                            setTimeout(() => isManuallySetting = false, 100);
                        }
                    }
                } else {
                    raw = raw.slice(0, includeTime ? 12 : 8); // ddMMyyyyhhmm
                    day = raw.slice(0, 2);
                    month = raw.slice(2, 4);
                    year = raw.slice(4, 8);
                    hour = raw.slice(8, 10);
                    minute = raw.slice(10, 12);

                    if (day.length === 2) day = Math.min(+day, 31).toString().padStart(2, '0');
                    if (month.length === 2) month = Math.min(+month, 12).toString().padStart(2, '0');
                    if (hour.length === 2) hour = Math.min(+hour, 23).toString().padStart(2, '0');
                    if (minute.length === 2) minute = Math.min(+minute, 59).toString().padStart(2, '0');

                    let formatted = '';
                    if (day) formatted += day;
                    if (month) formatted += '/' + month;
                    if (year) formatted += '/' + year;
                    if (includeTime) {
                        if (hour) formatted += ' ' + hour;
                        if (minute) formatted += ':' + minute;
                    }

                    input.value = formatted;

                    if (
                        day.length === 2 && month.length === 2 && year.length === 4 &&
                        (!includeTime || (hour.length === 2 && minute.length === 2))
                    ) {
                        const final = includeTime
                            ? `${day}/${month}/${year} ${hour}:${minute}`
                            : `${day}/${month}/${year}`;

                        if (fp.input.value !== final) {
                            isManuallySetting = true;
                            fp.setDate(final, true, includeTime ? "d/m/Y H:i" : "d/m/Y");
                            setTimeout(() => isManuallySetting = false, 100);
                        }
                    }
                }
            }
        });
    });
</script>



<script>
    $('form').on('submit', function (e) {
        $(this).find('.real-date, .datetime-local').each(function () {
            const input = this;
            const value = input.value.trim();

            // Tách phần ngày và giờ (nếu có)
            const [datePart, timePart] = value.split(' ');
            const [day, month, year] = datePart.split('/');

            if (day && month && year) {
                let iso = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

                // Nếu có thêm giờ phút
                if (timePart) {
                    const [hour, minute] = timePart.split(':');
                    if (hour && minute) {
                        iso += `T${hour.padStart(2, '0')}:${minute.padStart(2, '0')}:00`;
                    }
                }
                input.value = iso;                
            }
        });
    });
</script>


    
    
    
    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('assets/libs/morris.js/morris.min.js') }}"></script>
    <script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Projects Analytics Dashboard App js -->
    <script src="{{ asset('assets/js/pages/dashboard-sales.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Ẩn thông báo sau 10 giây (10000ms)
            setTimeout(function() {
                $('#successMessage').fadeOut('slow');
                $('#errorMessage').fadeOut('slow');
            }, 5000);
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hàm định dạng số với dấu phân cách hàng nghìn
            window.formatNumber = function formatNumber(number) {
                if (number === null || number === undefined || number === '') return '';
                return Number(number).toLocaleString('vi-VN');
            }

            // Hàm bỏ định dạng để lấy số nguyên
            window.unformatNumber = function unformatNumber(formattedNumber) {
                if (!formattedNumber) return '';
                return formattedNumber.replace(/[^0-9]/g, '');
            }

            // Tìm tất cả input có class currency-input
            const currencyInputs = document.querySelectorAll('input.currency-input');
            currencyInputs.forEach(input => {
                // Định dạng khi nhập
                input.addEventListener('input', function() {
                    const rawValue = unformatNumber(this.value);
                    this.value = formatNumber(rawValue);
                });

                // Định dạng giá trị ban đầu (nếu có)
                if (input.value) {
                    input.value = formatNumber(input.value);
                }
            });

            // Xử lý tất cả form trước khi gửi
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    currencyInputs.forEach(input => {
                        if (input.form === this) { // Chỉ xử lý input trong form đang gửi
                            const formattedValue = input.value;
                            input.value = unformatNumber(formattedValue);
                        }
                    });
                });
            });
        });
    </script>

    @yield('js')

</body>

</html>