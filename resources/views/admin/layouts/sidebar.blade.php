@php $routeName = \Request::route()->getName() @endphp

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="white">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="navbar brand" class="navbar-brand" height="45">
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Dashboard</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.matriks-kompetensi') ? 'active' : '' }}">
                    <a href="{{ route('admin.matriks-kompetensi') }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Matriks Kompetensi</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">User</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#user_sidebar">
                        <i class="fas fa-users"></i>
                        <p>Karyawan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.user.*') ? 'show' : '' }}" id="user_sidebar">
                        <ul class="nav nav-collapse">
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.user.employee.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.user.employee.index') }}">
                                    <i class="fas fa-user-friends"></i>
                                    <span>Semua Karyawan</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Kelas</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#kategori_sidebar">
                        <i class="fas fa-layer-group"></i>
                        <p>Kategori</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.category.*') ? 'show' : '' }}"
                        id="kategori_sidebar">
                        <ul class="nav nav-collapse">
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.category.learning.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.category.learning.index') }}">
                                    <i class="fas fa-list"></i>
                                    <span>Learning Category</span>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.category.divisi-category.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.category.divisi-category.index') }}">
                                    <i class="fas fa-th-list"></i>
                                    <span>Division</span>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.category.category.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.category.category.index') }}">
                                    <i class="fas fa-th-large"></i> Category
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.category.sub-category.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.category.sub-category.index') }}">
                                    <i class="fas fa-th"></i> Sub Category
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.course.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#kelas_sidebar">
                        <i class="fas fa-book-reader"></i>
                        <p>Course</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.course.*') ? 'show' : '' }}" id="kelas_sidebar">
                        <ul class="nav nav-collapse">
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.course.course.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.course.course.index') }}">
                                    <i class="fas fa-book"></i>
                                    <span>Class</span>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.course.nilai.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.course.nilai.index') }}">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span>Input Nilai</span>
                                </a>
                            </li>
                            <li class="ms-3">
                                <a class="{{ request()->routeIs('admin.course.nilai-matriks.*') ? 'text-white fw-bold active border-start border-2' : '' }}"
                                    href="{{ route('admin.course.nilai-matriks.index') }}">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span>Input Nilai Matriks Kompetensi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Calendar</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.calendar.calendar.index') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Course Schedule</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Preview</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.preview.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.preview.preview-nilai') }}">
                        <i class="fas fa-award"></i>
                        <p>Hasil Nilai Peserta</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Kuesioner</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.kuesioner.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kuesioner.feedback-kuesioner') }}">
                        <i class="fas fa-comment-dots"></i>
                        <p>Feedback Kuesioner</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
