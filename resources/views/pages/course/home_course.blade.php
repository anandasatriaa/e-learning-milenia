@extends('pages.layouts.app')
@section('title', 'Courses')
@section('css')
    <style>
        .card {
            transition: transform 0.3s ease;
            /* Efek transisi yang halus */
        }

        .card:hover {
            transform: scale(1.05);
            /* Membesarkan card sedikit saat hover */
        }

        .card {
            border: none;
            /* Menghilangkan border card agar lebih clean */
        }

        .card img {
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.1);
            /* Membesarkan gambar sedikit saat hover */
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

        <!-- Card Kategori -->
        <div class="row" id="categoryCards">
            @foreach ($learnings as $learning)
                <div class="col-12 col-md-4 category-card">
                    <a href="{{ route('pages.subCourse', ['learning_id' => $learning->id]) }}" class="card" style="width: 30rem;">
                        <img class="card-img-top" src="{{ asset('storage/category/learning/' . $learning->image) }}"
                            alt="{{ $learning->nama }}">
                        <div class="card-body">
                            <button
                                class="btn 
                                @if ($learning->nama == 'General Learning') bg-secondary
                                @elseif($learning->nama == 'Mandatory Learning')
                                    bg-warning
                                @elseif($learning->nama == 'Specific Learning')
                                    bg-primary
                                @else
                                    bg-info @endif
                                text-white">
                                {{ $learning->nama }}
                            </button>
                        </div>
                    </a>
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
