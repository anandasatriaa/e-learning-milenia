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

        .quiz-nav {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            /* Maksimal 5 kolom per baris */
            gap: 10px;
            /* Jarak antar tombol */
            justify-content: center;
        }

        .bg-danger-light {
            background-color: #ffe6e6;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            max-width: 100%;
            height: auto;
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
                            <div class="card-tools">
                                <button type="button" class="btn btn-label-info btn-round btn-sm me-2" id="goToLastAccess">
                                    Last access : <span id="lastAccess">00:00</span> <i class="fas fa-history ms-1"></i>
                                </button>
                                <button id="selesaiKelas" class="btn btn-danger btn-round btn-sm me-2">
                                    Submit & End Course <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button id="previewReviewBtn" class="btn btn-info btn-round btn-sm me-2 d-none">
                                    Preview Jawaban & Nilai <i class="fas fa-eye ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card bg-light-subtle rounded-end-4 rounded-start-0 h-100 shadow-none ">
                        <div class="card-header">
                            <div class="text-center border rounded">
                                <div class="card-title">
                                    <i class="fas fa-clock align-middle me-1"></i>
                                    <span class="align-middle" id="time">Time Spend: 00:00:00</span>
                                </div>
                            </div>
                            <div class="card-head-row">
                                <div class="card-title"><i class="icon-grid align-middle me-1"></i><span
                                        class="align-middle">
                                        Materi</span>
                                </div>
                            </div>
                            <div class="progress-card mb-0 mt-2">
                                <div class="progress-status">
                                    <span class="text-muted">Progress</span>
                                    <span class="text-muted fw-bold"> 0%</span> <!-- Progress text -->
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                        style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion accordion-flush" id="modulAccordion">
                            <!-- Introduction Card -->
                            <div class="card mb-2 border rounded m-2" id="introductionCard">
                                <div class="card-header" onclick="loadIntroductionVideo()">
                                    <div class="span-title">
                                        <span class="me-2 bg-success px-2 py-1 my-auto rounded text-white">
                                            <i class="fas fa-play-circle"></i></span>
                                        Introduction
                                    </div>
                                </div>
                            </div>

                            <!-- Course Modules -->
                            @foreach ($course->modul as $index => $modul)
                                <div class="card mb-2 border rounded m-2">
                                    <div class="card-header collapsed"
                                        onclick="updateIframeSource('{{ $modul->tipe_media }}', '{{ $modul->url_media_link }}', {{ $index }})"
                                        id="heading{{ $index }}" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                                        aria-controls="collapse{{ $index }}">
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
                                            @endswitch
                                            {{ $modul->nama_modul }}
                                        </div>
                                        <div class="span-mode"></div>
                                    </div>

                                    <div id="collapse{{ $index }}" class="collapse"
                                        aria-labelledby="heading{{ $index }}" data-bs-parent="#modulAccordion">
                                        <div class="card-body py-2 px-4">
                                            {{-- Sub-items for Quiz and Essay --}}
                                            <ul class="list-unstyled">
                                                @if ($modul->quizzes->isNotEmpty())
                                                    {{ Log::info('Log modul quizzes' . $modul->quizzes->first()->course_modul_id) }}
                                                    <li class="list-group-item mb-2 bg-body p-2 border rounded"
                                                        onclick="loadQuiz({{ $modul->quizzes->first()->course_modul_id }})">
                                                        <span class="me-2 bg-primary px-1 py-1 my-auto rounded text-white">
                                                            <i class="far fa-comment-dots"></i></span> Quiz
                                                    </li>
                                                @endif
                                                @if ($modul->essays->isNotEmpty())
                                                    <li class="list-group-item bg-body p-2 border rounded"
                                                        onclick="loadEssay({{ $modul->essays->first()->course_modul_id }})">
                                                        <span class="me-2 bg-warning px-1 py-1 my-auto rounded text-white">
                                                            <i class="far fa-file-alt"></i></span> Essay
                                                    </li>
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
                        <img class="img-thumbnail rounded" src="{{ $course->thumbnail_url }}"
                            alt="{{ $course->thumbnail }}" width="200px">
                    </div>
                    <div class="col-12 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="mb-1">
                                    <span class="text-muted">Learning</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->learningCategory->nama ?? '-' }}</h6>
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted">Divisi</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->divisiCategory->nama ?? '-' }}</h6>
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted">Kategori</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->category->nama ?? '-' }}</h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted">Sub Kategori</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $course->subCategory->nama ?? '-' }}</h6>
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

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-clipboard2-check-fill me-2 text-primary"></i>
                        Review Hasil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Score Summary -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Nilai Quiz:
                                    <span id="quizScore" class="fw-bold"></span>
                                </h5>
                                <small class="text-muted">Berdasarkan hasil quiz dan partisipasi</small>
                            </div>
                            <div class="text-end">
                                <div class="h4 mb-0" id="correctCount"></div>
                                <small class="text-muted">Jawaban Benar</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div id="scoreProgress" class="progress-bar bg-success" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- Quiz Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between">
                            <h6 class="mb-0">
                                <i class="bi bi-question-circle me-2"></i>
                                Review Quiz
                            </h6>
                            <span class="badge bg-light text-primary" id="quizStats"></span>
                        </div>
                        <div class="card-body">
                            <div id="quizAccordion" class="accordion">
                                <!-- Quiz items will be inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Essay Section -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-pencil-square me-2"></i>
                                Review Essay
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="essayReviewBody">
                                <!-- Essay items will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check2 me-2"></i>Tutup Review
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    {{-- MENGAMBIL USER ID YANG SEDANG LOGIN --}}
    <script>
        window.CURRENT_USER_ID = {{ auth()->id() }};
        console.log('User ID yang sedang login:', window.CURRENT_USER_ID);
    </script>

    {{-- MENGAMBIL DATA COURSE MODULE --}}
    <script>
        const courseModules = @json($courseModules);
        console.log('Data courseModules:', courseModules);
    </script>

    {{-- VIDEO --}}
    <script>
        let currentVideoSuffix = null; // akan di‐set tiap loadVideo dipanggil

        // Format mm:ss
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${String(minutes).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
        }

        // Array untuk menyimpan semua suffix video yang pernah dibuka
        window.loadedVideoSuffixes = [];

        // Fungsi generik untuk load video & track position
        function loadVideo(mediaLink, videoKeySuffix) {
            // simpan suffix ini jika belum ada
            if (!window.loadedVideoSuffixes.includes(videoKeySuffix)) {
                window.loadedVideoSuffixes.push(videoKeySuffix);
            }

            const userId = window.CURRENT_USER_ID;
            const courseId = "{{ $course->id }}";
            const iframe = document.getElementById("videoSource");
            const ratio = document.querySelector('.ratio');
            const lastAccessEl = document.getElementById('lastAccess');

            // Update suffix global untuk goToLastAccess
            currentVideoSuffix = videoKeySuffix;

            const keyTime = `videoCurrentTime_${userId}_${courseId}_${videoKeySuffix}`;
            const keyDur = `videoDuration_${userId}_${courseId}_${videoKeySuffix}`;

            // Tampilkan iframe
            ratio.style.display = 'block';
            iframe.style.display = 'block';
            iframe.src = mediaLink;

            // Pastikan hanya sekali listen load
            iframe.onload = () => {
                const doc = iframe.contentWindow.document;
                const interval = setInterval(() => {
                    const video = doc.querySelector('video');
                    if (!video) return;
                    clearInterval(interval);

                    // 🔥 Langsung simpan durasi begitu video tersedia
                    const dur = video.duration;
                    if (!isNaN(dur) && dur > 0) {
                        localStorage.setItem(keyDur, dur);
                    }

                    // Restore last time
                    let lastTime = parseFloat(localStorage.getItem(keyTime)) || 0;
                    video.currentTime = lastTime;
                    lastAccessEl.textContent = formatTime(lastTime);

                    // Simpan duration
                    video.addEventListener('loadedmetadata', () => {
                        const d2 = video.duration;
                        if (!isNaN(d2) && d2 > 0) {
                            localStorage.setItem(keyDur, d2);
                        }
                    });

                    // Update time
                    video.addEventListener('timeupdate', () => {
                        if (!video.seeking && video.currentTime > lastTime) {
                            lastTime = video.currentTime;
                            localStorage.setItem(keyTime, lastTime);
                            lastAccessEl.textContent = formatTime(lastTime);
                        }
                    });

                    // Cegah seek di luar batas
                    video.addEventListener('seeking', () => {
                        if (video.currentTime > lastTime) {
                            video.currentTime = lastTime;
                        }
                    });

                    // Jika selesai
                    video.addEventListener('ended', () => {
                        localStorage.setItem(keyTime, video.duration);
                    });
                }, 200);
            };
        }

        // Panggil loadVideo untuk video pengantar (jika butuh)
        function loadIntroductionVideo() {
            const courseId = "{{ $course->id }}";
            const iframeContent = document.getElementById("iframeContent");
            const ratio = document.querySelector('.ratio');
            const iframe = document.getElementById("videoSource");

            // Kosongkan konten sebelumnya (quiz/essay)
            iframeContent.innerHTML = "";

            // Tampilkan elemen video
            ratio.style.display = 'block';
            iframe.style.display = 'block';

            // Load video pengantar
            loadVideo("{{ url('/course/embed-video/') }}/" + courseId, 'intro');
            setActiveModule(0);

            const introCard = document.getElementById('introductionCard');
            if (introCard) {
                highlightCard(introCard);
            }

            // Hapus highlight dari item quiz/essay sebelumnya
            document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('border-primary'));
        }

        // updateIframeSource dengan support video/pdf/link
        function updateIframeSource(mediaType, mediaLink, index) {
            const iframe = document.getElementById("videoSource");
            const iframeContent = document.getElementById("iframeContent");
            const ratio = document.querySelector('.ratio');

            // sembunyikan dulu
            ratio.style.display = 'none';
            iframe.style.display = 'none';
            iframeContent.innerHTML = '';

            if (mediaType === 'video') {
                // suffix pakai index
                loadVideo(mediaLink, index);
            } else {
                // pdf atau link
                ratio.style.display = 'block';
                iframe.style.display = 'block';
                iframe.src = mediaLink;
            }

            setActiveModule(index + 1);
            highlightCard();
        }

        // Highlight card aktif
        function highlightCard(element = null) {
            document.querySelectorAll('.card').forEach(c => c.classList.remove('border-primary'));

            // Jika elemen diberikan langsung (bukan dari event)
            if (element) {
                element.classList.add('border-primary');
            } else if (event?.target) {
                const card = event.target.closest('.card');
                if (card) card.classList.add('border-primary');
            }
        }

        // Tombol Go To Last Access
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('goToLastAccess').addEventListener('click', () => {
                if (currentVideoSuffix === null) return;
                const userId = window.CURRENT_USER_ID;
                const courseId = "{{ $course->id }}";
                const keyTime = `videoCurrentTime_${userId}_${courseId}_${currentVideoSuffix}`;
                const lastTime = parseFloat(localStorage.getItem(keyTime)) || 0;
                const iframe = document.getElementById("videoSource");
                const vid = iframe.contentWindow?.document.querySelector('video');
                if (vid) vid.currentTime = lastTime;
            });
        });
    </script>

    {{-- QUIZ --}}
    <script>
        function loadQuiz(courseModulId) {
            // Clear iframe and hide ratio
            const iframe = document.getElementById("videoSource");
            const ratio = document.querySelector('.ratio');
            iframe.style.display = "none";
            ratio.style.display = "none";

            // Fetch quiz data from the backend using the course module ID
            fetch("{{ url('course/quiz') }}/" + courseModulId)
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message); // In case of an error
                        return;
                    }

                    quizIds = data.quizIds;
                    console.log('data quizIds:', quizIds);

                    const iframeContent = `
                        <div class="container mt-4">
                            <div class="text-center mb-4">
                                <h3 class="text-primary fw-bold">Quiz Time!</h3>
                                <img src="{{ asset('img/quiz-time.svg') }}" alt="Quiz Time" class="img-fluid my-3" style="max-width: 200px;">
                                <p class="text-muted mb-1">Please click the number button below to start the quiz!</p>
                                <p class="fw-bold mb-0">Total Quiz: <span class="text-primary">${data.totalQuizzes}</span></p>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                ${generateQuestionNav(data.quizIds, courseModulId)} <!-- Passing quizIds array -->
                            </div>
                        </div>
                    `;

                    document.getElementById('iframeContent').innerHTML = iframeContent;




                    // Update the active button and color
                    updateQuestionNavState(data.quizIds, courseModulId);
                })
                .catch(err => console.error('Error loading quiz:', err));

            // Get the clicked Quiz item and add border-primary
            var quizItem = event.target.closest('li');
            quizItem.classList.add('border-primary');

            // Remove primary border from all other Essay and Quiz items
            var allItems = document.querySelectorAll('.list-group-item');
            allItems.forEach(function(item) {
                if (item !== quizItem) {
                    item.classList.remove('border-primary');
                }
            });
        }
        // 1️⃣ Definisikan key namespaced sekali di top-level
        const QUIZ_STORAGE_KEY = `userAnswers_${window.CURRENT_USER_ID}`;

        // Tidak perlu var userAnswers global lagi, kita baca langsung dari storage

        // 2️⃣ Load & apply semua jawaban yang sudah disimpan
        function loadAnswers() {
            const saved = JSON.parse(localStorage.getItem(QUIZ_STORAGE_KEY)) || {};
            Object.entries(saved).forEach(([modulId, {
                answer
            }]) => {
                const radio = document.querySelector(`input[name="answer"][value="${answer}"]`);
                if (radio) radio.checked = true;
            });
        }

        // 3️⃣ Simpan jawaban ke storage namespaced
        function saveAnswer(courseModulId, answer) {
            const all = JSON.parse(localStorage.getItem(QUIZ_STORAGE_KEY)) || {};
            const radios = document.getElementsByName('answer');
            const idx = Array.from(radios).findIndex(r => r.value === answer && r.checked) + 1;

            all[courseModulId] = {
                answer,
                course_modul_id: courseModulId,
                kode_jawaban: idx.toString()
            };
            localStorage.setItem(QUIZ_STORAGE_KEY, JSON.stringify(all));

            updateQuestionNavState(window.currentQuizIds, courseModulId);
        }

        // 4️⃣ Update warna tombol nav sesuai storage terkini
        function updateQuestionNavState(quizIds, currentQuizId) {
            const saved = JSON.parse(localStorage.getItem(QUIZ_STORAGE_KEY)) || {};
            quizIds.forEach(id => {
                const btn = document.getElementById(`quizButton-${id}`);
                if (!btn) return;
                btn.classList.toggle('btn-primary', id === currentQuizId);
                btn.classList.toggle('btn-outline-primary', id !== currentQuizId);
                btn.classList.toggle('btn-info', Boolean(saved[id]));
            });
        }

        // 5️⃣ Fetch & render soal + aplikasikan jawaban yang tersimpan
        function getQuiz(event, courseModulId) {
            fetch("{{ url('course/getQuiz') }}/" + courseModulId)
                .then(r => r.json())
                .then(data => {
                    if (data.message) return alert(data.message);

                    window.currentQuizIds = data.quizIds;
                    const saved = JSON.parse(localStorage.getItem(QUIZ_STORAGE_KEY)) || {};
                    const userAnswer = data.userAnswer || saved[courseModulId]?.answer;

                    // bangun HTML dengan ID unik per modul
                    let formHtml = data.kunci_jawaban.map((ans, i) => {
                        const uid = `ans-${courseModulId}-${i}`;
                        const checked = userAnswer === ans ? 'checked' : '';
                        const label = ans.startsWith('storage/') ?
                            `<img src="{{ asset('${ans}') }}" style="width:50%;">` :
                            ans;
                        return `
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="answer"
                                   id="${uid}"
                                   value="${ans}"
                                   ${checked}
                                   onclick="saveAnswer(${courseModulId}, '${ans}')">
                            <label class="form-check-label" for="${uid}">${label}</label>
                        </div>`;
                    }).join('');

                    const html = `
                    <div class="card">
                        <div class="card-header">
                            <p class="fw-bold">Pertanyaan ${data.quizIndex}:</p>
                            <p>${data.question}</p>
                        </div>
                        <div class="card-body"><form>${formHtml}</form></div>
                        <div class="card-footer text-center">
                            ${generateQuestionNav(data.quizIds, courseModulId)}
                        </div>
                    </div>`;

                    document.getElementById('iframeContent').innerHTML = html;

                    // setelah inject HTML, pastikan tombol nav dan padyjawaban ter-update
                    updateQuestionNavState(data.quizIds, courseModulId);
                })
                .catch(console.error);

            // highlight sidebar
            document.querySelectorAll('.list-group-item').forEach(li => li.classList.remove('border-primary'));
            event.target.closest('li')?.classList.add('border-primary');
        }

        // generate nav button
        function generateQuestionNav(quizIds, currentQuizId) {
            return quizIds.map((id, idx) =>
                `<button id="quizButton-${id}"
                     class="btn btn-outline-primary mx-1"
                     onclick="getQuiz(event, ${id})">
                 ${idx+1}
             </button>`
            ).join('');
        }

        // inisialisasi pas load page pertama
        document.addEventListener('DOMContentLoaded', loadAnswers);
    </script>

    {{-- ESSAY --}}
    <script>
        function loadEssay(courseModulId) {
            // Clear iframe and hide ratio
            const iframe = document.getElementById("videoSource");
            const ratio = document.querySelector('.ratio');
            iframe.style.display = "none";
            ratio.style.display = "none";

            // Fetch essay data from the backend using the course module ID
            fetch("{{ url('course/essay') }}/" + courseModulId)
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message); // In case of an error
                        return;
                    }

                    essayIds = data.essayIds;
                    console.log('data essayIds:', essayIds);

                    // Combine all questions into a single list
                    const questionsList = data.questions.map((question, index) => `
                        <div class="mb-3">
                            <p class="mb-0"><strong>${index + 1}. </strong> ${question.question}</p>
                            ${question.image ? `<img src="${question.image}" alt="Question Image" class="ms-3" style="width: 50%;" onclick="enlargeImageEssay(this)" />` : ''}
                        </div>
                    `).join('');

                    // Create a container for all questions
                    const iframeContent = `
                        <div class="">
                            <div class="card-header">
                                <p><strong>Pertanyaan:</strong></p>
                                <div>${questionsList}</div>
                                <span class="text-muted">**untuk melihat gambar lebih jelas, klik gambar</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Jawab:</strong></p>
                                <textarea class="form-control essay-textarea" id="essayFrame-${courseModulId}" rows="6" required></textarea>
                            </div>
                        </div>

                        <!-- Modal for Enlarged Image -->
                        <div class="modal fade" id="imageModalEssay" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <img id="modalImageEssay" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Insert the content into the iframeContent container
                    document.getElementById('iframeContent').innerHTML = iframeContent;

                    // Initialize CKEditor for each textarea
                    CKEDITOR.replace(`essayFrame-${courseModulId}`);

                    const ESSAY_STORAGE_KEY = `essayAnswer_${window.CURRENT_USER_ID}_${courseModulId}`;
                    const savedAnswer = localStorage.getItem(ESSAY_STORAGE_KEY);
                    const answerFromDb = data.answer;
                    const finalAnswer = answerFromDb || savedAnswer;

                    if (finalAnswer) {
                        CKEDITOR.instances[`essayFrame-${courseModulId}`].setData(finalAnswer);
                    }

                    CKEDITOR.instances[`essayFrame-${courseModulId}`].on('change', function() {
                        const currentContent = this.getData();
                        localStorage.setItem(ESSAY_STORAGE_KEY, currentContent);
                    });

                })
                .catch(err => console.error('Error loading essay:', err));

            // Get the clicked Essay item and add border-primary
            var essayItem = event.target.closest('li');
            essayItem.classList.add('border-primary');

            // Remove primary border from all other Essay and Quiz items
            var allItems = document.querySelectorAll('.list-group-item');
            allItems.forEach(function(item) {
                if (item !== essayItem) {
                    item.classList.remove('border-primary');
                }
            });
        }
    </script>

    {{-- POST JAWABAN KE DATABASE --}}
    <script>
        document.getElementById('selesaiKelas').addEventListener('click', async function(e) {
            e.preventDefault();

            const userId = @json(auth()->user()->ID);
            const courseId = @json($course->id);
            const today = new Date().toISOString().split('T')[0];

            // 1️⃣ Ambil Time Spend dari key namespaced
            const timeKey = `timeElapsed_user_${userId}_course_${courseId}`;
            const timeSpend = parseInt(localStorage.getItem(timeKey), 10) || 0;

            // 2️⃣ Ambil Progress Bar dari key namespaced
            const progressKey = `progress_user_${userId}_course_${courseId}`;
            const progressBar = parseInt(localStorage.getItem(progressKey), 10) || 0;

            // 3️⃣ Ambil jawaban Quiz dari key namespaced
            const quizKey = `userAnswers_${userId}`;
            const userAnswers = JSON.parse(localStorage.getItem(quizKey)) || {};

            // 4️⃣ Ambil jawaban Essay yang namespaced per-modul
            const essayPrefix = `essayAnswer_${userId}_`;
            const essayKeys = Object.keys(localStorage).filter(k => k.startsWith(essayPrefix));

            // 5️⃣ (Optional) Ambil last video watch & duration jika perlu
            const videoTimeKey = `videoCurrentTime_${userId}_${courseId}_${currentVideoSuffix}`;
            const videoDurKey = `videoDuration_${userId}_${courseId}_${currentVideoSuffix}`;
            const watched = parseFloat(localStorage.getItem(videoTimeKey)) || 0;
            const duration = parseFloat(localStorage.getItem(videoDurKey)) || 0;
            const hasUnwatched = window.loadedVideoSuffixes.some(suffix => {
                const keyTime = `videoCurrentTime_${userId}_${courseId}_${suffix}`;
                const keyDur = `videoDuration_${userId}_${courseId}_${suffix}`;
                const watched = parseFloat(localStorage.getItem(keyTime)) || 0;
                const duration = parseFloat(localStorage.getItem(keyDur));

                console.log(`VIDEO[${suffix}] → watched=${watched}, duration=${duration}`); // debug

                // jika durasi tidak valid (belum diset) atau belum tuntas → unwatched
                if (!duration || isNaN(duration)) return true;
                return watched < duration;
            });

            // 6️⃣ Cek incomplete items
            const hasUnansweredQuiz = courseModules.some(mod =>
                mod.quizIds.some(qid => !userAnswers[qid])
            );
            const hasUnfilledEssay = courseModules.some(mod =>
                mod.essayIds.some(eid => {
                    const ans = localStorage.getItem(`${essayPrefix}${eid}`);
                    return !ans || !ans.trim();
                })
            );

            if (hasUnwatched || hasUnansweredQuiz || hasUnfilledEssay) {
                let msg = "Anda belum:\n";
                if (hasUnwatched) msg += "- Menonton video hingga akhir\n";
                if (hasUnansweredQuiz) msg += "- Menjawab seluruh quiz\n";
                if (hasUnfilledEssay) msg += "- Mengisi seluruh jawaban essay\n";
                return swal("Peringatan!", msg, "warning");
            }

            // 7️⃣ Pastikan progress = 100% lalu submit quiz
            currentModule = totalModules;
            updateProgress();

            // 8️⃣ Kirim Quiz
            for (let modulQuizzesId in userAnswers) {
                const {
                    answer,
                    kode_jawaban
                } = userAnswers[modulQuizzesId];
                await fetch(`{{ url('course/quiz') }}/${modulQuizzesId}/submit/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        modul_quizzes_id: modulQuizzesId,
                        jawaban: answer,
                        kode_jawaban
                    })
                });
            }

            // 9️⃣ Kirim Essay
            for (let key of essayKeys) {
                const eid = key.replace(essayPrefix, '');
                const jaw = localStorage.getItem(key);
                await fetch(`{{ url('course/essay') }}/${eid}/submit/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        course_modul_id: eid,
                        jawaban: jaw
                    })
                });
            }

            // 10️⃣ Kirim summary Course
            await fetch("{{ url('course/update-course-enrolls') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    course_id: courseId,
                    finish_date: today,
                    status: 'completed',
                    time_spend: timeSpend,
                    progress_bar: 100
                })
            });

            await swal("Jawaban berhasil dikumpulkan!", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: 'btn btn-success'
                    }
                }
            });
            showReviewModal();
        });
    </script>

    {{-- TIME SPEND --}}
    <script>
        // Format hh:mm:ss
        function formatTimeSpend(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${String(hours).padStart(2,'0')}:` +
                `${String(minutes).padStart(2,'0')}:` +
                `${String(secs).padStart(2,'0')}`;
        }

        let timeElapsed = 0;
        let timer = null;

        // Ambil courseId & userId
        const urlPath = window.location.pathname;
        const courseIdMatch = urlPath.match(/\/course\/(\d+)/);
        const courseId = courseIdMatch ? courseIdMatch[1] : 'default';
        const userId = window.CURRENT_USER_ID;

        // Key storage namespaced per user & course
        const timeStorageKey = `timeElapsed_user_${userId}_course_${courseId}`;

        async function updateTimeSpendToDatabase() {
            const response = await fetch("{{ url('course/post-time-spend-and-progress-bar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    course_id: courseId,
                    user_id: userId,
                    time_spend: timeElapsed,
                    progress_bar: Math.floor((currentModule / totalModules) * 100)
                })
            });
            const result = await response.json();
            if (result.status !== 'success') {
                console.error('Failed to update time spend:', result.message);
            }
        }

        async function fetchTimeSpendFromDatabase() {
            const url = "{{ url('course/get-time-spend-and-progress-bar') }}/" + courseId + "/" + userId;
            const response = await fetch(url);
            const data = await response.json();
            if (data?.time_spend != null) {
                timeElapsed = data.time_spend;
            } else {
                const stored = localStorage.getItem(timeStorageKey);
                if (stored) timeElapsed = parseInt(stored, 10);
            }
        }

        function startTimer() {
            if (!timer) {
                timer = setInterval(() => {
                    timeElapsed++;
                    document.getElementById('time').innerText = `Time Spend: ${formatTimeSpend(timeElapsed)}`;
                    localStorage.setItem(timeStorageKey, timeElapsed);
                }, 1000);
            }
        }

        function stopTimer() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
            localStorage.setItem(timeStorageKey, timeElapsed);
            updateTimeSpendToDatabase();
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible' && currentModule < totalModules) {
                startTimer();
            } else {
                stopTimer();
            }
        });

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchTimeSpendFromDatabase();
            document.getElementById('time').innerText = `Time Spend: ${formatTimeSpend(timeElapsed)}`;
            if (currentModule < totalModules) startTimer();
        });
    </script>

    {{-- PRGRESS BAR --}}
    <script>
        // Ambil total modul & inisialisasi
        const totalModules = {{ $course->modul->count() + 1 }};
        let currentModule = 0;
        let courseStatus = null;
        const progressKey = `progress_user_${userId}_course_${courseId}`;

        async function updateProgressToDatabase() {
            if (courseStatus === 'completed') return;
            const progressPercent = Math.floor((currentModule / totalModules) * 100);
            const response = await fetch("{{ url('course/post-time-spend-and-progress-bar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    course_id: courseId,
                    user_id: userId,
                    time_spend: timeElapsed,
                    progress_bar: progressPercent
                })
            });
            const result = await response.json();
            if (result.status !== 'success') {
                console.error('Failed to update progress bar:', result.message);
            }
        }

        function updateProgress() {
            const saved = parseInt(localStorage.getItem(progressKey)) || 0;
            let pct;

            if (saved >= 100) {
                pct = 100;
            } else {
                pct = Math.floor((currentModule / totalModules) * 100);
                if (pct > 100) pct = 100;
                localStorage.setItem(progressKey, pct);
            }

            document.querySelector('.progress-bar').style.width = `${pct}%`;
            document.querySelector('.progress-status span.fw-bold').innerText = `${pct}%`;

            const selesaiBtn = document.getElementById('selesaiKelas');
            const previewBtn = document.getElementById('previewReviewBtn');

            if (pct === 100) {
                // Berhenti timer saat progress 100%
                stopTimer();

                selesaiBtn.disabled = true;
                previewBtn.classList.remove('d-none');
            } else {
                selesaiBtn.disabled = false;
                previewBtn.classList.add('d-none');
            }

            updateProgressToDatabase();
        }

        function setActiveModule(index) {
            currentModule = index;
            updateProgress();
        }

        async function fetchProgressFromDatabase() {
            const url = "{{ url('course/get-time-spend-and-progress-bar') }}/" + courseId + "/" + userId;
            const response = await fetch(url);
            const data = await response.json();
            if (data) {
                if (data.status === 'completed') {
                    courseStatus = 'completed';
                    currentModule = totalModules;
                } else if (data.progress_bar != null) {
                    currentModule = Math.floor((data.progress_bar / 100) * totalModules);
                } else {
                    const saved = parseInt(localStorage.getItem(progressKey)) || 0;
                    currentModule = Math.floor((saved / 100) * totalModules);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await fetchProgressFromDatabase();
            updateProgress();

            loadIntroductionVideo();
        });
    </script>

    {{-- KETIKA DIKLIK IMAGE MEMBESAR --}}
    <script>
        function enlargeImage(imgElement) {
            const modalImage = document.getElementById("modalImage");
            modalImage.src = imgElement.src;
            const modal = new bootstrap.Modal(document.getElementById("imageModal"));
            modal.show();
        }

        function enlargeImageEssay(imgElement) {
            const modalImage = document.getElementById("modalImageEssay");
            modalImage.src = imgElement.src;
            const modal = new bootstrap.Modal(document.getElementById("imageModalEssay"));
            modal.show();
        }
    </script>

    {{-- FUNCTION PREVIEW DAN BUTTON NYA --}}
    <script>
        // 1) Buat fungsi umum untuk fetch & render modal review
        function showReviewModal() {
            fetch("{{ route('pages.course.course.review', $course->id) }}")
                .then(res => res.json())
                .then(data => {
                    // helper buat URL gambar
                    const makeSrc = path => {
                        if (!path) return '';
                        if (/^https?:\/\//.test(path)) return path;
                        return `${window.location.origin}/storage/${path.replace(/^\/?storage\//,'')}`;
                    };

                    // --- Render summary, progress, stats seperti biasa ---
                    document.getElementById('quizScore').innerHTML = `
                    <span class="text-success">${data.score}</span>
                    <small class="text-muted"> dari </small>
                    <span class="text-primary">${data.maxScore}</span>`;
                    const progressPercent = (data.score / data.maxScore) * 100;
                    const pb = document.getElementById('scoreProgress');
                    pb.style.width = `${progressPercent}%`;
                    pb.setAttribute('aria-valuenow', Math.round(progressPercent));
                    document.getElementById('correctCount').textContent = `${data.correct}/${data.totalQuiz}`;
                    document.getElementById('quizStats').textContent =
                        `${data.correct} Benar • ${data.totalQuiz - data.correct} Salah`;

                    // --- Render quiz accordion ---
                    const qb = document.getElementById('quizAccordion');
                    qb.innerHTML = '';
                    data.quizItems.forEach((item, i) => {
                        const statusClass = item.isCorrect ? 'text-success' : 'text-danger';
                        const statusIcon = item.isCorrect ? 'fas fa-check-circle' : 'fas fa-times-circle';

                        const quizImg = item.image ?
                            `<img src="${makeSrc(item.image)}" class="img-thumbnail mb-2" style="max-height:100px;max-width:100px;">` :
                            '';
                        const ansImg = item.jawabanUser && /\.(png|jpe?g|gif)$/i.test(item.jawabanUser);
                        const answerHtml = ansImg ?
                            `<img src="${makeSrc(item.jawabanUser)}" class="img-thumbnail" style="max-height:100px;max-width:100px;">` :
                            (item.jawabanUser || '<em class="text-muted">Belum dijawab / Tidak ada soal</em>');

                        const optionsHtml = item.options.map((opt, idx) => {
                            // deteksi gambar di opt.image atau opt.pilihan
                            const imgPath = opt.image ?
                                opt.image :
                                (opt.pilihan.startsWith('storage/quiz/answers/') ? opt.pilihan : null);
                            const imgTag = imgPath ?
                                `<img src="${makeSrc(imgPath)}" class="img-thumbnail me-2" style="max-height:50px;max-width:50px;">` :
                                '';
                            const text = imgPath ? '' : opt.pilihan;
                            return `
                            <li class="mb-2 d-flex align-items-center">
                                <span class="me-2">${String.fromCharCode(65+idx)}.</span>
                                ${imgTag}${text}
                            </li>`;
                        }).join('');

                        qb.insertAdjacentHTML('beforeend', `
                            <div class="accordion-item">
                            <h2 class="accordion-header" id="headingQuiz${i}">
                                <button class="accordion-button ${!item.isCorrect?'bg-danger-light':''}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseQuiz${i}">
                                <i class="${statusIcon} me-2 ${statusClass}"></i>
                                Pertanyaan #${i+1}
                                </button>
                            </h2>
                            <div id="collapseQuiz${i}" class="accordion-collapse collapse"
                                aria-labelledby="headingQuiz${i}" data-bs-parent="#quizAccordion">
                                <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-8">
                                    ${quizImg}
                                    <p class="fw-bold">${item.pertanyaan}</p>
                                    <p class="text-muted mb-1">Jawaban Anda:</p>
                                    <div class="alert ${item.isCorrect?'alert-success':'alert-danger'}">
                                        ${answerHtml}
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">Pilihan Jawaban</div>
                                        <div class="card-body">
                                        <ul class="list-unstyled">${optionsHtml}</ul>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        `);
                    });

                    // --- Render essay ---
                    const eb = document.getElementById('essayReviewBody');
                    let qsHTML = '';
                    data.essayItems.forEach((es, i) => {
                        const imgTag = es.image ?
                            `<img src="${makeSrc(es.image)}" class="img-thumbnail mb-2" style="max-height:100px;max-width:100px;">` :
                            '';
                        qsHTML += `
                            <div class="mb-3">
                            <p class="mb-1"><strong>${i+1}.</strong> ${es.pertanyaan}</p>
                            ${imgTag}
                            </div>`;
                    });
                    const allAns = data.essayItems[0]?.jawabanUser ?
                        data.essayItems[0].jawabanUser :
                        '<em class="text-muted">Belum dijawab / Tidak ada soal</em>';
                    eb.innerHTML = `
                        <div class="card mb-3">
                            <div class="card-header fw-bold">Pertanyaan Essay</div>
                            <div class="card-body">${qsHTML}</div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header fw-bold">Jawaban Anda</div>
                            <div class="card-body">
                            <div class="bg-light p-3 rounded">${allAns}</div>
                            </div>
                        </div>`;

                    // tampilkan modal sekali saja
                    new bootstrap.Modal(document.getElementById('reviewModal')).show();
                })
                .catch(err => {
                    console.error(err);
                    swal("Terjadi kesalahan!", "Tidak bisa memuat data review.", "error");
                });
        }

        // 3) Handler untuk tombol Preview
        document.getElementById('previewReviewBtn').addEventListener('click', function(e) {
            e.preventDefault();
            showReviewModal();
        });
    </script>

@endsection
