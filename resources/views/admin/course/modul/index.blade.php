@extends('admin.layouts.app')
@section('title', 'Modul Kelas ' . $data->nama_kelas)
@section('css')
    <style>
        .drag {
            cursor: all-scroll !important;
        }

        .drag:hover {
            cursor: all-scroll !important;
        }

        .drag:active {
            cursor: all-scroll !important;
        }

        .dropdown-item:active {
            color: white !important;
        }

        /* ===== Scrollbar CSS ===== */
        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #f4e2f9 #ffffff;
        }

        /* Chrome, Edge, and Safari */
        *::-webkit-scrollbar {
            width: 16px;
        }

        *::-webkit-scrollbar-track {
            background: #ffffff;
        }

        *::-webkit-scrollbar-thumb {
            background-color: #f4e2f9;
            border-radius: 13px;
            border: 3px solid #ffffff;
        }

        .item-list:hover {
            background-color: #f6f9fc
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Kelas</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.course.course.index') }}">Kelas</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a>{{ $data->nama_kelas }}</a>
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title my-auto">
                    <a href="{{ route('admin.course.course.index') }}"class="btn btn-icon btn-round btn-light">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <span>{{ $data->nama_kelas }}</span>
                </h4>
                <a class="btn btn-warning btn-round ms-auto" href="{{ route('admin.course.course.edit', $data->id) }}">
                    <i class="fa fa-pen-square me-1"></i>
                    Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-2 text-center">
                        <img class="img-thumbnail rounded" src="{{ $data->thumbnail_url }}" alt="{{ $data->thumbnail }}"
                            width="200px">
                    </div>
                    <div class="col-12 col-lg-10">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="mb-1">
                                    <span class="text-muted">Peserta</span>
                                    <h6 class="text-uppercase fw-bold">
                                        <i class="fas fa-users me-2"></i> {{ $data->user->count() }} Peserta
                                    </h6>
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted">Total Modul</span>
                                    <h6 class="text-uppercase fw-bold">
                                        <i class="far fa-folder me-2"></i> {{ $totalModul }} Modul
                                    </h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted">Rincian</span>
                                    <h6 class="text-uppercase fw-bold">
                                        <span class="me-3">
                                            <i class="fas fa-video me-1"></i> {{ $videoModul }}
                                        </span>
                                        <span class="me-3">
                                            <i class="far fa-file-powerpoint me-1"></i> {{ $pdfModul }}
                                        </span>
                                        <span class="me-3">
                                            <i class="fas fa-link me-1"></i> {{ $linkModul }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="mb-1">
                                    <span class="text-muted">Learning Category</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $data->learningCategory->nama ?? '-' }}
                                    </h6>
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted">Division</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $data->divisiCategory->nama ?? '-' }}</h6>
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted">Category</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $data->category->nama ?? '-' }}</h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted">Sub Category</span>
                                    <h6 class="text-uppercase fw-bold">
                                        {{ $data->subCategory->nama ?? '-' }}</h6>
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

                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"
                                    style="">
                                    <div class="card-body">
                                        {!! $data->deskripsi !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <div class="card-title">Modul Kelas</div>
                            <input type="checkbox" class="d-none" id="toggleHapus">
                            <input type="checkbox" class="d-none" id="toggleUbahUrut">
                            <div class="card-tools">
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownmodul"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownmodul">
                                        <a class="dropdown-item fw-bold text-primary fs-6"
                                            href="{{ route('admin.course.modul.create', $data->id) }}">
                                            <i class="fas fa-plus-circle me-1"></i>
                                            Tambah Modul
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" id="btnUbahUrut">
                                            <i class="fas fa-list me-1"></i>
                                            Ubah urutan modul</a>
                                        <a class="dropdown-item text-danger fw-bold" href="#" id="btnHapus">
                                            <i class="fas fa-trash-alt me-1"></i>
                                            Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-head-row card-tools-still-right bg-light" id="menuUbahUrutan"
                            style="display: none">
                            <p class="my-auto mx-3 fs-6 fw-bold"> <i class="fas fa-arrows-alt"></i> Ubah Urutan Modul</p>
                            <div class="card-tools text-end py-3">
                                <a class="btn btn-outline-danger btn-round btn-sm" id="batalUbahUrut">Batal</a>
                                <button class="btn btn-primary btn-round btn-sm" type="submit"
                                    id="simpanUbahUrut">Simpan</button>
                            </div>
                        </div>
                        <div class="card-head-row card-tools-still-right bg-light" id="menuHapus" style="display: none">
                            <p class="my-auto mx-3 fs-6 fw-bold text-danger"> <i class="fas fa-trash-alt"></i> Hapus
                                Modul</p>
                            <div class="card-tools text-end py-3">
                                <a class="btn btn-outline-danger btn-round btn-sm" id="batalHapus">Batal</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center mb-0 rounded">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th scope="col">Judul</th>
                                        <th scope="col" class="text-center">Media</th>
                                        <th scope="col" class="text-center">Tipe</th>
                                        {{-- <th scope="col" class="text-center">Status</th> --}}
                                        <th scope="col" class="text-center">Quiz</th>
                                        <th scope="col" class="text-center">Essay</th>
                                    </tr>
                                </thead>
                                <tbody id="my-list">
                                    @foreach ($courseModul->sortBy('no_urut') as $item)
                                        <tr id="module-{{ $item->id }}">
                                            <td class="text-center">
                                                <button class="btn btn-icon btn-sm drag" type="button"
                                                    style="display: none">
                                                    <i class="fas fa-equals"></i>
                                                </button>
                                                <div class="delete_button" style="display: none">
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#modal_delete_{{ $item->id }}">
                                                        <i class="fas fa-times-circle text-danger fs-5"></i>
                                                    </a>
                                                    <div class="modal fade" id="modal_delete_{{ $item->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="modal_delete_{{ $item->id }}Label"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('admin.course.modul.destroy', ['course_id' => $data->id, 'modul_id' => $item->id]) }}"
                                                                    class="d-inline my-1" method="POST">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <h4 class="text-danger mb-4">Hapus data </h4>
                                                                        <p>
                                                                            Apakah anda yakin menghapus data Modul
                                                                            {{ $item->nama_modul }} ?
                                                                        </p>
                                                                    </div>
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-center">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-bs-dismiss="modal">Tutup
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Hapus</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" name="order_id" value="{{ $item->id }}"
                                                    style="display: none">
                                            </td>
                                            <td>
                                                {{ $item->nama_modul }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ $item->url_media_link }}" target="_blank">Lihat Media</a>
                                            </td>
                                            <td class="text-center">
                                                @switch($item->tipe_media)
                                                    @case('video')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-video me-2"></i>
                                                            Video</span>
                                                    @break

                                                    @case('pdf')
                                                        <span class="badge badge-count fw-bold">
                                                            <i class="fas fa-file-pdf me-2 text-danger"></i>
                                                            <span class="text-danger">PDF</span>
                                                        </span>
                                                    @break

                                                    @case('link')
                                                        <span class="badge badge-info">
                                                            <i class="fas fa-link me-2"></i>
                                                            Link
                                                        </span>
                                                    @break

                                                    @default
                                                @endswitch
                                            </td>
                                            {{-- <td class="text-center">
                                                <div class="form-check form-switch text-center p-1">
                                                    <div class="d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            onchange="setStatusActive({{ $item->id }})"
                                                            id="is_active_{{ $item->id }}"
                                                            {{ $item->active == 1 ? 'checked' : '' }}>
                                                    </div>
                                                    <label class="form-check-label" for="is_active_{{ $item->id }}"
                                                        id="label_check_{{ $item->id }}">
                                                        {{ $item->active == 1 ? 'Aktif' : 'Non Aktif' }}
                                                    </label>
                                                </div>
                                            </td> --}}
                                            <td class="text-center">
                                                <p class="form-text mb-1">Total soal : {{ count($item->modulQuiz) }}</p>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalImportQuiz{{ $item->id }}">
                                                    Tambahkan Quiz
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <p class="form-text mb-1">Total soal : {{ count($item->modulEssay) }}</p>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalImportEssay{{ $item->id }}">
                                                    Tambahkan Essay
                                                </button>
                                            </td>
                                        </tr>
                                        {{-- Modal Quiz --}}
                                        <div class="modal fade" id="modalImportQuiz{{ $item->id }}" tabindex="-1"
                                            role="dialog"aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Import Quiz : {{ $item->nama_modul }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{ route('admin.course.modul.question-import', ['course_id' => $data->id, 'modul_id' => $item->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div>
                                                                <label for="excel"
                                                                    class="col-md-4 col-lg-2 col-form-label fw-bold mb-2">Pilih
                                                                    File</label>
                                                                <div class="col-md-8 mb-4">
                                                                    <input type="file" class="form-control"
                                                                        name="excel" id="excel"
                                                                        aria-describedby="desc_excel" required />
                                                                    <div id="desc_excel" class="form-text fst-italic">
                                                                        Download
                                                                        template file <a
                                                                            href="{{ asset('FormatImportPertanyaanModul.xlsx') }}"
                                                                            download> disini
                                                                            <i class="bi bi-download"></i></a>
                                                                        <br>
                                                                        <span class="text-danger">*apabila anda mengimport quiz, daftar quiz yang
                                                                        sebelumnya akan terhapus</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label
                                                                    class="col-md-4 col-lg-2 col-form-label fw-bold mb-2">Quiz
                                                                    : {{ $item->nama_modul }}
                                                                </label>
                                                                @if ($item->modulQuiz->isEmpty())
                                                                    <p class="form-text fst-italic">Anda belum menambahkan
                                                                        quiz</p>
                                                                @endif
                                                                <div class="accordion accordion-secondary">
                                                                    @foreach ($item->modulQuiz as $quiz)
                                                                        <div class="card my-1">
                                                                            <div class="card-header" id="headingOne"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#collapsQuestion{{ $quiz->id }}"
                                                                                aria-expanded="true"
                                                                                aria-controls="collapsQuestion{{ $quiz->id }}">
                                                                                <div class="span-title">
                                                                                    {{ $loop->iteration }}.
                                                                                    {{ $quiz->pertanyaan }}
                                                                                </div>
                                                                                <div class="span-mode"></div>
                                                                            </div>
                                                                            <div id="collapsQuestion{{ $quiz->id }}"
                                                                                class="collapse"
                                                                                aria-labelledby="headingOne"
                                                                                data-parent="#accordion">
                                                                                <div class="card-body py-2 px-3">
                                                                                    <ol class="list-group">
                                                                                        @foreach ($quiz->modulQuizAnswer as $answer)
                                                                                            <li
                                                                                                class="list-group-item {{ $quiz->kunci_jawaban == $loop->iteration ? 'active' : '' }}">
                                                                                                {{ chr(64 + $loop->iteration) }}
                                                                                                .
                                                                                                {{ $answer->pilihan }}
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ol>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer d-flex justify-content-center">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit"
                                                                    class="btn btn-success">Import</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Modal Essay --}}
                                        <div class="modal fade" id="modalImportEssay{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="modalImportEssayLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalImportEssayLabel">Tambah Essay
                                                            {{ $item->nama_modul }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form id="essayForm{{ $item->id }}" class="essay-form"
                                                        action="{{ route('admin.course.modul.question-import-essay', ['course_id' => $data->id, 'modul_id' => $item->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div id="essay-container{{ $item->id }}">
                                                                @foreach ($item->modulEssay as $essay)
                                                                    <div class="d-flex mb-3"
                                                                        id="essay-{{ $loop->iteration }}">
                                                                        <div class="me-2"
                                                                            style="width: 30px; text-align: center; line-height: 2;">
                                                                            <span
                                                                                class="number">{{ $loop->iteration }}</span>
                                                                        </div>
                                                                        <textarea class="form-control post-data" name="essay[]" rows="3" required data-id="{{ $essay->id }}"
                                                                            data-course-id="{{ $data->id }}" data-modul-id="{{ $item->id }}">{{ $essay->pertanyaan }}</textarea>
                                                                        <button type="button"
                                                                            class="btn btn-outline-danger btn-sm deleteEssay"
                                                                            data-modal-id="{{ $item->id }}"
                                                                            data-id="{{ $essay->id }}"
                                                                            data-course-id="{{ $data->id }}"
                                                                            data-modul-id="{{ $item->id }}">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <!-- Ikon untuk menambah nomor dan textarea baru -->
                                                            <div class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-secondary btn-sm addEssay"
                                                                    data-modal-id="{{ $item->id }}">
                                                                    <i class="fas fa-arrow-down"></i> Tambah Essay <i
                                                                        class="fas fa-arrow-down"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" id="submit-essay"
                                                                class="btn btn-primary">Simpan</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-body">
                        <div class="card-head-row card-tools-still-right">
                            <div class="card-title">Daftar Peserta
                                <span class="badge badge-secondary" data-bs-toggle="tooltip" data-bs-html="true"
                                    data-bs-title="Aktif : {{ $data->user->where('status', '!=', 'nonaktif')->count() }} <br> Non Aktif : {{ $data->user->where('status', 'nonaktif')->count() }}">{{ $data->user->count() }}</span>
                            </div>
                            <div class="card-tools">
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item fw-bold text-primary fs-6" href="#"
                                            data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                                            <i class="fas fa-user-plus me-1"></i>
                                            Tambah Peserta
                                        </a>
                                        {{-- <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" id="">
                                            <i class="fas fa-list me-1"></i>
                                            Urutkan</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modalTambahPeserta" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable " role="document">
                                <div class="modal-content card card-round">
                                    <div class="card-body">
                                        <div class="card-head-row card-tools-still-right">
                                            <div class="card-title">Tambah Peserta</div>
                                            <div class="card-tools">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                        <div class="form-group px-0 pb-0">
                                            <div class="input-icon mb-1">
                                                <span class="input-icon-addon">
                                                    <i class="fa fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control" placeholder="Cari..."
                                                    id="search">
                                            </div>
                                            <div class="overflow-x-scroll   ">
                                                <div class="avatar-group align-middle" id="selectedAvatars"
                                                    style="height: 50px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-list overflow-y-scroll pt-0" style="height: 360px"
                                            id="userList">
                                            @foreach ($listUser as $users)
                                                <div class="item-list" data-name="{{ $users->Nama }}">
                                                    <div class="avatar">
                                                        <img src="{{ $users->fotoUrl }}"
                                                            class="avatar-img rounded-circle">
                                                    </div>
                                                    <div class="info-user ms-3">
                                                        <div class="username">{{ $users->Nama }}</div>
                                                        <div class="status">{{ $users->Jabatan }}</div>
                                                    </div>
                                                    <div class="selectgroup selectgroup-pills">
                                                        <label class="selectgroup-item">
                                                            <input type="checkbox" name="user_id[]"
                                                                value="{{ $users->ID }}"class="selectgroup-input"
                                                                onchange="toggleAvatar(this, '{{ $users->fotoUrl }}')">
                                                            <span class="selectgroup-button">
                                                                <i class="fas fa-user-plus fs-6"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="pt-2">
                                            <button class="btn btn-primary col-12" id="submitSelected">
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal" id="modalStore" tabindex="-1" data-bs-backdrop="static"
                            data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal modal-sm"
                                role="document">
                                <div class="modal-content shadow-lg">
                                    <div class="modal-body">
                                        <div class="progress-card my-auto">
                                            <div class="progress-status">
                                                <span class="text-muted" id="progress_title_status">Proses Upload
                                                    Data</span>
                                                <span class="text-muted fw-bold" id="progress_status"></span>
                                            </div>
                                            <div class="progress">
                                                <div id="progress_bar"
                                                    class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                                    role="progressbar" style="width: 0%" aria-valuenow="0"
                                                    aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip"
                                                    data-placement="top">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <small><i>*jangan me-refresh atau menutup halaman ini</i></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Existing Template -->
                        <div class="card-list py-4 overflow-y-scroll" style="max-height: 720px">
                            @foreach ($data->user as $enrollUser)
                                <a href="#" class="item-list">
                                    @php
                                        $formattedFoto = str_pad($enrollUser->ID, 5, '0', STR_PAD_LEFT);
                                        $fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG";
                                    @endphp
                                    <div class="avatar">
                                        <img src="{{ $fotoUrl }}" class="avatar-img rounded-circle">
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username">{{ $enrollUser->Nama }}</div>
                                        <div class="status">{{ $enrollUser->Jabatan }}</div>
                                    </div>
                                    <form id="deleteForm"
                                        action="{{ route('admin.course.course.destroy-user', ['course_id' => $data->id, 'user_id' => $enrollUser->ID]) }}"
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="alertHapus btn btn-icon btn-round btn-light text-danger me-3">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/core/jq-ajax-progress.min.js') }}"></script>
    <script>
        let listSortModul = $('#my-list').sortable({
            handle: '.drag',
            animation: 150,
            forceFallback: true,
            onChoose: function(e) {
                $('.sortable-chosen').css('cursor', 'all-scroll');
            },
            onUnchoose: function(e) {
                $('.sortable-chosen').css('cursor', 'all-scroll');
            },
            onStart: function(e) {
                $('.sortable-chosen').css('cursor', 'all-scroll');
            },
            onEnd: function(e) {
                $('.sortable-chosen').css('cursor', 'all-scroll');
            },
            onMove: function(e) {
                $('.sortable-chosen').css('cursor', 'all-scroll')
            },
        });

        function checkHapus() {
            if ($('#toggleHapus').is(':checked')) {
                $('.drag').hide();
                $('.delete_button').show();
                $('#menuHapus').show();
                $('#menuUbahUrutan').hide();
            } else {
                $('.delete_button').hide();
                $('#menuHapus').hide();
            }
        }

        function checkUbahUrut() {
            if ($('#toggleUbahUrut').is(':checked')) {
                $('.delete_button').hide();
                $('.drag').show();
                $('#menuUbahUrutan').show();
                $('#menuHapus').hide();
            } else {
                $('.drag').hide();
                $('#menuUbahUrutan').hide();
            }
        }

        // function setStatusActive(id) {
        //     var isActive = $('#is_active_' + id).is(':checked');
        //     $.ajax({
        //         method: "POST",
        //         url: "{{ url('/admin/course/course') }}" + "/" + "{{ $data->id }}" +
        //             "/modul/update-is-active/" +
        //             id,
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             _method: 'PATCH',
        //             active: isActive === true ? 1 : 0,
        //             updateStatus: true
        //         },
        //     }).done(function(data) {
        //         if (data.isActive == 1) {
        //             $('#label_check_' + id).text('Aktif')
        //             $('#is_active_' + id).prop('checked', true)
        //         } else {
        //             $('#label_check_' + id).text('Non Aktif')
        //             $('#is_active_' + id).prop('checked', false)
        //         }
        //         $.notify({
        //             icon: "icon-check",
        //             title: 'Sukses',
        //             message: 'Berhasil mengubah status data',
        //         }, {
        //             type: "info",
        //             allow_dismiss: true,
        //             placement: {
        //                 from: "bottom",
        //                 align: "right"
        //             },
        //             timer: 1000,
        //         });
        //     }).fail(function(data) {

        //         $('#is_active_' + id).prop('checked', !isActive)

        //         $.notify({
        //             icon: "icon-close",
        //             title: 'Gagal',
        //             message: 'Gagal mengubah status data',
        //         }, {
        //             type: "danger",
        //             allow_dismiss: true,
        //             placement: {
        //                 from: "bottom",
        //                 align: "right"
        //             },
        //             timer: 1000,
        //         });
        //     });
        // }

        $('#btnHapus').click(function(e) {
            e.preventDefault();
            let stat = $('#toggleHapus').is(':checked');
            $('#toggleHapus').prop('checked', !stat)
            checkHapus();
        });

        $('#btnUbahUrut').click(function(e) {
            e.preventDefault();
            let stat = $('#toggleUbahUrut').is(':checked');
            $('#toggleUbahUrut').prop('checked', !stat)
            checkUbahUrut();
        });

        $('#batalHapus').click(function(e) {
            e.preventDefault();
            let stat = $('#toggleHapus').is(':checked');
            $('#toggleHapus').prop('checked', !stat)
            checkHapus();
        });

        $('#batalUbahUrut').click(function(e) {
            e.preventDefault();
            let stat = $('#toggleUbahUrut').is(':checked');
            $('#toggleUbahUrut').prop('checked', !stat)
            checkUbahUrut();
            location.reload();
        });

        $('#simpanUbahUrut').click(function(e) {
            e.preventDefault();
            let arrParams = [];
            $.each($('#my-list input[name=order_id]'), function(index, element) {
                const params = {
                    id: element.value,
                    no_urut: index + 1
                };
                arrParams.push(params)
            });
        });

        $('#search').on('input', function() {
            const query = $(this).val().toLowerCase();
            $('#userList .item-list').filter(function() {
                const name = $(this).data('name');
                // Pastikan name tidak undefined sebelum memanggil toLowerCase
                return name && name.toLowerCase().indexOf(query) > -1;
            }).show(); // Tampilkan item yang cocok
            $('#userList .item-list').filter(function() {
                const name = $(this).data('name');
                return !(name && name.toLowerCase().indexOf(query) > -1);
            }).hide(); // Sembunyikan item yang tidak cocok
        });

        function toggleAvatar(checkbox, fotoUrl) {
            const avatarGroup = $('#selectedAvatars');

            if (checkbox.checked) {
                // Tambah avatar
                const avatarHtml =
                    `<div class="avatar avatar-sm my-auto"><img src="${fotoUrl}" class="avatar-img rounded-circle"></div>`;
                avatarGroup.append(avatarHtml);
            } else {
                // Hapus avatar
                avatarGroup.find(`img[src="${fotoUrl}"]`).closest('.avatar').remove();
            }
        }

        $('#submitSelected').on('click', function() {
            const selectedUsers = [];
            $('input[name="user_id[]"]:checked').each(function() {
                selectedUsers.push($(this).val());
            });

            if (selectedUsers.length > 0) {
                const modalStore = new bootstrap.Modal(
                    document.getElementById("modalStore")
                );
                modalStore.show();
                var jqxhr = $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.course.course.enroll', $data->id) }}",
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        user_id: selectedUsers,

                    }),
                    cache: false,
                    dataType: "json",
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        modalStore.hide();
                        $('#modalTambahPeserta').modal('hide');
                        $('#btnupload').prop('disabled', true)
                        $.notify({
                            icon: "icon-check",
                            title: 'Sukses',
                            message: 'Berhasil menambahkan peserta data. Halaman akan dialihkan...',
                        }, {
                            type: "primary",
                            allow_dismiss: true,
                            offset: {
                                x: 20,
                                y: 80
                            },
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            showProgressbar: true,
                            timer: 500,
                            delay: 1500,
                            onClose: redirectOnClose
                        });
                    },
                    error: function(data) {
                        modalStore.hide();
                        $('#modalTambahPeserta').modal('hide');
                        console.log(data);
                        $.notify({
                            icon: "icon-close",
                            title: `Terjadi kesalahan!`,
                            message: `(${data.status}) ${data.responseJSON.message}`,
                        }, {
                            type: "danger",
                            allow_dismiss: true,
                            offset: 50,
                            spacing: 50,
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            timer: 500,
                        });
                    },
                    uploadProgress: uploadProgress
                });

                $(jqxhr).on('uploadProgress', uploadProgress);
            } else {
                alert('Tidak ada pengguna yang terpilih.');
            }
        });

        function uploadProgress(e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded * 100) / e.total;
                $('#progress_status').text(Math.round(percentComplete) + '%')
                $('#progress_bar').css('width', percentComplete + '%')
                $('#progress_bar').attr('aria-valuenow', percentComplete)
                if (percentComplete >= 100) {
                    // process completed
                    $('#progress_title_status').text('Menyelesaikan proses...')
                }
            }
        }

        function redirectOnClose() {
            location.reload();
        }
    </script>

    <script>
        document.getElementById("simpanUbahUrut").addEventListener("click", function() {
            const listItems = document.querySelectorAll('#my-list tr');
            let order = [];

            listItems.forEach((item, index) => {
                const id = item.id.split('-')[1]; // Extract module ID
                order.push({
                    id: id,
                    no_urut: index + 1
                }); // Set the order
            });

            // Send the new order to the server
            fetch("{{ route('admin.course.modul.update-order', ['course_id' => $data->id]) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        order: order
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Handle response
                    if (data.success) {
                        $.notify({
                            icon: "icon-check",
                            title: 'Sukses',
                            message: 'Urutan berhasil diperbarui!'
                        }, {
                            type: 'success',
                            delay: 2000
                        });

                        // Delay reload for a few seconds to let the user see the notification
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000); // 2 seconds delay

                    } else {
                        $.notify({
                            icon: "icon-exclamation",
                            title: 'Gagal',
                            message: 'Terjadi kesalahan saat memperbarui urutan.'
                        }, {
                            type: 'danger',
                            delay: 2000
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $.notify({
                        icon: "icon-exclamation",
                        title: 'Gagal',
                        message: 'Terjadi kesalahan jaringan.'
                    }, {
                        type: 'danger',
                        delay: 2000
                    });
                });
        });


        // Pastikan tombol "Hapus Essay" muncul jika data ada di textarea (diambil dari database)
        // document.querySelectorAll('.modal').forEach(modal => {
        //     modal.addEventListener('show.bs.modal', function () {
        //         const modalId = this.id.replace('modalImportEssay', '');
        //         const essayContainer = document.getElementById(`essay-container${modalId}`);
        //         const deleteButton = document.querySelector(`.deleteEssay[data-modal-id="${modalId}"]`);

        //         // Periksa apakah ada data di dalam essay container
        //         const allEssayDivs = essayContainer.querySelectorAll('.d-flex.mb-3');
        //         if (allEssayDivs.length > 0) {
        //             deleteButton.style.display = 'inline-block'; // Munculkan tombol "Hapus Essay"
        //         } else {
        //             deleteButton.style.display = 'none'; // Sembunyikan tombol jika tidak ada data
        //         }
        //     });
        // });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tambah Essay
            document.querySelectorAll(".addEssay").forEach(button => {
                button.addEventListener("click", function() {
                    const modalId = this.getAttribute("data-modal-id");
                    const container = document.getElementById(`essay-container${modalId}`);
                    const count = container.querySelectorAll(".d-flex").length + 1;

                    // Buat elemen baru
                    const newEssay = document.createElement("div");
                    newEssay.classList.add("d-flex", "mb-3");
                    newEssay.innerHTML = `
                    <div class="me-2" style="width: 30px; text-align: center; line-height: 2;">
                        <span class="number">${count}</span>
                    </div>
                    <textarea class="form-control post-data" name="essay[]" rows="3" required></textarea>
                    <button type="button" class="btn btn-outline-danger btn-sm deleteEssay">
                        <i class="fas fa-trash"></i>
                    </button>
                `;

                    // Tambahkan elemen ke container
                    container.appendChild(newEssay);

                    // Tambahkan event listener untuk tombol hapus
                    newEssay.querySelector(".deleteEssay").addEventListener("click", function() {
                        newEssay.remove();
                        renumberEssay(container); // Update nomor setelah penghapusan
                    });
                });
            });

            // Hapus Essay
            document.querySelectorAll(".deleteEssay").forEach(button => {
                button.addEventListener("click", function() {
                    const modalId = this.getAttribute("data-modal-id"); // Ambil ID modal
                    const courseId = this.getAttribute("data-course-id"); // Ambil ID course
                    const modulId = this.getAttribute("data-modul-id"); // Ambil ID modul
                    const container = document.getElementById(
                    `essay-container${modalId}`); // Cari container spesifik

                    if (!container) {
                        console.warn(
                            `Container dengan ID essay-container${modalId} tidak ditemukan!`);
                        return;
                    }

                    // Elemen essay yang akan dihapus
                    const essayDiv = this.closest(".d-flex");
                    if (!essayDiv) {
                        console.error("Elemen essay untuk dihapus tidak ditemukan!");
                        return;
                    }



                    // Kirim permintaan hapus ke server
                    const essayId = this.getAttribute("data-id");
                    if (!essayId) {
                        console.warn("ID Essay tidak ditemukan!");
                        return;
                    }

                    // Logging informasi elemen yang akan dihapus
                    console.log("Elemen yang akan dihapus:", {
                        essayId: essayId,
                        content: essayDiv.querySelector(".form-control").value.trim()
                    });

                    if (essayId) {
                        fetch("{{ url('admin/course/course') }}/" + courseId + "/modul/" + modulId + "/delete-essay/" + essayId, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    essayDiv.remove(); // Hapus elemen essay dari DOM
                                    renumberEssay(container); // Perbarui nomor
                                    $.notify({
                                        icon: "icon-check",
                                        title: 'Sukses',
                                        message: 'Essay berhasil dihapus!'
                                    }, {
                                        type: 'success',
                                        delay: 2000
                                    });
                                    // Reload halaman setelah delay untuk memberi waktu notifikasi tampil
                                    // setTimeout(() => {
                                    //     location.reload();
                                    // }, 1000);
                                } else {
                                    // Tampilkan pesan error jika server mengembalikan kesalahan
                                    $.notify({
                                        icon: "icon-exclamation",
                                        title: 'Gagal',
                                        message: data.message ||
                                            'Terjadi kesalahan saat menghapus data.'
                                    }, {
                                        type: 'danger',
                                        delay: 2000
                                    });
                                }
                            })
                            .catch(error => {
                                // Tampilkan pesan error jika terjadi kesalahan jaringan
                                console.error('Error:', error);
                                $.notify({
                                    icon: "icon-exclamation",
                                    title: 'Gagal',
                                    message: 'Terjadi kesalahan jaringan.'
                                }, {
                                    type: 'danger',
                                    delay: 2000
                                });
                            });
                    } else {
                        console.log("Elemen belum tersimpan di server, menghapus langsung:", {
                            content: essayDiv.innerHTML.trim()
                        });
                        // Jika ID essay tidak tersedia, hapus langsung dari DOM
                        essayDiv.remove();
                        renumberEssay(container); // Perbarui nomor
                        $.notify({
                            icon: "icon-check",
                            title: 'Sukses',
                            message: 'Essay berhasil dihapus!'
                        }, {
                            type: 'success',
                            delay: 2000
                        });
                    }
                });
            });



            // Fungsi untuk memperbarui nomor urut
            function renumberEssay(container) {
                const numbers = container.querySelectorAll(".number");
                numbers.forEach((number, index) => {
                    number.textContent = index + 1;
                });
            }

            // Submit Form
            document.querySelectorAll(".essay-form").forEach(form => {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();

                    const modalId = this.getAttribute("id").replace("essayForm", "");
                    const container = document.getElementById(`essay-container${modalId}`);
                    const textareas = container.querySelectorAll("textarea");

                    const formData = new FormData(this);

                    // Tambahkan data essay dari textarea
                    textareas.forEach(textarea => {
                        const essayId = textarea.getAttribute(
                        'data-id'); // Mendapatkan ID jika ada
                        const essayValue = textarea.value;

                        if (essayId) {
                            // Jika ada ID, kirimkan ID dan value untuk update
                            formData.append("essay_id[]",
                            essayId); // Mengirim ID untuk update
                        }
                        formData.append("essay[]", essayValue); // Kirim value dari textarea
                    });

                    // Debugging untuk melihat data yang dikirim
                    formData.forEach((value, key) => {
                        console.log(`Key: ${key}, Value: ${value}`);
                    });

                    // Kirim data menggunakan fetch
                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Handle response dari server
                            if (data.success) {
                                $.notify({
                                    icon: "icon-check",
                                    title: 'Sukses',
                                    message: 'Essay berhasil disimpan!'
                                }, {
                                    type: 'success',
                                    delay: 2000
                                });

                                // Menutup modal yang sesuai
                                $(`#modalImportEssay${modalId}`).modal(
                                'hide'); // Bootstrap modal

                                // Reset form setelah sukses
                                form.reset();
                                const essayContainer = document.getElementById(
                                    `essay-container${modalId}`);
                                essayContainer.innerHTML = ''; // Hapus semua textarea tambahan

                                // Reload halaman setelah delay untuk memberi waktu notifikasi tampil
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                $.notify({
                                    icon: "icon-exclamation",
                                    title: 'Gagal',
                                    message: data.message ||
                                        'Terjadi kesalahan saat menyimpan data.'
                                }, {
                                    type: 'danger',
                                    delay: 2000
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            $.notify({
                                icon: "icon-exclamation",
                                title: 'Gagal',
                                message: 'Terjadi kesalahan jaringan.'
                            }, {
                                type: 'danger',
                                delay: 2000
                            });
                        });
                });
            });
        });
    </script>

    <script>
        // Hapus peserta dari enroll
        $('.alertHapus').click(function(e) {
            e.preventDefault(); // Cegah aksi default agar tidak terjadi redirect langsung

            var deleteUrl = $(this).closest('form').attr('action'); // Ambil URL untuk penghapusan dari data-url
            console.log(deleteUrl);

            swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan menghapus user ini dari course!",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya, hapus!',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    // Jika dihapus, kirim formulir untuk menghapus user
                    $(this).closest('form').submit();
                } else {
                    // Jika dibatalkan, tutup swal
                    swal.close();
                }
            });
        });
    </script>




@endsection
