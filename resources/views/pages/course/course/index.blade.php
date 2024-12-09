@extends('pages.layouts.app')
@section('title', $course->nama_kelas)
@section('css')
    <style>
        .list-materi:hover {
            background-color: whitesmoke
        }

        .list-group-item {
            cursor: pointer;
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
                        <!-- Modal or Iframe content will be loaded here -->
                        <div id="iframeContent"></div>
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
                                <a href="#" id="mulaiBelajarBtn" class="btn btn-primary btn-round btn-sm me-2">
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
                                        Materi</span>
                                </div>
                            </div>
                            <div class="progress-card mb-0 mt-2">
                                <div class="progress-status">
                                    <span class="text-muted">Progress</span>
                                    <span class="text-muted fw-bold"> 30%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 30%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="" data-original-title="60%"></div>
                                </div>
                            </div>
                        </div>
<div class="accordion accordion-flush" id="modulAccordion">
    @foreach ($course->modul as $index => $modul)
        <div class="card mb-2 border rounded m-2">
            <div class="card-header" onclick="updateIframeSource('{{ $modul->tipe_media }}', '{{ $modul->url_media_link }}')" id="heading{{ $index }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                <div class="span-title">
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
                    {{ $modul->nama_modul }}
                </div>
                <div class="span-mode"></div>
            </div>

            <div id="collapse{{ $index }}" class="collapse show" aria-labelledby="heading{{ $index }}" data-bs-parent="#modulAccordion">
                <div class="card-body py-2 px-4">
                    {{-- Sub-items for Quiz and Essay --}}
                    <ul class="list-unstyled">
                        @if ($modul->quizzes->isNotEmpty())
                            <li class="list-group-item mb-2 bg-body p-2 border rounded" onclick="loadQuiz({{ $modul->quizzes->first()->id }})"><span class="me-2 bg-primary px-1 py-1 my-auto rounded text-white">
                                    <i class="far fa-comment-dots"></i></span> Quiz</li>
                        @endif
                        @if ($modul->essays->isNotEmpty())
                            <li class="list-group-item bg-body p-2 border rounded" onclick="loadEssay({{ $modul->essays->first()->id }})"><span class="me-2 bg-warning px-1 py-1 my-auto rounded text-white">
                                    <i class="far fa-file-alt"></i></span> Essay</li>
                        @endif
                    </ul>
                    {{-- Media Link --}}
                    {{-- <a href="{{ $modul->url_media_link }}" class="btn btn-link" target="_blank">Open Media</a> --}}
                </div>
            </div>
        </div>
    @endforeach
</div>



                    </div>
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
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    {{-- <script>
        CKEDITOR.replace('essayFrame');
    </script> --}}
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
    </script>

<script>
// Function to handle displaying iframe based on media type
function updateIframeSource(mediaType, mediaLink, index) {
    const iframe = document.getElementById("videoSource");
    const iframeContent = document.getElementById("iframeContent");
    const ratio = document.querySelector('.ratio'); // Class ratio to be toggled

    // Hide the ratio and iframe content when Quiz or Essay is clicked
    ratio.style.display = "none";
    iframe.style.display = "none";
    iframeContent.innerHTML = ""; // Clear iframe content

    // Show the iframe when a media type is clicked from card header (video/pdf/link)
    if (mediaType === 'video' || mediaType === 'pdf') {
        ratio.style.display = "block"; // Show ratio class (iframe container)
        iframe.style.display = "block"; // Show iframe
        iframe.src = mediaLink;
    } else if (mediaType === 'link') {
        ratio.style.display = "block"; // Show ratio class (iframe container)
        iframe.style.display = "block"; // Show iframe
        iframe.src = mediaLink;
    }
}
</script>

<script>
function loadQuiz(quizId) {
    // Clear iframe and hide ratio
    const iframe = document.getElementById("videoSource");
    const ratio = document.querySelector('.ratio');
    iframe.style.display = "none";
    ratio.style.display = "none";

    // Fetch quiz data from the backend using the quiz ID
    fetch(`/course/quiz/${quizId}`)
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message); // In case of an error
                return;
            }

            // Log data to check quizIds
            console.log("Received data:", data);

            const iframeContent = `
                <div class="">
                    <div class="card-header">
                        <p class="fw-bold">Pertanyaan ${data.quizIndex}:</p>
                        <p class="mt-2">${data.question}</p>
                    </div>
                    <div class="card-body">
                        <p class="fw-bold">Jawaban:</p>
                        <form>
                            ${data.kunci_jawaban.map((answer, index) => `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" id="answer${index + 1}" value="${answer}">
                                    <label class="form-check-label" for="answer${index + 1}">${answer}</label>
                                </div>
                            `).join('')}
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        Pilih salah satu jawaban di atas.
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        ${generateQuestionNav(data.quizIds, quizId)} <!-- Passing quizIds array -->
                    </div>
                </div>
            `;
            document.getElementById('iframeContent').innerHTML = iframeContent;
        })
        .catch(err => console.error('Error loading quiz:', err));
}

function generateQuestionNav(quizIds, currentQuizId) {
    // Check if quizIds is an array
    if (!Array.isArray(quizIds)) {
        console.error("quizIds is not an array:", quizIds);
        return;
    }

    // Create buttons for each quiz ID
    let buttons = '';
    quizIds.forEach((id, index) => {
        buttons += `
            <button class="btn btn-outline-primary mx-1" onclick="loadQuiz(${id})">
                ${index + 1} <!-- Increment button number starting from 1 -->
            </button>
        `;
    });
    return buttons;
}


    function loadEssay(essayId) {
    // Clear iframe and hide ratio
    const iframe = document.getElementById("videoSource");
    const ratio = document.querySelector('.ratio');
    iframe.style.display = "none";
    ratio.style.display = "none";
    
        // Fetch essay data from the backend using the essay ID
        fetch(`/course/essay/${essayId}`)
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message); // In case of an error
                    return;
                }

                const iframeContent = `
                    <div class="">
                        <div class="card-header">
                            <p class="fw-bold">Pertanyaan:</p>
                            <p class="mt-2">${data.question}</p>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="essayFrame" rows="3" required></textarea>
                        </div>
                        <div class="card-footer text-muted">
                        </div>
                    </div>
                `;
                document.getElementById('iframeContent').innerHTML = iframeContent;

                // Initialize CKEditor after the content has been loaded into the DOM
            CKEDITOR.replace('essayFrame');
            })
            .catch(err => console.error('Error loading essay:', err));
    }
</script>


@endsection
