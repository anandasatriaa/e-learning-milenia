@extends('pages.layouts.app')
@section('title', 'Courses')
@section('css')
    <style>
        .shadow {
            transition: transform 0.3s ease;
            /* Efek transisi yang halus */
        }

        .shadow:hover {
            transform: scale(1.05);
            /* Membesarkan card sedikit saat hover */
        }

        .shadow {
            border: none;
            /* Menghilangkan border card agar lebih clean */
        }

        .shadow img {
            transition: transform 0.3s ease;
        }

        .shadow:hover .card-img-top {
            transform: scale(1.1);
            /* Membesarkan gambar sedikit saat hover */
        }
    </style>

    <style>
        .accordion .card>.card-header>.span-mode {
            margin-left: 0px;
        }

        .accordion .card {
            background-color: white !important;
        }

        .accordion .card .bg-white {
            background-color: white !important;
        }

        .accordion .card>.card-header {
            border-bottom: 0.1px solid gray !important;
        }
    </style>

@endsection

@section('content')
    <div class="page-inner text-center">
        <!-- Form Pencarian -->
        <div class="mb-4">
            <div class="input-group justify-content-center" style="max-width: 400px; margin: 0 auto;">
                <input type="text" class="form-control" placeholder="Search Learning Category" id="searchCategory"
                    aria-label="Search Kategori">
                <button class="btn btn-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <h1>Sub-Courses for {{ $learning->nama }}</h1>

        <div class="accordion accordion-black">
            @foreach ($groupedCourses as $learningGroup)
                <!-- Tampilkan Judul Kategori Utama -->
                <div class="card my-2">
                    <div class="card-header" id="heading{{ $loop->index }}" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $loop->index }}" aria-expanded="true"
                        aria-controls="collapse{{ $loop->index }}">
                        <div class="span-mode"></div>
                        <div class="span-title ms-2">
                            {{ $learningGroup['title'] }}
                        </div>
                    </div>

                    <div id="collapse{{ $loop->index }}" class="collapse show"
                        aria-labelledby="heading{{ $loop->index }}">
                        <div class=" px-3">
                            <!-- Divisi sebagai Subjudul -->
                            @foreach ($learningGroup['children'] as $divisiGroup)
                                <div class="accordion">
                                    <div class="card my-2">
                                        <div class="card-header" id="heading{{ $loop->parent->index }}-{{ $loop->index }}"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $loop->parent->index }}-{{ $loop->index }}"
                                            aria-expanded="true"
                                            aria-controls="collapse{{ $loop->parent->index }}-{{ $loop->index }}">
                                            <div class="span-mode"></div>
                                            <div class="span-title ms-2">
                                                {{ $divisiGroup['title'] }}
                                            </div>
                                        </div>

                                        <div id="collapse{{ $loop->parent->index }}-{{ $loop->index }}"
                                            class="collapse show"
                                            aria-labelledby="heading{{ $loop->parent->index }}-{{ $loop->index }}">
                                            <div class=" px-3">
                                                <!-- Kategori sebagai Subjudul -->
                                                @foreach ($divisiGroup['children'] as $categoryGroup)
                                                    <div class="accordion">
                                                        <div class="card my-2">
                                                            <div class="card-header"
                                                                id="heading{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                <div class="span-mode"></div>
                                                                <div class="span-title ms-2">
                                                                    {{ $categoryGroup['title'] }}
                                                                </div>
                                                            </div>

                                                            <div id="collapse{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                class="collapse show"
                                                                aria-labelledby="heading{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                <div class=" px-3">
                                                                    <!-- SubKategori sebagai Subjudul -->
                                                                    @foreach ($categoryGroup['children'] as $subCategoryGroup)
                                                                        <div class="accordion">
                                                                            <div class="card my-2">
                                                                                <div class="card-header"
                                                                                    id="heading{{ $loop->parent->parent->parent->index }}-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapse{{ $loop->parent->parent->parent->index }}-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapse{{ $loop->parent->parent->parent->index }}-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                                    <div class="span-mode"></div>
                                                                                    <div class="span-title ms-2">
                                                                                        {{ $subCategoryGroup['title'] }}
                                                                                    </div>
                                                                                </div>

                                                                                <div id="collapse{{ $loop->parent->parent->parent->index }}-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                                    class="collapse show"
                                                                                    aria-labelledby="heading{{ $loop->parent->parent->parent->index }}-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                                    <div class=" px-3 row">
                                                                                        <!-- Daftar Course -->
                                                                                        @if (isset($subCategoryGroup['courses']))
                                                                                            @foreach ($subCategoryGroup['courses'] as $course)
                                                                                                <div
                                                                                                    class="col-12 col-md-4 category-card mt-2">
                                                                                                    <a href=""
                                                                                                        class="card bg-white shadow"
                                                                                                        style="width: 30rem;">
                                                                                                        <img class="card-img-top"
                                                                                                            src="{{ asset('storage/course/thumbnail/' . $course['thumbnail']) }}"
                                                                                                            alt="{{ $course['name'] }}">
                                                                                                        <p
                                                                                                            class="fw-bold text-start m-2">
                                                                                                            {{ $course['name'] }}
                                                                                                        </p>
                                                                                                        <!-- Progress Bar Dummy -->
                                                                                                        <div
                                                                                                            class="progress-card mx-2">
                                                                                                            <div
                                                                                                                class="progress-status">
                                                                                                                <span
                                                                                                                    class="text-muted">Tasks
                                                                                                                    Complete</span>
                                                                                                                <span
                                                                                                                    class="text-muted fw-bold">70%</span>
                                                                                                            </div>
                                                                                                            <div class="progress"
                                                                                                                style="height: 6px;">
                                                                                                                <div class="progress-bar bg-primary"
                                                                                                                    role="progressbar"
                                                                                                                    style="width: 70%;"
                                                                                                                    aria-valuenow="70"
                                                                                                                    aria-valuemin="0"
                                                                                                                    aria-valuemax="100">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endforeach
                                                                                        @else
                                                                                            <p>No courses available for this
                                                                                                subcategory.</p>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>





    </div>

@endsection

@section('js')
    <script>
        document.getElementById('searchCategory').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var cards = document.querySelectorAll('.category-card');

            cards.forEach(function(card) {
                var cardName = card.querySelector('.btn').innerText.toLowerCase();
                if (cardName.includes(searchValue)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
@endsection
