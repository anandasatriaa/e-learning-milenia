@extends('pages.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Selamat Datang, {{ ucfirst(strtolower(Str::before(Auth::user()->Nama, ' '))) }} !
                </h3>
                <h6 class="op-7 mb-2">Semoga aktivitas belajarmu menyenangkan</h6>
            </div>
            {{-- <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kursus Tersedia</p>
                                    <h4 class="card-title">{{ $totalCourses }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kursus Diikuti</p>
                                    <h4 class="card-title">{{ $coursesFollowed }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-luggage-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kursus Sedang Dipelajari</p>
                                    <h4 class="card-title">{{ $coursesInProgress }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="far fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Kursus Telah Diselesaikan</p>
                                    <h4 class="card-title">{{ $coursesCompleted }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"> <span class="bg-light p-1 rounded me-1">
                                    <i class="icon-graph"></i></span>
                                Progress Pelatihan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($courseEnrolled as $courseEnrolleds)
                                <div class="col-12 col-md-6">
                                    <div class="card card-stats card-round shadow-none {{ $courseEnrolleds->status != 'complete' ? 'bg-light' : 'bg-white border' }}">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-icon">
                                                    <img class="w-100 rounded" src="{{ $courseEnrolleds->course->thumbnail_url }}" alt="{{ $courseEnrolleds->course->thumbnail_url }}">
                                                </div>
                                                <div class="col col-stats ms-3 ms-sm-0">
                                                    <div class="number">
                                                        @if ($courseEnrolleds->status != 'complete')
                                                            <p class="card-category fw-bold text-danger"> Sedang dipelajari </p>
                                                        @else
                                                            <p class="card-category fw-bold text-success align-middle"> Telah diselesaikan <i class="fas fa-check-circle"></i></p>
                                                        @endif
                                                        <h5 class="card-text text-truncate" style="line-height: 1.5rem;">{{ $courseEnrolleds->course->nama_kelas }}</h5>
                                                        <h6 class="text-muted" style="font-size: 0.8rem; line-height: 0.5rem;">Dimulai {{ \Carbon\Carbon::parse($courseEnrolleds->enroll_date)->format('d M Y') }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if ($courseEnrolleds->status != 'complete')
                                                    <a href="{{ route('pages.course.course.detail', $courseEnrolleds->course_id) }}" class="btn btn-label-warning btn-round btn-sm me-2 stretched-link">
                                                        Lanjutkan <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('pages.course.course.detail', $courseEnrolleds->course_id) }}" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                                        Detail <i class="fas fa-search"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
