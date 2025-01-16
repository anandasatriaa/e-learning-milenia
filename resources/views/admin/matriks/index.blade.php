@extends('admin.layouts.app')
@section('title', 'Dashboard Matriks Kompetensi')
@section('css')
    <style>
        .center-list {
            display: flex;
            justify-content: center;
            /* Horizontal center */
            align-items: center;
            /* Vertical center */
            text-align: center;
        }

        .hover-effect {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .hover-effect:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Bagian Kiri: Judul -->
                    <div class="card-title d-flex align-items-center">
                        <span class="bg-light p-1 rounded me-2 fs-3">
                            <i class="fas fa-chart-bar"></i>
                        </span>
                        <h5 class="mb-0">Dashboard Matriks Kompetensi</h5>
                    </div>

                    <!-- Bagian Kanan: Form Pencarian -->
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Search Divisi" id="searchDivisi"
                            aria-label="Search Divisi">
                        <button class="btn btn-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($data as $divisi)
                        <div class="col-md-4 mb-4 course-item">
                            <div class="card hover-effect">
                                <img src="{{ asset('storage/category/divisi/' . $divisi->image) }}" class="card-img-top"
                                    alt="Divisi Image">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $divisi->divisi_name }}</h5>
                                    <p class="card-text">{{ $divisi->deskripsi }}</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item center-list d-flex align-items-center justify-content-center">
                                        <i class="fas fa-users me-2"></i> {{ $divisi->peserta_count }} Peserta
                                    </li>
                                    <li class="list-group-item center-list">Matriks Kompetensi</li>
                                    <li class="list-group-item center-list text-muted">
                                        {{ $divisi->learning_category_name ?? 'Learning Category tidak tersedia' }} >
                                        {{ $divisi->divisi_category_name ?? 'Divisi Category tidak tersedia' }} >
                                        {{ $divisi->category_name ?? 'Category tidak tersedia' }}
                                    </li>
                                </ul>
                                <div class="card-body text-center">
                                    <a href="{{ route('admin.matriks-kompetensi-detail', ['divisi_id' => $divisi->id]) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Search Divisi --}}
    <script>
        // Event listener untuk input pencarian
        document.getElementById('searchDivisi').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase(); // Ambil nilai pencarian dan ubah ke huruf kecil
            const cards = document.querySelectorAll('.course-item'); // Ambil semua elemen dengan class course-item

            cards.forEach(card => {
                const title = card.querySelector('.card-title')?.textContent.toLowerCase() ||
                ''; // Ambil teks dari card-title
                const categoryInfo = card.querySelector('.center-list.text-muted')?.textContent
                .toLowerCase() || ''; // Ambil teks dari elemen kategori

                // Cek apakah pencarian cocok dengan salah satu teks
                if (title.includes(searchTerm) || categoryInfo.includes(searchTerm)) {
                    card.style.display = ''; // Tampilkan jika cocok
                } else {
                    card.style.display = 'none'; // Sembunyikan jika tidak cocok
                }
            });
        });
    </script>
@endsection
