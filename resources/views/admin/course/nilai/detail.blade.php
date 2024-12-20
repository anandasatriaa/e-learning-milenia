@extends('admin.layouts.app')
@section('title', 'Nilai ')
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
                                                                {{ $enrollUser->nilai->first()->nilai_quiz ?? '-' }}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <span class="fw-semibold">Nilai Essay:
                                                                {{ $enrollUser->nilai->first()->nilai_essay ?? '-' }}</span>
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
                                                <div class="mb-3">
                                                    <label for="nilaiQuiz" class="form-label">Nilai Quiz</label>
                                                    <input type="number" class="form-control" id="nilaiQuiz"
                                                        placeholder="Masukkan nilai quiz">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nilaiEssay" class="form-label">Nilai Essay</label>
                                                    <input type="number" class="form-control" id="nilaiEssay"
                                                        placeholder="Masukkan nilai essay">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="komentar" class="form-label">Komentar</label>
                                                    <textarea class="form-control" id="komentar" rows="3" placeholder="Tulis komentar"></textarea>
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

<script>
    $('#reviewModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var courseId = button.data('course-id');
        var userId = button.data('user-id');
        var modal = $(this);

        $.ajax({
            url: '/admin/course/get-review-data/' + courseId + '/' + userId, 
            method: 'GET',
            success: function(response) {
                modal.find('.modal-title').text('Review & Penilaian ' + response.user.name);

                if (response.course.moduls && response.course.moduls.length > 0) {
                    var accordionContent = '';
                    response.course.moduls.forEach(function(modul) {
                        var quizContent = '';
                        if (modul.modul_quiz && modul.modul_quiz.length > 0) {
                            quizContent = modul.modul_quiz.map(function(quiz, index) {
                                // Tampilkan jawaban quiz hanya untuk user yang sesuai
                                if (quiz.userAnswer && quiz.userAnswer.user_id == userId) {
    var userAnswer = quiz.userAnswer.jawaban;
    var correctAnswer = quiz.correct_answer;

    // Menambahkan console log untuk melihat nilai kunci jawaban, jawaban pengguna, dan kode jawaban
    console.log('Kunci Jawaban:', correctAnswer);
    console.log('Jawaban Pengguna:', userAnswer);
    console.log('Kode Jawaban Pengguna:', quiz.userAnswer.kode_jawaban_pengguna);

    return `
        <div class="mb-4 p-3 border rounded shadow-sm bg-light">
            <h5><strong>${index + 1}. </strong> ${quiz.pertanyaan || 'Tidak ada pertanyaan'}</h5>
            <p><strong>Jawaban Pengguna:</strong> ${quiz.userAnswer.jawaban}</p>
            <p><strong>Kunci Jawaban:</strong> ${quiz.correct_answer || 'Tidak ada kunci jawaban'}</p>
            ${quiz.options.length > 0 ? `
                <p><strong>Opsi Jawaban:</strong></p>
                <ul class="list-group">
                    ${quiz.options.map(function(option) {
                        let optionClass = '';
                        // Cek apakah pilihan adalah kunci jawaban dan jika jawaban pengguna sesuai
                        if (option.pilihan === correctAnswer) {
                            optionClass = 'bg-success text-white'; // Kunci jawaban benar
                            // Jika jawaban pengguna sesuai dengan kunci jawaban, beri bg-primary
                            if (option.pilihan === userAnswer) {
                                optionClass = 'bg-primary text-white'; // Jawaban benar oleh pengguna
                            }
                        }
                        // Jika jawaban pengguna salah, beri bg-danger
                        if (option.pilihan === userAnswer && option.pilihan !== correctAnswer) {
                            optionClass = 'bg-danger text-white'; // Jawaban salah
                        }
                        return `<li class="list-group-item ${optionClass}">${option.pilihan}</li>`;
                    }).join('')}
                </ul>
            ` : ''}
        </div>
    `;
}



                            }).join('');
                        } else {
                            quizContent = '<p class="text-muted ms-3">Tidak ada soal quiz di modul.</p>';
                        }

                        var essayContent = '';
                        if (modul.modul_essay && modul.modul_essay.length > 0) {
                            essayContent = modul.modul_essay.map(function(essay, index) {
                                if (essay.userAnswer && essay.userAnswer.user_id == userId) {
                                    return `
                                        <div class="mb-4 p-3 border rounded shadow-sm bg-light">
                                            <h5><strong>${index + 1}. </strong> ${essay.pertanyaan || 'Tidak ada pertanyaan'}</h5>
                                            <p><strong>Jawaban Pengguna:</strong> ${essay.userAnswer.jawaban}</p>
                                        </div>
                                    `;
                                }
                            }).join(''); 
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

                    modal.find('#accordionReview').html(accordionContent);
                } else {
                    modal.find('#accordionReview').html('<p>Tidak ada modul yang tersedia.</p>');
                }
            },
            error: function(err) {
                console.error('Error fetching review data:', err);
                modal.find('#accordionReview').html('<p>Gagal memuat data. Silakan coba lagi nanti.</p>');
            }
        });
    });
</script>




@endsection
