@extends('pages.layouts.app')
@section('title', 'Courses')
@section('css')
    <style>
        .shadow {
            transition: transform 0.3s ease;
            /* Efek transisi yang halus */
        }

        .shadow:hover {
            transform: scale(1.03);
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
            transform: scale(1.03);
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

<div class="accordion accordion-black" id="accordionMain">
    @foreach ($groupedCourses as $group)
        <div class="card my-2">
            <!-- Header Kategori Utama (LearningCategory) -->
            <div class="card-header" id="heading{{ $loop->index }}" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $loop->index }}" aria-expanded="true"
                aria-controls="collapse{{ $loop->index }}">
                <div class="span-mode"></div>
                <div class="span-title ms-2">
                    {{ isset($group['title']) ? $group['title'] : 'No Title' }}
                </div>
            </div>

            <!-- Kontainer Kategori Utama -->
            <div id="collapse{{ $loop->index }}" class="collapse show" aria-labelledby="heading{{ $loop->index }}">
                <div class="px-3 row">
                    @if (isset($group['courses']) && count($group['courses']) > 0)
                        @foreach ($group['courses'] as $course)
                            <div class="col-12 col-md-4 category-card mt-2">
                                <a href="{{ route('pages.course.course.detail', $course['id']) }}"
                                    class="card bg-white shadow" style="width: 30rem;">
                                    <img class="card-img-top"
                                        src="{{ asset('storage/course/thumbnail/' . $course['thumbnail']) }}"
                                        alt="{{ $course['name'] }}">
                                    <p class="fw-bold text-start m-2">{{ $course['name'] }}</p>
                                    <div class="progress-card mx-2">
                                        <div class="progress-status">
                                            <span class="text-muted">Tasks Complete</span>
                                            <span class="text-muted fw-bold">{{ $course['progress'] }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $course['progress'] }}%;" aria-valuenow="{{ $course['progress'] }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif

                    <!-- Subkategori (DivisiCategory) -->
                    @foreach ($group['children'] as $division)
                        <div class="accordion mt-2" id="accordionSub{{ $loop->index }}">
                            <div class="card">
                                <div class="card-header" id="subHeading{{ $loop->parent->index }}-{{ $loop->index }}"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#subCollapse{{ $loop->parent->index }}-{{ $loop->index }}"
                                    aria-expanded="false" aria-controls="subCollapse{{ $loop->parent->index }}-{{ $loop->index }}">
                                    <div class="span-mode"></div>
                                    <div class="span-title ms-2">
                                        {{ isset($division['title']) ? $division['title'] : 'No Title' }}
                                    </div>
                                </div>

                                <div id="subCollapse{{ $loop->parent->index }}-{{ $loop->index }}" class="collapse show"
                                    aria-labelledby="subHeading{{ $loop->parent->index }}-{{ $loop->index }}">
                                    <div class="px-3 row">
                                        @if (isset($division['courses']) && count($division['courses']) > 0)
                                        @foreach ($division['courses'] as $course)
                                            <div class="col-12 col-md-4 category-card mt-2">
                                                <a href="{{ route('pages.course.course.detail', $course['id']) }}"
                                                    class="card bg-white shadow" style="width: 30rem;">
                                                    <img class="card-img-top"
                                                        src="{{ asset('storage/course/thumbnail/' . $course['thumbnail']) }}"
                                                        alt="{{ $course['name'] }}">
                                                    <p class="fw-bold text-start m-2">{{ $course['name'] }}</p>
                                                    <div class="progress-card mx-2">
                                                        <div class="progress-status">
                                                            <span class="text-muted">Tasks Complete</span>
                                                            <span class="text-muted fw-bold">{{ $course['progress'] }}%</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ $course['progress'] }}%;" aria-valuenow="{{ $course['progress'] }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                        @endif

                                        <!-- Category (SubCategory) -->
                                        @foreach ($division['children'] as $category)
                                            <div class="accordion mt-2" id="accordionCategory{{ $loop->parent->index }}-{{ $loop->index }}">
                                                <div class="card">
                                                    <div class="card-header" id="categoryHeading{{ $loop->parent->index }}-{{ $loop->index }}"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#categoryCollapse{{ $loop->parent->index }}-{{ $loop->index }}"
                                                        aria-expanded="false" aria-controls="categoryCollapse{{ $loop->parent->index }}-{{ $loop->index }}">
                                                        <div class="span-mode"></div>
                                                        <div class="span-title ms-2">
                                                            {{ isset($category['title']) ? $category['title'] : 'No Title' }}
                                                        </div>
                                                    </div>

                                                    <div id="categoryCollapse{{ $loop->parent->index }}-{{ $loop->index }}" class="collapse show"
                                                        aria-labelledby="categoryHeading{{ $loop->parent->index }}-{{ $loop->index }}">
                                                        <div class="px-3 row">
                                                            @if (isset($category['courses']) && count($category['courses']) > 0)
                                                            @foreach ($category['courses'] as $course)
                                                                <div class="col-12 col-md-4 category-card mt-2">
                                                                    <a href="{{ route('pages.course.course.detail', $course['id']) }}"
                                                                        class="card bg-white shadow" style="width: 30rem;">
                                                                        <img class="card-img-top"
                                                                            src="{{ asset('storage/course/thumbnail/' . $course['thumbnail']) }}"
                                                                            alt="{{ $course['name'] }}">
                                                                        <p class="fw-bold text-start m-2">{{ $course['name'] }}</p>
                                                                        <div class="progress-card mx-2">
                                                                            <div class="progress-status">
                                                                                <span class="text-muted">Tasks Complete</span>
                                                                                <span class="text-muted fw-bold">{{ $course['progress'] }}%</span>
                                                                            </div>
                                                                            <div class="progress" style="height: 6px;">
                                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                                    style="width: {{ $course['progress'] }}%;" aria-valuenow="{{ $course['progress'] }}" aria-valuemin="0"
                                                                                    aria-valuemax="100"></div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                            @endif

                                                            <!-- SubCategory (Final Level) -->
                                                            @foreach ($category['children'] as $subCategory)
                                                                <div class="card mt-2">
                                                                    <div class="card-header" id="subCategoryHeading{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#subCategoryCollapse{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                        aria-expanded="false" aria-controls="subCategoryCollapse{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                        <div class="span-mode"></div>
                                                                        <div class="span-title ms-2">
                                                                            {{ isset($subCategory['title']) ? $subCategory['title'] : 'No Title' }}
                                                                        </div>
                                                                    </div>

                                                                    <div id="subCategoryCollapse{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}" class="collapse show"
                                                                        aria-labelledby="subCategoryHeading{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                        <div class="px-3 row">
                                                                            @if (isset($subCategory['courses']) && count($subCategory['courses']) > 0)
                                                                            @foreach ($subCategory['courses'] as $course)
                                                                                <div class="col-12 col-md-4 category-card mt-2">
                                                                                    <a href="{{ route('pages.course.course.detail', $course['id']) }}"
                                                                                        class="card bg-white shadow" style="width: 30rem;">
                                                                                        <img class="card-img-top"
                                                                                            src="{{ asset('storage/course/thumbnail/' . $course['thumbnail']) }}"
                                                                                            alt="{{ $course['name'] }}">
                                                                                        <p class="fw-bold text-start m-2">{{ $course['name'] }}</p>
                                                                                        <div class="progress-card mx-2">
                                                                                            <div class="progress-status">
                                                                                                <span class="text-muted">Tasks Complete</span>
                                                                                                <span class="text-muted fw-bold">{{ $course['progress'] }}%</span>
                                                                                            </div>
                                                                                            <div class="progress" style="height: 6px;">
                                                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                                                    style="width: {{ $course['progress'] }}%;" aria-valuenow="{{ $course['progress'] }}" aria-valuemin="0"
                                                                                                    aria-valuemax="100"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            @endforeach
                                                                            @endif
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
