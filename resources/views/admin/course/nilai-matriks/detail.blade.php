@extends('admin.layouts.app')
@section('title', 'Detail Matriks Kompetensi')
@section('css')
    <style>
        .accordion .card {
            margin-bottom: 0.5rem;
            /* Sesuaikan sesuai keinginan */
        }

        .accordion .card .card-body {
            padding: 5px !important;
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
                                    <img class="rounded me-3" src="{{ $course->thumbnail_url }}" width="200px"
                                        alt="Course Image">

                                    <!-- Text content: Peserta Kursus, Modul, and Peserta -->
                                    <div>
                                        <!-- Peserta Kursus -->
                                        <span class="d-block fw-bold">{{ $course->nama_kelas }}</span>
                                        <p class="my-0">
                                            @if ($course->subCategory && $course->category && $course->divisiCategory && $course->learningCategory)
                                                {{ $course->learningCategory->nama }} >
                                                {{ $course->divisiCategory->nama }} >
                                                {{ $course->category->nama }} >
                                                {{ $course->subCategory->nama }}
                                            @elseif ($course->category && $course->divisiCategory && $course->learningCategory)
                                                {{ $course->learningCategory->nama }} >
                                                {{ $course->divisiCategory->nama }} >
                                                {{ $course->category->nama }}
                                            @elseif ($course->divisiCategory && $course->learningCategory)
                                                {{ $course->learningCategory->nama }} >
                                                {{ $course->divisiCategory->nama }}
                                            @elseif ($course->learningCategory)
                                                {{ $course->learningCategory->nama }}
                                            @else
                                                Data tidak lengkap
                                            @endif
                                        </p>
                                        <!-- Modul and Peserta -->
                                        <div class="mt-1 fs-6">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-book me-2"></i>
                                                <span class="fw-semibold">{{ $course->modul_count }} Modul</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-friends me-2"></i>
                                                <span class="fw-semibold">{{ $course->user_count }} Peserta</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-4">
                        <div class="row">
                            @foreach ($course->user as $enrollUser)
                                @php
                                    // Menyiapkan URL foto peserta
                                    $formattedFoto = str_pad($enrollUser->id, 5, '0', STR_PAD_LEFT);
                                    $fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG";
                                @endphp

                                <div class="col-lg-6">
                                    <div class="card card-stats card-round shadow-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-icon">
                                                    <!-- Foto Peserta -->
                                                    <img class="w-100 rounded" src="{{ $fotoUrl }}"
                                                        alt="Course Image">
                                                </div>
                                                <div class="col col-stats ms-3 ms-sm-0">
                                                    <div class="number">
                                                        <p class="card-category fw-bold text-dark align-middle">
                                                            <span class="d-flex align-items-center">
                                                                <i class="fas fa-user-friends me-2 fs-4"></i>
                                                                <span
                                                                    class="ms-2 fw-semibold fs-4">{{ $enrollUser->Nama }}</span>
                                                            </span>
                                                        </p>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <span class="fw-semibold">Nilai Quiz:
                                                                {{ $enrollUser->nilaimatriks->first()->nilai_quiz ?? '-' }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <span class="fw-semibold">Nilai Essay:
                                                                {{ $enrollUser->nilaimatriks->first()->nilai_essay ?? '-' }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <span class="fw-semibold">Nilai Praktek:
                                                                {{ $enrollUser->nilaimatriks->first()->nilai_praktek ?? '-' }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <span class="fw-semibold">Kompetensi:
                                                                {{ $enrollUser->nilaimatriks->first()->presentase_kompetensi ?? '-' }}%
                                                            </span>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <a href="#"
                                                    class="btn btn-label-info btn-round btn-sm me-2 stretched-link"
                                                    data-bs-toggle="modal" data-bs-target="#reviewModal"
                                                    data-course-id="{{ $course->id }}"
                                                    data-user-id="{{ $enrollUser->id }}">
                                                    Review & Nilai <i class="fas fa-search"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            <!-- Modal -->
                            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reviewModalLabel">Review & Penilaian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="accordion accordion-black" id="accordionReview">
                                                <!-- Data Modul, Quiz, Essay akan dimuat di sini -->
                                            </div>
                                            <form>
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-md-3">
                                                        <label for="nilaiQuiz" class="form-label">Nilai Quiz <span class="text-primary">(0 - 5)</span></label>
                                                        <input type="number" class="form-control" id="nilaiQuiz" name="nilaiQuiz" 
                                                            placeholder="" min="0" max="5">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="nilaiEssay" class="form-label">Nilai Essay <span class="text-primary">(0 - 2)</span></label>
                                                        <input type="number" class="form-control" id="nilaiEssay" name="nilaiEssay" 
                                                            placeholder="" min="0" max="2">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="nilaiPraktek" class="form-label">Nilai Praktek <span class="text-primary">(0 - 8)</span></label>
                                                        <input type="number" class="form-control" id="nilaiPraktek" name="nilaiPraktek" 
                                                            placeholder="" min="0" max="8">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="presentaseKompetensi" class="form-label">Presentase Kompetensi</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="presentaseKompetensi" name="presentaseKompetensi" placeholder="" readonly>
                                                            <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="komentar" class="form-label">Komentar</label>
                                                    <textarea class="form-control" id="komentar" name="komentar" rows="3" 
                                                            placeholder="Tulis komentar"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-primary" id="saveReviewButton">Simpan
                                                Penilaian</button>
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

{{-- hasil jawaban quiz dan essay user --}}
    <script>
        // Fungsi untuk menentukan format angka
        function formatScore(score) {
            // Jika bilangan bulat, tampilkan tanpa desimal
            if (Number.isInteger(score)) {
                return score.toString();
            }
            // Jika bilangan desimal, tampilkan apa adanya
            return score.toString().replace(/\.0+$/, '');
        }

        $('#reviewModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var courseId = button.data('course-id');
            var userId = button.data('user-id');
            var modal = $(this);

            // Reset input fields hanya di modal ini
            modal.find('#nilaiQuiz').val('');
            modal.find('#nilaiEssay').val('');
            modal.find('#nilaiPraktek').val('');
            modal.find('#presentaseKompetensi').val('');
            modal.find('#komentar').val('');

            // Set data ke tombol "Simpan Penilaian"
            var saveButton = modal.find('#saveReviewButton');
            saveButton.attr('data-course-id', courseId);
            saveButton.attr('data-user-id', userId);

            $.ajax({
                url: `{{ url('admin/course/get-review-data-matriks') }}/${courseId}/${userId}`,
                method: 'GET',
                success: function(response) {
                    modal.find('.modal-title').text('Review & Penilaian ' + response.user.name);

                    if (response.course.moduls && response.course.moduls.length > 0) {
                        var accordionContent = '';
                        var totalScores = [];
                        var modulWithQuizCount = 0;

                        response.course.moduls.forEach(function(modul) {
                                var totalQuestions = modul.modul_quiz ? modul.modul_quiz.length : 0;
                                var correctCount = 0;
                                var incorrectCount = 0;

                                var quizContent = '';
                                if (modul.modul_quiz && modul.modul_quiz.length > 0) {
                                    modulWithQuizCount++;
                                    quizContent = modul.modul_quiz.map(function(quiz, index) {
                                        // Tampilkan jawaban quiz hanya untuk user yang sesuai
                                        if (quiz.userAnswer && quiz.userAnswer.user_id == userId) {
                                            var userAnswer = quiz.userAnswer.jawaban;
                                            var correctAnswer = quiz.correct_answer;
                                            var kodeJawabanPengguna = quiz.userAnswer.kode_jawaban;

                                            // Hitung benar/salah
                                            if (kodeJawabanPengguna == correctAnswer) {
                                                correctCount++;
                                            } else {
                                                incorrectCount++;
                                            }

                                            return `
                                                <div class="mb-4 p-3 border rounded shadow-sm bg-light">
                                                    <h5><strong>${index + 1}. </strong> ${quiz.pertanyaan || 'Tidak ada pertanyaan'}</h5>
                                                    ${quiz.image ? `
                                                        <div class="mt-2 ms-4">
                                                            <img src="{{ asset('${quiz.image}') }}" class="img-fluid rounded" width="100px" alt="Gambar Pertanyaan">
                                                        </div>
                                                    ` : ''}
                                                    ${quiz.options.length > 0 ? `
                                                            <p><strong>Opsi Jawaban:</strong></p>
                                                            <ul class="list-group">
                                                            ${quiz.options.map(function(option, index) {
                                                            let optionClass = '';
                                                            let correctIcon = ''; // Variabel untuk ikon jawaban benar

                                                            // Bandingkan kode jawaban pengguna dengan kunci jawaban
                                                            if (quiz.userAnswer.kode_jawaban == index + 1) {
                                                                optionClass = (index + 1 == correctAnswer) 
                                                                    ? 'bg-primary text-white' 
                                                                    : 'bg-danger text-white';
                                                            }

                                                            // Tambahkan ikon jika ini adalah jawaban yang benar
                                                            if (correctAnswer == index + 1) {
                                                                    correctIcon = `<span class="position-absolute end-0 pe-3">
                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                    </span>`; // Ikon di pojok kanan
                                                                }

                                                                // Tampilkan gambar jika pilihan berupa path gambar
                                                                const optionContent = option.pilihan.startsWith('storage/quiz/answers/') ? `
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="{{ asset('${option.pilihan}') }}" class="img-fluid" alt="Gambar Jawaban" style="width: 100px;">
                                                                    </div>
                                                                ` : `<span>${option.pilihan}</span>`;

                                                                return `<li class="list-group-item ${optionClass} d-flex align-items-center position-relative">
                                                                    <span>${optionContent}</span>
                                                                    ${correctIcon}
                                                                    </li>`;
                                                                }).join('')
                                                            } </ul>` : ''}
                                                        </div>`;
                                                    }
                                            }).join('');
                                        } else {
                                            quizContent = '<p class="text-muted ms-3">Tidak ada soal quiz di modul.</p>';
                                        }

                                        // Hitung nilai total
                                        var scorePerQuestion = totalQuestions > 0 ? 5 / totalQuestions : 0;
                                        var totalScore = correctCount * scorePerQuestion;

                                        if (totalQuestions > 0) {
                                            totalScores.push(totalScore); // Tambahkan nilai hanya jika ada soal
                                        }

                                        var essayContent = '';
                                        if (modul.modul_essay && modul.modul_essay.length > 0) {
                                            // Gabungkan semua pertanyaan dalam satu modul
                                            var combinedEssayQuestions = modul.modul_essay.map(function (essay, index) {
                                                // Periksa apakah ada gambar terkait pertanyaan
                                                var imageHtml = essay.image 
                                                    ? `<img src="{{ asset('storage/${essay.image}') }}" alt="Gambar Pertanyaan ${index + 1}" class="img-fluid rounded" width="100px">`
                                                    : '';
                                                
                                                return `
                                                    <p><strong>${index + 1}. </strong> ${essay.pertanyaan || 'Tidak ada pertanyaan'}</p>
                                                    ${imageHtml}
                                                `;
                                            }).join('');

                                            // Cari jawaban pengguna
                                            var userEssayAnswer = modul.modul_essay.find(function (essay) {
                                                return essay.userAnswer && essay.userAnswer.user_id == userId;
                                            });

                                            // Jika ada jawaban, tampilkan
                                            if (userEssayAnswer) {
                                                essayContent = `
                                                    <div class="mb-4 p-3 border rounded shadow-sm bg-light">
                                                        <div>${combinedEssayQuestions}</div>
                                                        <p class="mt-3"><strong>Jawaban Pengguna:</strong></p>
                                                        <div>${userEssayAnswer.userAnswer.jawaban}</div>
                                                    </div>
                                                `;
                                            } else {
                                                essayContent = '<p class="text-muted ms-3">Tidak ada soal essay di modul.</p>';
                                            }
                                        } else {
                                            essayContent = '<p class="text-muted ms-3">Tidak ada soal essay di modul.</p>';
                                        }

                accordionContent += `
                    <div class="card border rounded">
                        <div class="card-header collapsed" data-bs-toggle="collapse" data-bs-target="#modul${modul.id}">
                            <div class="span-title d-flex align-items-center">
                                <span class="me-2 bg-secondary p-2 my-auto rounded text-white d-flex align-items-center justify-content-center">
                                    <i class="far fa-file-alt"></i>
                                </span>
                                ${modul.nama_modul}
                            </div>
                            <div class="span-mode"></div>
                        </div>
                        <div id="modul${modul.id}" class="collapse" data-parent="#accordionReview">
                            <div class="card-body">
                                <div class="accordion accordion-black">
                                    <div class="card border rounded">
                                        <div class="card-header collapsed" data-bs-toggle="collapse" data-bs-target="#quiz${modul.id}">
                                            <div class="span-title d-flex align-items-center">
                                                <span class="me-2 bg-primary p-2 my-auto rounded text-white d-flex align-items-center justify-content-center">
                                                    <i class="far fa-comment-dots"></i>
                                                </span> Quiz
                                                <span class="ms-3"><span class="badge badge-success">Benar: ${correctCount}</span> <span class="badge badge-danger">Salah: ${incorrectCount}</span> <span class="badge badge-primary">Nilai: ${formatScore(totalScore)}</span></span>
                                            </div>
                                            <div class="span-mode"></div>
                                        </div>
                                        <div id="quiz${modul.id}" class="collapse">
                                            <div class="card-body">
                                                ${quizContent}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border rounded">
                                        <div class="card-header collapsed" data-bs-toggle="collapse" data-bs-target="#essay${modul.id}">
                                            <div class="span-title d-flex align-items-center">
                                                <span class="me-2 bg-warning p-2 my-auto rounded text-white d-flex align-items-center justify-content-center">
                                                    <i class="far fa-file-alt"></i>
                                                </span> Essay
                                            </div>
                                            <div class="span-mode"></div>
                                        </div>
                                        <div id="essay${modul.id}" class="collapse">
                                            <div class="card-body">
                                                ${essayContent}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                });
                    // Hitung rata-rata nilai
                var averageScore = totalScores.length > 0 
                    ? totalScores.reduce((a, b) => a + b, 0) / totalScores.length
                    : 0;

                    // Tampilkan rata-rata nilai di input field
                    modal.find('#nilaiQuiz').val(formatScore(averageScore));

                    modal.find('#accordionReview').html(accordionContent);
                } else {
                    modal.find('#accordionReview').html('<p>Tidak ada modul yang tersedia.</p>');
                }
                    // Set nilai quiz, nilai essay, dan komentar ke input form jika sudah ada di database
                    if (response.review) {
                        modal.find('#nilaiQuiz').val(response.review.nilai_quiz || '');
                        modal.find('#nilaiEssay').val(response.review.nilai_essay || '');
                        modal.find('#nilaiPraktek').val(response.review.nilai_praktek || '');
                        modal.find('#presentaseKompetensi').val(response.review.presentase_kompetensi || '');
                        modal.find('#komentar').val(response.review.komentar || '');
                    }
                },
                error: function(err) {
                    console.error('Error fetching review data:', err);
                    modal.find('#accordionReview').html('<p>Gagal memuat data. Silakan coba lagi nanti.</p>');
                }
            });
        });
    </script>

{{-- Perhitungan Matriks Kompetensi --}}
    <script>
        // Fungsi untuk menghitung presentaseKompetensi
        function hitungPresentaseKompetensi() {
            // Ambil nilai dari input
            var nilaiQuiz = parseFloat($('#nilaiQuiz').val()) || 0;
            var nilaiEssay = parseFloat($('#nilaiEssay').val()) || 0;
            var nilaiPraktek = parseFloat($('#nilaiPraktek').val()) || 0;

            // Perhitungan Presentase Kompetensi
            var presentase = (nilaiQuiz * 2.5 / 100) + (nilaiEssay * 10 / 100) + (nilaiPraktek * 8.75 / 100);

            // Set nilai ke input Presentase Kompetensi tanpa dua angka di belakang koma dan tambahkan tanda %
            $('#presentaseKompetensi').val(Math.round(presentase * 100) + '%'); // Mengalikan dengan 100 dan menghapus desimal
        }

        // Event listener untuk perubahan nilai pada input
        $('#nilaiQuiz, #nilaiEssay, #nilaiPraktek').on('input', function() {
            hitungPresentaseKompetensi(); // Panggil fungsi saat input berubah
        });

        // Panggil fungsi untuk menghitung presentase saat pertama kali form dimuat
        $(document).ready(function() {
            hitungPresentaseKompetensi();
        });
    </script>

{{-- kirim nilai ke database --}}
    <script>
        document.getElementById('saveReviewButton').addEventListener('click', function() {
            // Ambil nilai dari input form
            var nilaiQuiz = document.getElementById('nilaiQuiz').value;
            var nilaiEssay = document.getElementById('nilaiEssay').value;
            var nilaiPraktek = document.getElementById('nilaiPraktek').value;
            var presentaseKompetensi = document.getElementById('presentaseKompetensi').value.replace('%', '');
            var komentar = document.getElementById('komentar').value;

            // Ambil nilai dari data atribut yang ada di tombol
            var courseId = this.getAttribute('data-course-id');
            var userId = this.getAttribute('data-user-id');

            // Tampilkan data di console untuk memeriksa
            console.log('User ID:', userId);
            console.log('Course ID:', courseId);
            console.log('Nilai Quiz:', nilaiQuiz);
            console.log('Nilai Essay:', nilaiEssay);
            console.log('Nilai Praktek:', nilaiPraktek);
            console.log('Presentase Kompetensi:', presentaseKompetensi);
            console.log('Komentar:', komentar);

            // Ambil CSRF token untuk keamanan
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('Request URL:', `/admin/course/get-review-data-matriks/${courseId}/${userId}`);

            // Pertama, cek apakah review sudah ada di database
            fetch("{{ url('admin/course/get-review-data-matriks') }}/" + courseId + "/" + userId)
                .then(response => response.json())
                .then(data => {
                    var url = `{{ url('admin/course/nilai-matriks/store') }}`; // Default untuk POST (insert)
                    var method = 'POST'; // Default untuk POST (insert)

                    // Jika review sudah ada, kita gunakan PUT untuk update
                    if (data.review) {
                        url = `{{ url('admin/course/nilai-matriks/update') }}/${courseId}/${userId}`; // Gunakan route update
                        method = 'PUT'; // Menggunakan PUT, jadi method 'POST' dengan pengubahan URL
                    }

                    // Kirim data ke backend menggunakan fetch
                    fetch(url, {
                        method: method, // Menggunakan POST atau PUT sesuai kondisi
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken, // CSRF token untuk keamanan
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            course_id: courseId,
                            nilai_quiz: nilaiQuiz,
                            nilai_essay: nilaiEssay,
                            nilai_praktek: nilaiPraktek,
                            presentase_kompetensi: presentaseKompetensi,
                            komentar: komentar
                        })
                    })
                    .then(response => {
                        console.log(response.status);  // Cek status kode
                        if (response.ok) { // Jika status code adalah 200-299
                            return response.json();
                        } else {
                            throw new Error('Request failed');
                        }
                    })
                    .then(data => {
                        // Tampilkan swal setelah data berhasil disimpan
                        swal("Nilai berhasil disimpan!", { 
                            icon: "success",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            }
                        }).then(() => {
                            // Setelah swal ditutup, lakukan refresh halaman
                            location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Error terjadi:', error);
                        swal("Terjadi kesalahan!", "Nilai gagal disimpan.", "error");
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    swal("Terjadi kesalahan saat memeriksa data review!", "Silakan coba lagi.", "error");
                });
        });
    </script>
@endsection
