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
                            {{-- <div class="card-title"> <span class="bg-light p-1 rounded me-1">
                                    <i class="icon-pin"></i></span>
                                Introduction
                            </div> --}}
                            <div class="card-tools">
                                <button type="button" class="btn btn-label-info btn-round btn-sm me-2" id="goToLastAccess">
                                    Last access : <span id="lastAccess">00:00</span> <i class="fas fa-history ms-1"></i>
                                </button>
                                <a href="#" id="selesaiKelas" class="btn btn-danger btn-round btn-sm me-2 disabled">
                                    Submit & End Course <i class="fas fa-arrow-right ms-2"></i>
                                </a>
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
                            <div class="card mb-2 border rounded m-2">
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
@endsection
@section('js')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    <script>
        function loadIntroductionVideo() {
            var course_id = "{{ $course->id }}";
            var videoUrl = "{{ url('/course/embed-video/') }}" + "/" + course_id;
            const iframe = document.getElementById("videoSource");
            const iframeContent = document.getElementById("iframeContent");
            const ratio = document.querySelector('.ratio'); // Class ratio to be toggled

            // Clear existing content and reset iframe
            iframeContent.innerHTML = ""; // Clear iframe content
            iframe.src = videoUrl; // Set the new video URL

            // Ensure the iframe and ratio are displayed
            ratio.style.display = "block"; // Show ratio class (iframe container)
            iframe.style.display = "block"; // Show iframe

            // Change the border to primary for the clicked card
            var card = event.target.closest('.card'); // Get the clicked card
            card.classList.add('border-primary');

            // Remove primary border color from all other cards
            var allCards = document.querySelectorAll('.card');
            allCards.forEach(function(otherCard) {
                if (otherCard !== card) {
                    otherCard.classList.remove('border-primary');
                }
            });

            // Update progress
            setActiveModule(0); // Set progress to the Introduction module (index 0)
        }

        let isVideoCompleted = false; // Status untuk melacak apakah video selesai

        $(document).ready(function() {
            var course_id = "{{ $course->id }}";
            var videoUrl = "{{ url('/course/embed-video/') }}" + "/" + course_id;
            $('#videoSource').attr('src', videoUrl);

            $('#videoSource').on("load", function() {
                const iframeVideo = $(this)[0].contentWindow.document.querySelector('video');
                if (iframeVideo) {
                    iframeVideo.setAttribute("controlslist", "nodownload");
                    iframeVideo.setAttribute("oncontextmenu", "return false");
                    iframeVideo.setAttribute("id", "mainVideo");
                    var supposedCurrentTime = 0;
                    var lastLongAccessTime = 0;

                    // Event listener untuk memperbarui status waktu
                    $(iframeVideo).on('timeupdate', function() {
                        if (!iframeVideo.seeking) {
                            supposedCurrentTime = iframeVideo.currentTime;
                            if (lastLongAccessTime < iframeVideo.currentTime) {
                                lastLongAccessTime = iframeVideo.currentTime;
                            }
                        }
                        $('#lastAccess').text(formatTime(lastLongAccessTime));
                    });

                    // Mencegah video di-seek ke depan
                    $(iframeVideo).on('seeking', function() {
                        var delta = iframeVideo.currentTime - supposedCurrentTime;
                        if (delta > 0 && iframeVideo.currentTime > lastLongAccessTime) {
                            iframeVideo.currentTime = supposedCurrentTime;
                        } else {
                            supposedCurrentTime = iframeVideo.currentTime;
                        }
                    });

                    // Perbarui status saat video selesai
                    $(iframeVideo).on('ended', function() {
                        supposedCurrentTime = 0;
                        isVideoCompleted = true; // Tandai video selesai
                        console.log("Video selesai diputar.");
                        validateCompletion(courseModules); // Periksa ulang validasi tombol
                    });
                }
            });
        });

        function validateCompletion(courseModules) {
            console.log('Validasi mulai dengan modul:', courseModules);

            const hasQuizzesOrEssays = courseModules.some(modul => modul.quizIds.length > 0 || modul.essayIds.length > 0);

            if (!hasQuizzesOrEssays && isVideoCompleted) {
                activateSelesaiKelasButton();
                console.log('Tidak ada kuis atau esai dan video selesai. Tombol Selesai Kelas diaktifkan.');
                return;
            }

            const allModulesComplete = courseModules.every(modul => {
                const allQuizzesAnswered = modul.quizIds.every(quizId => userAnswers[quizId]);
                const allEssaysAnswered = modul.essayIds.every(courseModulId => {
                    const essayAnswer = localStorage.getItem(`essayAnswer-${courseModulId}`);
                    return essayAnswer && essayAnswer.trim() !== '';
                });

                return allQuizzesAnswered && allEssaysAnswered;
            });

            if (allModulesComplete && isVideoCompleted) {
                activateSelesaiKelasButton();
                console.log('Semua modul selesai dan video selesai. Tombol Selesai Kelas diaktifkan.');
            } else {
                deactivateSelesaiKelasButton();
                console.log('Belum semua modul selesai atau video belum selesai. Tombol Selesai Kelas dinonaktifkan.');
            }
        }

        function activateSelesaiKelasButton() {
            const selesaiKelasButton = document.getElementById("selesaiKelas");
            selesaiKelasButton.classList.remove('disabled');
            selesaiKelasButton.removeAttribute('disabled');
        }

        function deactivateSelesaiKelasButton() {
            const selesaiKelasButton = document.getElementById("selesaiKelas");
            selesaiKelasButton.classList.add('disabled');
            selesaiKelasButton.setAttribute('disabled', 'disabled');
        }

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
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

            // Change the border to primary for the clicked card
            var card = event.target.closest('.card'); // Get the clicked card
            card.classList.add('border-primary');

            // Remove primary border color from all other cards
            var allCards = document.querySelectorAll('.card');
            allCards.forEach(function(otherCard) {
                if (otherCard !== card) {
                    otherCard.classList.remove('border-primary');
                }
            });

            // Update progress
            setActiveModule(index + 1); // Update progress based on the clicked module's index
        }
    </script>


    <script>
        // Deklarasikan courseModules di JavaScript
        const courseModules = @json($courseModules);
        console.log('Data courseModules:', courseModules); // Debugging untuk memastikan data diterima

        // document.addEventListener('DOMContentLoaded', function() {
        //     console.log('Halaman dimuat, validasi tombol selesai kelas.');
        //     validateCompletion(courseModules); // Memastikan tombol diperiksa saat halaman dimuat
        // });
    </script>

    {{-- <script>
    function validateCompletion(courseModules) {
        console.log('Validasi mulai dengan modul:', courseModules);

        // Cek apakah tidak ada quiz dan essay sama sekali
        const noQuizAndEssay = courseModules.every(modul => 
            (!modul.quizIds || modul.quizIds.length === 0) && 
            (!modul.essayIds || modul.essayIds.length === 0)
        );

        // Jika tidak ada quiz dan essay, tombol langsung aktif
        if (noQuizAndEssay) {
            console.log('Tidak ada quiz dan essay. Tombol Selesai Kelas diaktifkan.');
            activateSelesaiKelasButton();
            return;
        }

        // Memeriksa apakah semua quiz dan essay di setiap modul telah dijawab
        const allModulesComplete = courseModules.every(modul => {
            const allQuizzesAnswered = modul.quizIds?.every(quizId => {
                const isAnswered = userAnswers[quizId]; // Jawaban quiz disimpan di `userAnswers`
                console.log(`Quiz ID ${quizId} Terjawab:`, isAnswered);
                return isAnswered;
            }) ?? true; // Jika tidak ada quiz, anggap sudah terjawab

            const allEssaysAnswered = modul.essayIds?.every(courseModulId => {
                const essayAnswer = localStorage.getItem(`essayAnswer-${courseModulId}`); // Jawaban essay disimpan di `localStorage`
                const isAnswered = essayAnswer && essayAnswer.trim() !== '';
                console.log(`Essay ID ${courseModulId} Terjawab:`, isAnswered, 'Jawaban:', essayAnswer);
                return isAnswered;
            }) ?? true; // Jika tidak ada essay, anggap sudah terjawab

            const isModuleComplete = allQuizzesAnswered && allEssaysAnswered;
            console.log(`Modul ${modul.namaModul}: Status Lengkap:`, isModuleComplete);

            return isModuleComplete;
        });

        if (allModulesComplete) {
            activateSelesaiKelasButton();
        } else {
            deactivateSelesaiKelasButton();
        }
    }

    function activateSelesaiKelasButton() {
        const selesaiKelasButton = document.getElementById("selesaiKelas");
        selesaiKelasButton.classList.remove('disabled');
        selesaiKelasButton.removeAttribute('disabled');
        console.log('Tombol Selesai Kelas diaktifkan.');
    }

    function deactivateSelesaiKelasButton() {
        const selesaiKelasButton = document.getElementById("selesaiKelas");
        selesaiKelasButton.classList.add('disabled');
        selesaiKelasButton.setAttribute('disabled', 'disabled');
        console.log('Tombol Selesai Kelas dinonaktifkan.');
    }
</script> --}}


    {{-- Quiz --}}
    <script>
        let userAnswers = JSON.parse(localStorage.getItem('userAnswers')) || {};

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

        window.onload = function() {
            loadAnswers();
        };

        function saveAnswer(courseModulId, answer) {
            const radioButtons = document.getElementsByName('answer');

            let selectedIndex = -1;
            // Cari indeks radio button yang dipilih
            for (let i = 0; i < radioButtons.length; i++) {
                if (radioButtons[i].value === answer && radioButtons[i].checked) {
                    selectedIndex = i + 1;
                    break;
                }
            }

            // console.log(`Jawaban terpilih untuk modul ${courseModulId}: ${answer}, Indeks radio button: ${selectedIndex}`);

            if (!userAnswers) userAnswers = {};
            if (userAnswers[courseModulId]) {
                delete userAnswers[courseModulId];
            }

            // Simpan jawaban dengan kode_jawaban
            userAnswers[courseModulId] = {
                answer,
                course_modul_id: courseModulId,
                kode_jawaban: selectedIndex.toString() // Menyimpan indeks sebagai kode jawaban
            };

            // console.log('Jawaban yang disimpan ke userAnswers:', userAnswers);

            // Simpan jawaban di LocalStorage
            localStorage.setItem('userAnswers', JSON.stringify(userAnswers));

            validateCompletion(courseModules);

            // console.log('Data LocalStorage setelah saveAnswer:', localStorage.getItem('userAnswers'));
        }


        // Function to load the saved answers from localStorage and set the radio buttons
        function loadAnswers() {
            const savedAnswers = JSON.parse(localStorage.getItem('userAnswers')) || {};
            Object.keys(savedAnswers).forEach(courseModulId => {
                const savedAnswer = savedAnswers[courseModulId].answer;
                const savedKodeJawaban = savedAnswers[courseModulId].kode_jawaban;
                const radioButton = document.querySelector(`input[name="answer"][value="${savedAnswer}"]`);
                // for (let i = 0; i < radioButtons.length; i++) {
                //     if (radioButtons[i].value === savedAnswer) {
                //         radioButtons[i].checked = true;

                //         console.log(
                //             `Mengatur jawaban untuk modul ${courseModulId}, kode jawaban: ${savedKodeJawaban}`);
                //         break;
                //     }
                // }
            });
        }

        // Call the loadAnswers function when the page loads
        document.addEventListener('DOMContentLoaded', loadAnswers);



        function updateQuestionNavState(quizIds, currentQuizId) {
            // Update button states based on quizIds and the currentQuizId
            quizIds.forEach((quizId, index) => {
                const button = document.getElementById(`quizButton-${quizId}`);
                if (button) {
                    // Aktifkan tombol berdasarkan quizId yang aktif
                    if (quizId === currentQuizId) {
                        button.classList.add('btn-primary');
                        button.classList.remove('btn-outline-primary');
                    } else {
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-outline-primary');
                    }

                    // Ubah warna tombol menjadi info jika sudah ada jawaban
                    if (userAnswers[quizId]) {
                        button.classList.add('btn-info');
                    } else {
                        button.classList.remove('btn-info');
                    }
                }
            });
        }

        function generateQuestionNav(quizIds, currentQuizId) {
            if (!Array.isArray(quizIds)) {
                console.error("quizIds is not an array:", quizIds);
                return '';
            }

            let buttons = quizIds.map((id, index) => `
				<button 
					id="quizButton-${id}" 
					class="btn btn-outline-primary" 
					onclick="getQuiz(event, ${id})">
					${index + 1}
				</button>
			`).join('');

            return `<div class="quiz-nav">${buttons}</div>`;
        }

        function getQuiz(event, courseModulId) {
            // Clear iframe and hide ratio
            const iframe = document.getElementById("videoSource");
            const ratio = document.querySelector('.ratio');
            iframe.style.display = "none";
            ratio.style.display = "none";

            // Fetch quiz data from the backend using the course module ID
            fetch("{{ url('course/getQuiz') }}/" + courseModulId)
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message); // In case of an error
                        return;
                    }

                    const userAnswerFromDb = data.userAnswer;
                    // console.log('User Answer from DB:', userAnswerFromDb); // Untuk debugging
                    // console.log('Answer Options:', data.kunci_jawaban); // Untuk debugging

                    let userAnswer = userAnswerFromDb || JSON.parse(localStorage.getItem('userAnswers'))?.[
                        courseModulId]?.answer;

                    const iframeContent = `
                        <div class="">
                            <div class="card-header">
                                <p class="fw-bold">Pertanyaan ${data.quizIndex}:</p>
                                <p class="mt-2">${data.question}</p>
                                ${data.questionImage ? `<img src="${data.questionImage}" alt="Question Image" class="img-fluid rounded" style="width: 50%;" onclick="enlargeImage(this)">` : ''}
                            </div>
                            <div class="card-body">
                                <p class="fw-bold">Jawaban:</p>
                                <form>
                                    ${data.kunci_jawaban.map((answer, index) => {
                                        const isChecked = userAnswer === answer ? 'checked' : '';
                                        const isImage = answer.startsWith('storage/quiz/answers/');
                                        return `
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        name="answer" 
                                                        id="answer${index + 1}" 
                                                        value="${answer}" 
                                                        ${isChecked}
                                                        onclick="saveAnswer(${courseModulId}, '${answer}')"
                                                    >
                                                    <label class="form-check-label" for="answer${index + 1}" style="word-wrap: break-word; white-space: normal; display: block; max-width: 100%;">
                                                        ${isImage 
                                                            ? `<img src="{{ asset('${answer}') }}" alt="Answer Image" class="img-fluid rounded" style="width: 50%;" onclick="enlargeImage(this)">`
                                                            : answer}
                                                    </label>
                                                </div>
                                            `;
                                    }).join('')}
                                </form>
                            </div>
                            <div class="card-footer text-muted">
                                <div>Pilih salah satu jawaban di atas.<div>
                                <div>*untuk melihat gambar lebih jelas, klik gambar</div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                ${generateQuestionNav(data.quizIds, courseModulId)} <!-- Passing quizIds array -->
                            </div>
                        </div>

                        <!-- Modal for Enlarged Image -->
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <img id="modalImage" class="img-fluid rounded">
                                    </div>
                                </div>
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
            if (quizItem) {
                quizItem.classList.add('border-primary');

                // Remove primary border from all other Essay and Quiz items
                var allItems = document.querySelectorAll('.list-group-item');
                allItems.forEach(function(item) {
                    if (item !== quizItem) {
                        item.classList.remove('border-primary');
                    }
                });
            } else {
                console.error('Could not find a parent li element.');
            }
        }


        window.onload = function() {
            loadAnswers();
        };
    </script>

    {{-- Essay --}}
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

                    // Determine the source of the answer
                    const savedAnswer = localStorage.getItem(`essayAnswer-${courseModulId}`);
                    const answerFromDb = data.answer;

                    // Use database answer if available; otherwise, fallback to localStorage
                    const finalAnswer = answerFromDb || savedAnswer;

                    // Set the answer in CKEditor
                    if (finalAnswer) {
                        CKEDITOR.instances[`essayFrame-${courseModulId}`].setData(finalAnswer);
                    }

                    // Add event listener to save the content when the user types
                    CKEDITOR.instances[`essayFrame-${courseModulId}`].on('change', function() {
                        const currentContent = CKEDITOR.instances[`essayFrame-${courseModulId}`].getData();
                        console.log(`Simpan jawaban untuk essay ID ${courseModulId}:`,
                        currentContent); // Debugging
                        localStorage.setItem(`essayAnswer-${courseModulId}`, currentContent);

                        // Periksa apakah data telah disimpan dengan benar
                        const savedAnswer = localStorage.getItem(`essayAnswer-${courseModulId}`);
                        console.log(`Jawaban yang disimpan di localStorage untuk ${courseModulId}:`,
                            savedAnswer); // Debugging

                        // Memvalidasi apakah semua quiz dan essay sudah terjawab
                        validateCompletion(courseModules);
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

    {{-- POST jawaban ke database --}}
    <script>
        // Event listener untuk tombol Submit & End Course
        document.getElementById('selesaiKelas').addEventListener('click', function(e) {
            e.preventDefault();

            const userId = @json(auth()->user()->ID);
            const courseId = @json($course->id);
            const currentDate = new Date().toISOString().split('T')[0];

            // Mengambil Time Spend dari localStorage berdasarkan courseId
            const storageKey = `timeElapsed_course_${courseId}`;
            const timeSpend = parseInt(localStorage.getItem(storageKey)) || 0;

            // console.log("Time Spend untuk course ini (dalam detik):", timeSpend);

            // Mengambil Progress Bar dari localStorage
            const progressBar = parseInt(localStorage.getItem('progress_bar')) || 0;
            // console.log("Progress Bar:", progressBar + "%");

            const userAnswers = JSON.parse(localStorage.getItem('userAnswers')) || {};
            const essayAnswers = Object.keys(localStorage).filter(key => key.startsWith('essayAnswer-'));

            // console.log("User Answers:", userAnswers);

            // Pastikan progress menjadi 100% ketika selesai kelas ditekan
            currentModule = totalModules;
            updateProgress();

            // Mengirim jawaban quiz tanpa course_modul_id
            Object.keys(userAnswers).forEach((modulquizzesId) => {
                const {
                    answer,
                    kode_jawaban
                } = userAnswers[modulquizzesId];

                // console.log(`Mengirim jawaban quiz untuk modul ${modulquizzesId}`);
                // console.log(`Jawaban: ${answer}, Kode Jawaban: ${kode_jawaban}`);

                const quizData = {
                    user_id: userId,
                    modul_quizzes_id: modulquizzesId,
                    jawaban: answer,
                    kode_jawaban: kode_jawaban
                };

                fetch("{{ url('course/quiz') }}/" + quizData.modul_quizzes_id + "/submit/" + quizData
                        .user_id, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(quizData)
                        })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => console.log('Quiz Response:', data))
                    .catch(err => console.error('Quiz Error:', err));
            });

            // console.log(`Jawaban essay: ${essayAnswers}`);

            // Mengirim jawaban essay ke backend
            essayAnswers.forEach((key) => {
                const courseModulId = key.replace('essayAnswer-', '');
                const essayContent = localStorage.getItem(key);

                const essayData = {
                    user_id: userId,
                    course_modul_id: courseModulId,
                    jawaban: essayContent
                };

                // console.log(`Mengirim jawaban essay untuk modul ${courseModulId}:`, essayData);

                fetch("{{ url('course/essay') }}/" + essayData.course_modul_id + "/submit/" + essayData
                    .user_id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(essayData)
                    });
            });

            // Mengirim summary course ke backend
            const summaryData = {
                course_id: courseId,
                finish_date: currentDate,
                status: 'completed',
                time_spend: timeSpend,
                progress_bar: 100 // Pastikan progress menjadi 100%
            };

            // console.log('Mengirim data summary:', summaryData);

            fetch("{{ url('course/update-course-enrolls') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(summaryData)
                })
                .then(response => {
                    if (!response.ok) {
                        // Jika respons tidak ok, tampilkan alert error
                        swal("Terjadi kesalahan!", "Jawaban gagal disimpan.", "error");
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Summary Response:', data);
                    // Tampilkan swal setelah data berhasil disimpan
                    swal("Jawaban berhasil dikumpulkan!", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        }
                    }).then(() => {
                        // Setelah swal ditutup, lakukan refresh halaman
                        window.location.href = "{{ route('pages.dashboard') }}";
                    });
                })
                .catch(error => {
                    console.error('Summary Error:', error);
                    // Tampilkan swal jika terjadi error
                    swal("Terjadi kesalahan!", "Jawaban gagal disimpan.", "error");
                });
        });
    </script>

    {{-- Time Spend --}}
    <script>
        // Fungsi untuk format waktu
        function formatTimeSpend(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        let timeElapsed = 0; // Waktu berjalan dalam detik
        let timer = null; // Timer ID untuk setInterval

        // Ambil course_id dari URL
        const urlPath = window.location.pathname; // Contoh: /course/25/
        const courseIdMatch = urlPath.match(/\/course\/(\d+)/); // Ekstrak angka setelah "/course/"
        const courseId = courseIdMatch ? courseIdMatch[1] : 'default';

        // Gunakan course_id untuk kunci localStorage
        const storageKey = `timeElapsed_course_${courseId}`;

        async function updateTimeSpendToDatabase() {
            const userId = @json(auth()->user()->ID);
            const response = await fetch("{{ url('course/post-time-spend-and-progress-bar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    course_id: courseId,
                    user_id: userId,
                    time_spend: timeElapsed,
                    progress_bar: Math.floor((currentModule / totalModules) *
                        100) // Kirim progress juga
                })
            });
            const result = await response.json();
            if (result.status !== 'success') {
                console.error('Failed to update time spend:', result.message);
            }
        }

        // Fungsi untuk ambil data dari server (API)
        async function fetchTimeSpendFromDatabase() {
            const userId = @json(auth()->user()->ID); // Ganti dengan ID pengguna yang sesuai
            const url = "{{ url('course/get-time-spend-and-progress-bar') }}/" + courseId + "/" + userId;

            const response = await fetch(url);
            const data = await response.json();
            if (data && data.time_spend) {
                timeElapsed = data.time_spend;
            } else {
                const storedTime = localStorage.getItem(storageKey);
                if (storedTime) {
                    timeElapsed = parseInt(storedTime, 10);
                }
            }
        }

        // Fungsi untuk mulai timer
        function startTimer() {
            if (!timer) {
                timer = setInterval(() => {
                    timeElapsed++;
                    document.getElementById('time').innerText = `Time Spend: ${formatTimeSpend(timeElapsed)}`;
                    localStorage.setItem(storageKey, timeElapsed);
                }, 1000);
            }
        }

        // Fungsi untuk berhenti timer
        function stopTimer() {
            if (timer) {
                clearInterval(timer);
                timer = null; // Set timer ke null untuk mencegah setInterval ganda
            }
            localStorage.setItem(storageKey, timeElapsed);
            updateTimeSpendToDatabase();
        }

        // Event listener untuk mendeteksi perubahan visibility halaman
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                startTimer(); // Halaman aktif
            } else {
                stopTimer(); // Halaman tidak aktif
            }
        });

        // Ambil data dari database atau localStorage saat halaman dimuat
        document.addEventListener('DOMContentLoaded', async () => {
            await fetchTimeSpendFromDatabase();
            document.getElementById('time').innerText = `Time Spend: ${formatTimeSpend(timeElapsed)}`;
            startTimer();
        });
    </script>

    {{-- Progress Bar --}}
    <script>
        // Total jumlah modul, termasuk Introduction
        const totalModules = {{ $course->modul->count() + 1 }}; // +1 untuk Introduction
        let currentModule = 0; // Modul saat ini (dimulai dari 0 untuk Introduction)
        let courseStatus = null; // Variabel untuk menampung status course

        // Gunakan course_id untuk kunci localStorage yang unik
        const progressStorageKey = `progress_bar_course_${courseId}`;

        async function updateProgressToDatabase() {
            // Jika status course sudah completed, jangan kirim update progress
            if (courseStatus === 'completed') {
                console.log('Course telah completed, progress tidak perlu diupdate lagi');
                return;
            }

            const userId = @json(auth()->user()->ID);
            const progressPercent = Math.floor((currentModule / totalModules) * 100);

            const response = await fetch("{{ url('course/post-time-spend-and-progress-bar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    course_id: courseId,
                    user_id: userId,
                    time_spend: timeElapsed, // Kirim time_spend juga
                    progress_bar: progressPercent
                })
            });
            const result = await response.json();
            if (result.status !== 'success') {
                console.error('Failed to update progress bar:', result.message);
            }
        }

        // Fungsi untuk memperbarui progress
        function updateProgress() {
            let progressPercent = Math.floor((currentModule / totalModules) * 100);

            // Pastikan tidak lebih dari 99% (submit membuatnya menjadi 100%)
            if (progressPercent >= 100) {
                progressPercent = 100;
            }

            // Simpan progress ke localStorage dengan kunci berdasarkan course_id
            localStorage.setItem(progressStorageKey, progressPercent);

            // Update tampilan progress
            document.querySelector('.progress-bar').style.width = `${progressPercent}%`;
            document.querySelector('.progress-status span.fw-bold').innerText = `${progressPercent}%`;

            updateProgressToDatabase();
        }

        // Fungsi untuk set modul aktif
        function setActiveModule(index) {
            currentModule = index;
            updateProgress();
        }

        // Fungsi untuk ambil data dari server (API)
        async function fetchProgressFromDatabase() {
            const userId = @json(auth()->user()->ID); // Ganti dengan ID pengguna yang sesuai
            const url = "{{ url('course/get-time-spend-and-progress-bar') }}/" + courseId + "/" + userId;

            const response = await fetch(url);
            const data = await response.json();

            // Asumsikan API mengembalikan 'progress_bar' dan 'status'
            if (data) {
                // Periksa apakah status sudah completed
                if (data.status && data.status === 'completed') {
                    courseStatus = 'completed';
                    currentModule = totalModules; // Set progress menjadi 100%
                } else if (data.progress_bar) {
                    const savedProgress = data.progress_bar;
                    currentModule = Math.floor((savedProgress / 100) * totalModules);
                } else {
                    const savedProgress = parseInt(localStorage.getItem(progressStorageKey)) || 0;
                    currentModule = Math.floor((savedProgress / 100) * totalModules);
                }
            }
        }

        // Ambil progress dari database atau localStorage saat halaman dimuat
        document.addEventListener('DOMContentLoaded', async () => {
            await fetchProgressFromDatabase();
            updateProgress();
        });
    </script>


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


@endsection
