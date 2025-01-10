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
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Bagian Kiri: Judul -->
                            <div class="card-title d-flex align-items-center">
                                <span class="bg-light p-1 rounded me-2 fs-3">
                                    <i class="fas fa-book-reader fs-3"></i>
                                </span>
                                <h5 class="mb-0">Nilai Pelatihan</h5>
                            </div>

                            <!-- Bagian Kanan: Form Pencarian -->
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" class="form-control" placeholder="Search Course" id="searchCourse"
                                    aria-label="Search Course">
                                <button class="btn btn-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-4">
                        <div class="row">
                            @foreach ($courses as $course)
                                <div class="col-lg-6 course-item" data-nama-kelas="{{ strtolower($course->nama_kelas) }}" data-category="{{ strtolower($course->learningCategory->nama ?? '') }} {{ strtolower($course->divisiCategory->nama ?? '') }} {{ strtolower($course->category->nama ?? '') }} {{ strtolower($course->subCategory->nama ?? '') }}">
                                    <div class="card card-stats card-round shadow-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-icon me-3">
                                                    <img class="w-100 rounded" src="{{ $course->thumbnail_url }}"
                                                        alt="Course Image">
                                                </div>
                                                <div class="col col-stats ms-3 ms-sm-0">
                                                    <div class="number">
                                                        <p class="card-category fw-bold text-dark align-middle mb-0">
                                                            <span class="d-flex align-items-center">
                                                                <i class="fas fa-book-open me-2 fs-4"></i>
                                                                <span
                                                                    class="ms-2 fw-semibold fs-4">{{ $course->nama_kelas }}</span>
                                                            </span>
                                                        </p>
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
                                                        <div
                                                            class="d-flex justify-content-start align-items-center mt-2 text-muted">
                                                            <div class="d-flex align-items-center me-5">
                                                                <i class="fas fa-book me-2 fs-5"></i>
                                                                <span class="fw-semibold">{{ $course->modul_count }}
                                                                    Modul</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-user-friends me-2 fs-5"></i>
                                                                <span class="fw-semibold">{{ $course->user_count }}
                                                                    Peserta</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <a href="{{ route('admin.course.nilai.detail', ['course_id' => $course->id]) }}"
                                                    class="btn btn-label-info btn-round btn-sm me-2 stretched-link">
                                                    Detail <i class="fas fa-search"></i>
                                                </a>
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
@section('js')

    {{-- Search Course --}}
    <script>
        // Event listener untuk input pencarian
        document.getElementById('searchCourse').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const courses = document.querySelectorAll('.course-item');

            courses.forEach(course => {
                const namaKelas = course.getAttribute('data-nama-kelas');
                const category = course.getAttribute('data-category');
                if (namaKelas.includes(searchTerm) || category.includes(searchTerm)) {
                    course.style.display = ''; // Tampilkan
                } else {
                    course.style.display = 'none'; // Sembunyikan
                }
            });
        });
    </script>
@endsection
