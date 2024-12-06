@extends('admin.layouts.app')
@section('title', 'Nilai ')
@section('css')
    <style>

    </style>
@endsection
@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">
                            <span class="bg-light p-1 rounded me-1 fs-3">
                                <i class="fas fa-book-reader fs-3"></i>
                            </span>
                            Nilai Kursus
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-4">
                    <div class="row">
                        <!-- Card 1 -->
                        <div class="col-lg-6">
                            <div class="card card-stats card-round shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <img class="w-100 rounded" src="{{ asset('img/no_image.jpg') }}" alt="Course Image">
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="number">
                                                <p class="card-category fw-bold text-dark align-middle">
                                                    <span class="d-flex align-items-center">
                                                        <i class="fas fa-book-open me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Course 1</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3 text-muted">
                                                    <div class="d-flex align-items-center me-2">
                                                        <i class="fas fa-book me-2 fs-5"></i>
                                                        <span class="fw-semibold">5 Modul</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-friends me-2 fs-5"></i>
                                                        <span class="fw-semibold">20 Peserta</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ route('admin.course.nilai.detail') }}" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Detail <i class="fas fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-lg-6">
                            <div class="card card-stats card-round shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <img class="w-100 rounded" src="{{ asset('img/no_image.jpg') }}" alt="Course Image">
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="number">
                                                <p class="card-category fw-bold text-dark align-middle">
                                                    <span class="d-flex align-items-center">
                                                        <i class="fas fa-book-open me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Course 2</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3 text-muted">
                                                    <div class="d-flex align-items-center me-2">
                                                        <i class="fas fa-book me-2 fs-5"></i>
                                                        <span class="fw-semibold">10 Modul</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-friends me-2 fs-5"></i>
                                                        <span class="fw-semibold">35 Peserta</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Detail <i class="fas fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-lg-6">
                            <div class="card card-stats card-round shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <img class="w-100 rounded" src="{{ asset('img/no_image.jpg') }}" alt="Course Image">
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="number">
                                                <p class="card-category fw-bold text-dark align-middle">
                                                    <span class="d-flex align-items-center">
                                                        <i class="fas fa-book-open me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Course 2</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3 text-muted">
                                                    <div class="d-flex align-items-center me-2">
                                                        <i class="fas fa-book me-2 fs-5"></i>
                                                        <span class="fw-semibold">10 Modul</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-friends me-2 fs-5"></i>
                                                        <span class="fw-semibold">35 Peserta</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Detail <i class="fas fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-lg-6">
                            <div class="card card-stats card-round shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <img class="w-100 rounded" src="{{ asset('img/no_image.jpg') }}" alt="Course Image">
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="number">
                                                <p class="card-category fw-bold text-dark align-middle">
                                                    <span class="d-flex align-items-center">
                                                        <i class="fas fa-book-open me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Course 2</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3 text-muted">
                                                    <div class="d-flex align-items-center me-2">
                                                        <i class="fas fa-book me-2 fs-5"></i>
                                                        <span class="fw-semibold">10 Modul</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-friends me-2 fs-5"></i>
                                                        <span class="fw-semibold">35 Peserta</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Detail <i class="fas fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery-3.7.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Bootstrap Notify -->
	<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
	<script src="assets/js/plugin/jsvectormap/world.js"></script>

	<!-- Sweet Alert -->
	<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Kaiadmin JS -->
	<script src="assets/js/kaiadmin.min.js"></script>

	<!-- Kaiadmin DEMO methods, don't include it in your project! -->
	<script src="assets/js/setting-demo.js"></script>
	<script src="assets/js/demo.js"></script>
	<script>
		$('#lineChart').sparkline([102,109,120,99,110,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#177dff',
			fillColor: 'rgba(23, 125, 255, 0.14)'
		});

		$('#lineChart2').sparkline([99,125,122,105,110,124,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#f3545d',
			fillColor: 'rgba(243, 84, 93, .14)'
		});

		$('#lineChart3').sparkline([105,103,123,100,95,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#ffa534',
			fillColor: 'rgba(255, 165, 52, .14)'
		});
	</script>
@endsection