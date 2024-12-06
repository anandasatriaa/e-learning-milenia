@extends('admin.layouts.app')
@section('title', 'Nilai ')
@section('css')
    <style>
        .accordion .card {
            margin-bottom: 0.5rem; /* Sesuaikan sesuai keinginan */
        }
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
                            <div class="col-icon d-flex align-items-center">
                                <!-- Image -->
                                <img class="rounded me-3" src="{{ asset('img/no_image.jpg') }}" width="15%" alt="Course Image">

                                <!-- Text content: Peserta Kursus, Modul, and Peserta -->
                                <div>
                                    <!-- Peserta Kursus -->
                                    <span class="d-block fw-bold">Course 1</span>

                                    <!-- Modul and Peserta -->
                                    <div class="mt-1 fs-6">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-book me-2"></i>
                                            <span class="fw-semibold">5 Modul</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-friends me-2"></i>
                                            <span class="fw-semibold">20 Peserta</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                        <i class="fas fa-user-friends me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Peserta 1</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Quiz: -</span>
                                                </div>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Essay: 100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                            Review & Nilai <i class="fas fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review & Penilaian { $peserta_satu}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form atau konten review -->
                <div class="accordion accordion-black">
                    <div class="card">
                        <div class="card-header" id="headingOne" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <div class="span-title">
                            Modul 1
                        </div>
                        <div class="span-mode"></div>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header collapsed" id="headingTwo" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <div class="span-title">
                            Modul 2
                        </div>
                        <div class="span-mode"></div>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header collapsed" id="headingThree" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <div class="span-title">
                            Modul 3
                        </div>
                        <div class="span-mode"></div>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                        </div>
                    </div>
                </div>
                <form>
                    <div class="mb-3">
                        <label for="nilaiQuiz" class="form-label">Nilai Quiz</label>
                        <input type="number" class="form-control" id="nilaiQuiz" placeholder="Masukkan nilai quiz">
                    </div>
                    <div class="mb-3">
                        <label for="nilaiEssay" class="form-label">Nilai Essay</label>
                        <input type="number" class="form-control" id="nilaiEssay" placeholder="Masukkan nilai essay">
                    </div>
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea class="form-control" id="komentar" rows="3" placeholder="Tulis komentar"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary">Simpan Penilaian</button>
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
                                                        <i class="fas fa-user-friends me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Peserta 1</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Quiz: -</span>
                                                </div>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Essay: 100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Review & Nilai <i class="fas fa-search"></i>
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
                                                        <i class="fas fa-user-friends me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Peserta 1</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Quiz: -</span>
                                                </div>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Essay: 100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Review & Nilai <i class="fas fa-search"></i>
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
                                                        <i class="fas fa-user-friends me-2 fs-4"></i>
                                                        <span class="ms-2 fw-semibold fs-4">Peserta 1</span>
                                                    </span>
                                                </p>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Quiz: -</span>
                                                </div>
                                                <div class="d-flex align-items-center text-muted">
                                                    <span class="fw-semibold">Nilai Essay: 100</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="#" class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                            Review & Nilai <i class="fas fa-search"></i>
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