@extends('admin.layouts.app')
@section('title', 'Learning')
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
                <div class="card-title">Learning Category</div>
                <a class="btn btn-primary btn-round ms-auto" href="{{ route('admin.category.learning.create') }}">
                    <i class="fa fa-plus me-1"></i>
                    Add
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.category.learning.index') }}" id="formSearch">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl-2">
                            <div class="form-group form-group-default">
                                <label class="d-inline" for="showEntries">Show Entries :</label>
                                <select class="form-control" id="showEntries" name="show">
                                    <option value="15" {{ $show == 15 ? 'selected' : '' }}>15</option>
                                    <option value="30" {{ $show == 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ $show == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $show == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl-2">
                            <div class="form-group px-0">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Cari..." name="search" id="searchInput"
                                        value="{{ $search ?? '' }}">
                                    @if (!empty($search))
                                        <a href="{{ route('admin.category.learning.index') }}"
                                            class="input-icon-addon">
                                            <i class="fa fa-times-circle text-danger"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 3%">No</th>
                                <th class="text-center" style="width: 10%">Gambar</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Deskripsi</th>
                                <th class="text-center" colspan="2" style="width: 20%"><i class="fas fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <th class="text-center" scope="row"> {{ $data->firstItem() + $loop->index }}</th>
                                    <td class="text-center">
                                        <img src="{{ Storage::url('category/learning/'.$item->image) }}" alt="{{ $item->image }}"
                                                            class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{!! $item->deskripsi !!}</td>
                                    <td class="text-center">
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
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.category.learning.edit', $item->id) }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ubah"
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
                                                        action="{{ route('admin.category.learning.destroy', $item->id) }}"
                                                        class="d-inline my-1" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <div class="modal-body">
                                                            <h4 class="text-danger mb-4">Hapus data</h4>
                                                            <p>
                                                                Apakah Anda Yakin? <br> Menghapus Data Ini Akan Menghapus Divisi dan Kategori Yang Terelasi!!
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

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {!! $data->links() !!}
                    </div>
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
                url: "{{ url('/admin/category/learning/') }}" + "/" + id,
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

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const tableContainer = document.querySelector('.table-responsive');
            const baseUrl = "{{ route('admin.category.learning.index') }}";

            searchInput.addEventListener('input', function () {
                const searchQuery = this.value;

                // Kirim permintaan AJAX ke server
                fetch(`${baseUrl}?search=${encodeURIComponent(searchQuery)}`)
                    .then(response => response.text())
                    .then(html => {
                        // Gantikan isi tabel dengan respons dari server
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.querySelector('.table-responsive');
                        if (newTable) {
                            tableContainer.innerHTML = newTable.innerHTML;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

    </script>
@endsection
