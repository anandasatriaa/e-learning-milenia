@extends('pages.layouts.app')
@section('title', $course->nama_kelas)
@section('css')
    <style>
        .list-materi:hover {
            background-color: whitesmoke
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Detail Kelas</h3>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('pages.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="">Kelas</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a>{{ $course->nama_kelas }}</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a>Introduction</a>
                </li>
            </ul>
        </div>
        <div class="card card-round">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe id="videoSource" src="" frameborder="0" oncontextmenu="return false"></iframe>
                        </div>
                    </div>
                    <div class="pb-3 px-3">
                        <div class="card-head-row">
                            <div class="card-title"> <span class="bg-light p-1 rounded me-1">
                                    <i class="icon-pin"></i></span>
                                Introduction
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-label-info btn-round btn-sm me-2" id="goToLastAccess">
                                    Last access : <span id="lastAccess">00:00</span> <i class="fas fa-history ms-1"></i>
                                </button>
                                <a href="#" id="mulaiBelajarBtn" class="btn btn-success btn-round btn-sm me-2">
                                    Mulai Belajar <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card bg-light-subtle rounded-end-4 rounded-start-0 h-100 shadow-none ">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title"><i class="icon-grid align-middle me-1"></i><span
                                        class="align-middle">
                                        Materi</span></div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-label-info dropdown-toggle" type="button"
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
<ul class="list-group list-group-flush overflow-y-scroll" style="max-height: 60vh">
    @foreach ($course->modul as $modul)
        <li class="list-group-item list-materi align-middle"
            data-url="{{ $modul->url_media_link }}"
            data-type="{{ $modul->tipe_media }}"
            data-quiz="{{ $modul->quiz_available ? 'true' : 'false' }}">
            <input class="form-check-input me-3" type="checkbox" />
            @switch($modul->tipe_media)
                @case('video')
                    <span class="me-2 bg-danger px-2 py-1 my-auto rounded text-white">
                        <i class="fas fa-video"></i></span>
                @break

                @case('pdf')
                    <span class="me-2 bg-info-subtle px-2 py-1 my-auto rounded">
                        <i class="icon-book-open"></i></span>
                @break

                @case('link')
                    <span class="me-2 bg-primary px-2 py-1 my-auto rounded text-white">
                        <i class="icon-share-alt"></i></span>
                @break

                @default
            @endswitch
            <a href="" class="stretched-link text-black"
               data-media-url="{{ $modul->url_media_link }}" 
               data-media-type="{{ $modul->tipe_media }}">{{ $modul->nama_modul }}</a>
        </li>
    @endforeach
</ul>


                    </div>
                </div>
            </div>
        </div>
<div id="quizContainer" style="display: none;">
    <div class="card">
        <div class="card-header">
            Pertanyaan: Apa ibu kota Indonesia?
        </div>
        <div class="card-body">
            <form>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="answer1" value="Jakarta">
                    <label class="form-check-label" for="answer1">Jakarta</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="answer2" value="Bandung">
                    <label class="form-check-label" for="answer2">Bandung</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="answer3" value="Surabaya">
                    <label class="form-check-label" for="answer3">Surabaya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="answer4" value="Medan">
                    <label class="form-check-label" for="answer4">Medan</label>
                </div>
            </form>
        </div>
        <div class="card-footer text-muted">
            Pilih salah satu jawaban di atas.
        </div>
    </div>
</div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-2 text-center">
                        <img class="img-thumbnail rounded" src="{{ $course->thumbnail_url }}" alt="{{ $course->thumbnail }}"
                            width="200px">
                    </div>
                    <div class="col-12 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="mb-1">
                                    <span class="text-muted">Divisi</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->subCategory->category->divisiCategory->nama }}</h6>
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted">Kategori</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->subCategory->category->nama }}</h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted">Sub Kategori</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->subCategory->nama }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="accordion accordion-secondary">
                            <div class="card">
                                <div class="card-header collapsed" id="headingOne" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <div class="span-title text-uppercase fw-bold text-muted">
                                        Deskripsi Kelas
                                    </div>
                                    <div class="span-mode"></div>
                                </div>

                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#accordion" style="">
                                    <div class="card-body">
                                        {!! $course->deskripsi !!}
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
    <script>
        $(document).ready(function() {
            var course_id = "{{ $course->id }}";
            var videoUrl = "{{ url('/course/embed-video/') }}" + "/" + course_id;
            $('#videoSource').attr('src', videoUrl);
        });
        $('#videoSource').on("load", function() {
            const iframeVideo = $(this)[0].contentWindow.document.querySelector('video');
            if (iframeVideo) {
                iframeVideo.setAttribute("controlslist", "nodownload");
                iframeVideo.setAttribute("oncontextmenu", "return false");
                iframeVideo.setAttribute("id", "mainVideo");
                console.log(iframeVideo.duration)
                var supposedCurrentTime = 0;
                var lastLongAccessTime = 0;

                $('#goToLastAccess').click(function(e) {
                    e.preventDefault();
                    iframeVideo.currentTime = lastLongAccessTime;
                });

                $(iframeVideo).on('timeupdate', function() {
                    if (!iframeVideo.seeking) {
                        supposedCurrentTime = iframeVideo.currentTime;
                        if (lastLongAccessTime < iframeVideo.currentTime) {
                            lastLongAccessTime = iframeVideo.currentTime;
                        }

                    }
                    $('#lastAccess').text(formatTime(lastLongAccessTime))
                });

                $(iframeVideo).on('seeking', function() {
                    var delta = iframeVideo.currentTime - supposedCurrentTime;
                    var lastAccess = iframeVideo.currentTime - lastLongAccessTime;

                    if (delta > 0) {
                        if (iframeVideo.currentTime > lastLongAccessTime) {
                            iframeVideo.currentTime = supposedCurrentTime;
                        } else {
                            supposedCurrentTime = iframeVideo.currentTime;
                        }

                    } else if (iframeVideo.currentTime < supposedCurrentTime) {
                        supposedCurrentTime = iframeVideo.currentTime;
                    }
                });

                $(iframeVideo).on('ended', function() {
                    supposedCurrentTime = 0;
                });
            }
        });

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            const formattedMinutes = String(minutes).padStart(2, '0'); // Tambahkan nol di depan jika perlu
            const formattedSeconds = String(secs).padStart(2, '0'); // Tambahkan nol di depan jika perlu
            return `${formattedMinutes}:${formattedSeconds}`;
        }

        document.addEventListener("DOMContentLoaded", function () {
            const links = document.querySelectorAll('.list-group .stretched-link');
            const iframe = document.getElementById('videoSource');

            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault(); // Mencegah navigasi default

                    // Ambil URL media dan tipe media dari data-attributes
                    const mediaUrl = link.getAttribute('data-media-url');
                    const mediaType = link.getAttribute('data-media-type');

                    // Periksa tipe media dan tampilkan kontennya di iframe
                    if (mediaType === 'video') {
                        iframe.src = mediaUrl;  // Menampilkan video di iframe
                        iframe.style.display = 'block';  // Menampilkan iframe
                    } else if (mediaType === 'pdf') {
                        iframe.src = mediaUrl;  // Memuat PDF di iframe
                        iframe.style.display = 'block';  // Menampilkan iframe
                    } else if (mediaType === 'link') {
                        iframe.src = mediaUrl;  // Memuat URL di iframe
                        iframe.style.display = 'block';  // Menampilkan iframe
                    }
                });
            });
        });

    document.querySelector('.btn-success').addEventListener('click', function (e) {
        e.preventDefault();
        const courseId = {{ $course->id }};

        fetch(`/course/${courseId}/first-modul`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const iframe = document.getElementById('videoSource');
                    iframe.src = data.url;

                    // Tambahkan pengaturan tipe media jika diperlukan
                    if (data.tipe_media === 'pdf') {
                        iframe.src = `/pdf-viewer?file=${data.url}`;
                    } else if (data.tipe_media === 'link') {
                        iframe.src = data.url;
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });


    document.addEventListener("DOMContentLoaded", () => {
        const mulaiBelajarBtn = document.getElementById("mulaiBelajarBtn");
        const modulItems = document.querySelectorAll(".list-group-item");

        mulaiBelajarBtn.addEventListener("click", () => {
            if (modulItems.length > 0) {
                // Hapus class active dari semua modul
                modulItems.forEach(item => item.classList.remove("bg-secondary"));

                // Tambahkan class active ke modul pertama
                const firstModul = modulItems[0];
                firstModul.classList.add("bg-secondary");

                // Atur iframe untuk memuat media dari modul pertama
                const videoSource = document.getElementById("videoSource");
                videoSource.src = firstModul.getAttribute("data-url");
            }
        });
    });

    document.querySelector('#mulaiBelajarBtn').addEventListener('click', function (e) {
    e.preventDefault();

    // Simulasikan pengambilan data dari server
    const courseId = {{ $course->id }};
    fetch(`/course/${courseId}/first-modul`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const iframe = document.getElementById('videoSource');
                const quizContainer = document.getElementById('quizContainer');
                const button = document.getElementById('mulaiBelajarBtn');

                if (data.is_quiz) {
                    // Sembunyikan iframe dan tampilkan kuis
                    iframe.style.display = 'none';
                    quizContainer.style.display = 'block';

                    // Ganti tombol menjadi "Kerjakan Kuis"
                    button.textContent = 'Kerjakan Kuis';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                } else {
                    // Tampilkan media di iframe
                    iframe.src = data.url;
                    iframe.style.display = 'block';
                    quizContainer.style.display = 'none';

                    // Kembalikan tombol ke "Mulai Belajar"
                    button.textContent = 'Mulai Belajar';
                    button.classList.add('btn-success');
                    button.classList.remove('btn-primary');
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});



    </script>
@endsection
