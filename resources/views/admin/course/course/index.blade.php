@extends('admin.layouts.app')
@section('title', 'Kelas')
@section('css')
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 0px 8px !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title">Course</div>
                <a class="btn btn-secondary btn-round ms-auto" href="{{ route('admin.course.course.create') }}">
                    <i class="fa fa-plus me-1"></i>
                    Add Course
                </a>
            </div>
            <div class="card-body">


                <!-- Form Pencarian -->
                <div class="mb-4">
                    <div class="input-group justify-content-center" style="max-width: 400px; margin: 0 auto;">
                        <input type="text" class="form-control" placeholder="Search Course" id="searchCourse"
                            aria-label="Search Course">
                        <button class="btn btn-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="row" id="courseContainer">
                    @foreach ($data as $item)
                        <div class="col-md-4 course-item" data-nama-kelas="{{ strtolower($item->nama_kelas) }}">
                            <div class="card card-profile">
                                <div class="card-header" style="background-image: url('{{ asset('img/blogpost.jpg') }}')">
                                    <div class="profile-picture">
                                        <div class="avatar avatar-xl">
                                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->thumbnail_url }}"
                                                class="avatar-img rounded-circle" />
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="user-profile text-center">
                                        <div class="name">{{ $item->nama_kelas }}</div>
                                        <div class="job">
                                            @if ($item->subCategory && $item->category && $item->divisiCategory && $item->learningCategory)
                                                {{ $item->learningCategory->nama }} > {{ $item->divisiCategory->nama }} > {{ $item->category->nama }} > {{ $item->subCategory->nama }}
                                            @elseif ($item->category && $item->divisiCategory && $item->learningCategory)
                                                {{ $item->learningCategory->nama }} > {{ $item->divisiCategory->nama }} > {{ $item->category->nama }}
                                            @elseif ($item->divisiCategory && $item->learningCategory)
                                                {{ $item->learningCategory->nama }} > {{ $item->divisiCategory->nama }}
                                            @elseif ($item->learningCategory)
                                                {{ $item->learningCategory->nama }}
                                            @else
                                                Data tidak lengkap
                                            @endif
                                        </div>
                                        <div class="desc">{!! $item->deskripsi !!}</div>
                                        <div class="social-media">
                                            <a href="{{ route('admin.course.modul.index', $item->id) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modul"
                                                class="btn btn-icon btn-round btn-info my-1">
                                                <i class="far fa-list-alt text-white"></i>
                                            </a>
                                            <a href="{{ route('admin.course.course.edit', $item->id) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"
                                                class="btn btn-icon btn-round btn-warning my-1">
                                                <i class="fas fa-pen-square text-dark"></i>
                                            </a>
                                            <a class="btn btn-icon btn-round btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modal_delete_{{ $item->id }}">
                                                <i class="fas fa-times-circle text-white"></i>
                                            </a>
                                            <div class="modal fade" id="modal_delete_{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="modal_delete_{{ $item->id }}Label"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('admin.course.course.destroy', $item->id) }}"
                                                            class="d-inline my-1" method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                            <div class="modal-body">
                                                                <h4 class="text-danger mb-4">Hapus data</h4>
                                                                <p>
                                                                    Apakah anda yakin?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer d-flex justify-content-center">
                                                                <button type="button" class="btn btn-default"
                                                                    data-bs-dismiss="modal">Tutup
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="view-profile">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row user-stats text-center">
                                        <div class="col">
                                            <div class="number">{{ $item->modul_count }}</div>
                                            <div class="title">Modul</div>
                                        </div>
                                        <div class="col">
                                            <div class="number">{{ $item->user_count }}</div>
                                            <div class="title">Participants</div>
                                        </div>
                                    </div>
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
    <script>
        $('#showEntries').change(function(e) {
            e.preventDefault();
            $('#formSearch').submit();
        });

        function setStatusActive(id) {
            var isActive = $('#is_active_' + id).is(':checked');
            $.ajax({
                method: "POST",
                url: "{{ url('/admin/course/course/update-is-active') }}" + "/" + id,
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'PATCH',
                    active: isActive === true ? 1 : 0,
                    updateStatus: true
                },
            }).done(function(data) {
                if (data.isActive == 1) {
                    $('#label_check_' + id).text('Aktif')
                    $('#is_active_' + id).prop('checked', true)
                } else {
                    $('#label_check_' + id).text('Non Aktif')
                    $('#is_active_' + id).prop('checked', false)
                }
                $.notify({
                    icon: "icon-check",
                    title: 'Sukses',
                    message: 'Berhasil mengubah status data',
                }, {
                    type: "info",
                    allow_dismiss: true,
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    timer: 1000,
                });
            }).fail(function(data) {

                $('#is_active_' + id).prop('checked', !isActive)

                $.notify({
                    icon: "icon-close",
                    title: 'Gagal',
                    message: 'Gagal mengubah status data',
                }, {
                    type: "danger",
                    allow_dismiss: true,
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    timer: 1000,
                });
            });
        }

        // document.addEventListener('DOMContentLoaded', () => {
        //     const searchInput = document.getElementById('searchInput');
        //     const tableContainer = document.querySelector('.table-responsive');
        //     const baseUrl = "{{ route('admin.course.course.index') }}";

        //     searchInput.addEventListener('input', function() {
        //         const searchQuery = this.value;

        //         // Kirim permintaan AJAX ke server
        //         fetch(`${baseUrl}?search=${encodeURIComponent(searchQuery)}`)
        //             .then(response => response.text())
        //             .then(html => {
        //                 // Gantikan isi tabel dengan respons dari server
        //                 const parser = new DOMParser();
        //                 const doc = parser.parseFromString(html, 'text/html');
        //                 const newTable = doc.querySelector('.table-responsive');
        //                 if (newTable) {
        //                     tableContainer.innerHTML = newTable.innerHTML;
        //                 }
        //             })
        //             .catch(error => console.error('Error:', error));
        //     });
        // });
    </script>

    <script>
        // Event listener untuk input pencarian
        document.getElementById('searchCourse').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const courses = document.querySelectorAll('.course-item');

            courses.forEach(course => {
                const namaKelas = course.getAttribute('data-nama-kelas');
                if (namaKelas.includes(searchTerm)) {
                    course.style.display = ''; // Tampilkan
                } else {
                    course.style.display = 'none'; // Sembunyikan
                }
            });
        });
    </script>
@endsection
