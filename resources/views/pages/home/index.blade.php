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
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a>
            </div>
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
                                    <p class="card-category">Data 1</p>
                                    <h4 class="card-title">1,294</h4>
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
                                    <p class="card-category">Data 2</p>
                                    <h4 class="card-title">1303</h4>
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
                                    <p class="card-category">Data 3</p>
                                    <h4 class="card-title">$ 1,345</h4>
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
                                    <p class="card-category">Data 4</p>
                                    <h4 class="card-title">576</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-6">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"> <span class="bg-light p-1 rounded me-1">
                                    <i class="icon-graph"></i></span>
                                Progress Pelatihan</div>
                            <div class="card-tools">
                                <a href="#" class="btn btn-label-info btn-round btn-sm me-2">
                                    <span class="btn-label">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    Lihat Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($courseEnrolled as $courseEnrolleds)
                            <div
                                class="card card-stats card-round shadow-none {{ $courseEnrolleds->status != 'complete' ? 'bg-light' : 'bg-white border' }} ">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <img class="w-100 rounded" src="{{ $courseEnrolleds->course->thumbnail_url }}"
                                                alt="{{ $courseEnrolleds->course->thumbnail_url }}">
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="number">
                                                @if ($courseEnrolleds->status != 'complete')
                                                    <p class="card-category fw-bold text-danger"> Sedang dipelajari </p>
                                                @else
                                                    <p class="card-category fw-bold text-success align-middle"> Telah
                                                        diselesaikan
                                                        <i class="fas fa-check-circle"></i>
                                                    </p>
                                                @endif
                                                <h5 class="card-text text-truncate">
                                                    {{ $courseEnrolleds->course->nama_kelas }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if ($courseEnrolleds->status != 'complete')
                                            <a href="{{ route('pages.course.course.detail', $courseEnrolleds->course_id) }}"
                                                class="btn btn-label-warning btn-round btn-sm me-2 stretched-link">
                                                Lanjutkan <i class="fas fa-arrow-right"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('pages.course.course.detail', $courseEnrolleds->course_id) }}"
                                                class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                                Detail <i class="fas fa-search"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-primary card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Aktivitas Lain</div>
                            <div class="card-tools">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">

                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection