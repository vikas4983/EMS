<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Modern Education Admin Dashboard for schools, colleges, universities, and eLearning platforms. Includes student and course management, attendance, exams, payments, analytics, and a fully responsive clean UI—ideal for LMS, coaching centers, and academic admin systems.">
    <meta name="keywords"
        content="Education Admin Dashboard, School Admin Panel, College Dashboard, University Dashboard, LMS Dashboard, eLearning Admin Template, Student Management System, Course Management, Education Template, Study Dashboard, Online Learning Dashboard, Academic Admin Panel, Bootstrap Dashboard, React Education Dashboard, Next.js Education Template">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('assets/css/apexcharts.css') }}">
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css') }}">
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}">
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('assets/css/full-calendar.css') }}">
    <!-- calendar -->
    <link rel="stylesheet" href="{{ asset('assets/css/calendar.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

    <!-- Theme Customization Structure Start -->
    <div class="body-overlay"></div>

    <button type="button"
        class="theme-customization__button w-48-px h-48-px bg-primary-600 text-white rounded-circle d-flex justify-content-center align-items-center position-fixed end-0 bottom-0 mb-40 me-40 text-2xxl bg-hover-primary-700"
        aria-label="Theme Customization Button">
        <i class="ri-settings-3-line animate-spin"></i>
    </button>
    <div class="theme-customization-sidebar w-100 bg-base h-100vh overflow-y-auto position-fixed end-0 top-0">
        <div class="d-flex align-items-center gap-3 py-16 px-24 justify-content-between border-bottom">
            <div>
                <h6 class="text-sm dark:text-white">Theme Settings</h6>
                <p class="text-xs mb-0 text-neutral-500 dark:text-neutral-200">Customize and preview instantly</p>
            </div>
            <button data-slot="button"
                class="theme-customization-sidebar__close text-neutral-900 bg-transparent text-hover-primary-600 d-flex text-xl">
                <i class="ri-close-fill"></i>
            </button>
        </div>

        <div class="d-flex flex-column gap-48 p-24 overflow-y-auto flex-grow-1">

            <div class="theme-setting-item">
                <h6 class="fw-medium text-primary-light text-md mb-3">Theme Mode</h6>
                <div class="d-grid grid-cols-3 gap-3 dark-light-mode">
                    <button type="button"
                        class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl active"
                        data-theme="light" aria-label="light">
                        <i class="ri-sun-line"></i>
                    </button>
                    <button type="button"
                        class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl"
                        data-theme="dark" aria-label="dark">
                        <i class="ri-moon-line"></i>
                    </button>
                    <button type="button"
                        class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl"
                        data-theme="system" aria-label="system">
                        <i class="ri-computer-line"></i>
                    </button>
                </div>
            </div>

            <div class="theme-setting-item">
                <h6 class="fw-medium text-primary-light text-md mb-3">Page Direction</h6>
                <div class="d-grid grid-cols-2 gap-3">
                    <button type="button"
                        class="theme-setting-item__btn ltr-mode-btn d-flex align-items-center justify-content-center gap-2 h-56-px rounded-3 text-xl"
                        aria-label="LTR">
                        <span><i class="ri-align-item-left-line"></i></span>
                        <span class="h6 text-sm font-medium mb-0">LTR</span>
                    </button>

                    <button type="button"
                        class="theme-setting-item__btn rtl-mode-btn d-flex align-items-center justify-content-center gap-2 h-56-px rounded-3 text-xl"
                        aria-label="RTL">
                        <span class="h6 text-sm font-medium mb-0">RTL</span>
                        <span><i class="ri-align-item-right-line"></i></span>
                    </button>
                </div>
            </div>

            <div class="theme-setting-item">
                <h6 class="fw-medium text-primary-light text-md mb-3">Color Schema</h6>
                <div class="d-grid grid-cols-3 gap-3">
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="base" aria-label="Base">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #25A194;"></span>
                        <span class="fw-medium mt-1" style="color: #25A194;">Base</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="red" aria-label="Red">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #dc2626;"></span>
                        <span class="fw-medium mt-1" style="color: #dc2626;">Red</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="blue" aria-label="Blue">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #2563eb;"></span>
                        <span class="fw-medium mt-1" style="color: #2563eb;">Blue</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="yellow" aria-label="Yellow">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #ff9f29;"></span>
                        <span class="fw-medium mt-1" style="color: #ff9f29;">Yellow</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="cyan" aria-label="Cyan">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #00b8f2;"></span>
                        <span class="fw-medium mt-1" style="color: #00b8f2;">Cyan</span>
                    </button>
                    <button type="button"
                        class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                        data-color="violet" aria-label="Violet">
                        <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                            style="background-color: #7c3aed;"></span>
                        <span class="fw-medium mt-1" style="color: #7c3aed;">Violet</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <!-- Theme Customization Structure End -->

    <div
        class="overlay bg-black bg-opacity-50 w-100 h-100 position-fixed z-9 visibility-hidden opacity-0 duration-300">
    </div>
    <aside class="sidebar">
        <button type="button" class="sidebar-close-btn">
            <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
        </button>
        <div class="">
            <div class="sidebar-logo d-flex align-items-center justify-content-between">
                <a href="schoolDashboard.html" class="">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
                    <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
                </a>
                <button type="button" class="text-xxl d-xl-flex d-none line-height-1 sidebar-toggle text-neutral-500"
                    aria-label="Collapse Sidebar">
                    <i class="ri-contract-left-line"></i>
                </button>
            </div>
        </div>
        @php
            $user = auth()->user();
        @endphp
        <!-- User Info start -->
        <div class="mx-16 py-12">
            <div class="dropdown profile-dropdown">
                <button type="button"
                    class="profile-dropdown__button d-flex align-items-center justify-content-between p-10 w-100 overflow-hidden bg-neutral-50 radius-12 "
                    data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <span class="d-flex align-items-start gap-10">
                        <img src="{{ asset('assets/images/leave-request-img2.png') }}" alt="Thumbnail"
                            class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <span class="profile-dropdown__contents">
                            <span class="h6 mb-0 text-md d-block text-primary-light">
                                {{ $user?->name ?? 'Gaust' }}
                            </span>
                            <span class="text-secondary-light text-sm mb-0 d-block">
                                {{ $user?->email ?? 'Gaust email' }}
                            </span>
                        </span>
                    </span>
                    <span class="profile-dropdown__icon pe-8 text-xl d-flex line-height-1">
                        <i class="ri-arrow-right-s-line"></i>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                    <li>
                        <a href="student-details.html"
                            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                            <i class="ri-user-3-line"></i>
                            My Profile
                        </a>
                    </li>
                    <li>
                        <a href="general.html"
                            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                            <i class="ri-settings-3-line"></i>
                            Setting
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-shut-down-line"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- User Info end -->
        <div class="sidebar-menu-area">
            <ul class="sidebar-menu" id="sidebar-menu">
                <li class="dropdown">
                    <a href="{{ route('dashboard') }}">
                        <i class="ri-home-4-line"></i>
                        <span>Dashboard </span>
                    </a>

                </li>
                @role('admin')
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <i class="ri-graduation-cap-line"></i>
                            <span>Categories</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('categories.index') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    List
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.create') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    Create
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.trashed') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    Trashed Category
                                </a>
                            </li>

                        </ul>
                    </li>
                @endrole
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="ri-graduation-cap-line"></i>
                        <span>Expenses</span>
                    </a>
                    <ul class="sidebar-submenu">
                        @role('employee')
                            <li>
                                <a href="{{ route('expenses.index') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    List
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('expenses.create') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    Create
                                </a>
                            </li>
                        @endrole
                        @role('manager')
                            <li>
                                <a href="{{ route('get-expenses') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    List
                                </a>
                            </li>
                        @endrole
                        @role('admin')
                            <li>
                                <a href="{{ route('expenses.create') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    Create
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('get-expenses') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    List
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('expenses.trashed') }}">
                                    <i class="ri-circle-fill circle-icon w-auto"></i>
                                    Trashed Expenses
                                </a>
                            </li>
                        @endrole

                    </ul>
                </li>

            </ul>
        </div>
    </aside>

    <main class="dashboard-main">
        <div class="navbar-header shadow-1">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        <button type="button" class="sidebar-mobile-toggle"
                            aria-label="Sidebar Mobile Toggler Button">
                            <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                        </button>
                        <form class="navbar-search">
                            <input type="text" class="bg-transparent" name="search" placeholder="Search">
                            <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                        </form>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <button type="button" data-theme-toggle
                            class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                            aria-label="Dark & Light Mode Button"></button>

                        <div class="dropdown" data-notification-dropdown>
                            <button
                                class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center position-relative"
                                type="button" data-bs-toggle="dropdown" aria-label="Notification Button">
                                <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                                <span id="notificationDot"
                                    class="w-8-px h-8-px bg-danger-600 position-absolute end-0 top-0 rounded-circle mt-2 me-2"
                                    style="display: none;"></span>
                                <span id="notificationCount"
                                    class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                                    style="display: none; font-size: 10px; min-width: 20px;">0</span>
                            </button>

                            <div class="dropdown-menu to-top dropdown-menu-lg p-0"
                                style="width: 380px; max-height: 500px; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.10);">
                                <div class="d-flex align-items-center justify-content-between py-10 px-16 border-bottom"
                                    style="background: #f8fafc; border-radius: 8px 8px 0 0;">
                                    <h6 class="text-md fw-semibold mb-0">Notifications</h6>
                                    <button type="button" id="markAllReadBtn"
                                        class="btn btn-sm btn-link text-primary"
                                        style="font-size: 12px; text-decoration: none; padding: 2px 10px;">
                                        Mark all read
                                    </button>
                                </div>

                                <div id="notificationList" class="overflow-y-auto" style="max-height: 380px;">
                                    <div class="text-center py-4" id="notificationLoading">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 mb-0 text-sm text-muted">Loading...</p>
                                    </div>
                                </div>

                                <div class="text-center py-8 border-top"
                                    style="background: #f8fafc; border-radius: 0 0 8px 8px;">
                                    <a href="javascript:void(0)" id="viewAllNotifications"
                                        class="text-primary fw-semibold text-sm hover-underline">
                                        View All
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        @yield('content')
        <footer class="d-footer">
            <div class="">
                <p class="mb-0 text-center"> &copy; <span class="current-year"></span> Made With ❤️ by Wowtheme7.
                </p>
            </div>
        </footer>
    </main>

    <!-- jQuery library js -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Apex Chart js -->
    {{-- <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script> --}}
    <!-- Iconify Font js -->
    <script src="{{ asset('assets/js/iconify-icon.min.js') }}"></script>
    <!-- Data Table js -->
    {{-- <script src="{{ asset('assets/js/dataTables.min.js') }}"></script> --}}

    <!-- jQuery UI js -->
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

    <!-- main js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="notificationToast" class="toast align-items-center border-0 shadow-lg" role="alert"
            aria-live="assertive" aria-atomic="true" style="min-width: 300px; display: none; border-radius: 8px;">
            <div class="d-flex align-items-center p-3">
                <div class="flex-grow-1">
                    <strong id="toastTitle" class="d-block mb-1 text-sm fw-semibold"></strong>
                    <p id="toastMessage" class="mb-0 text-sm opacity-90"></p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>@vite(['resources/js/app.js'])

</body>

</html>
