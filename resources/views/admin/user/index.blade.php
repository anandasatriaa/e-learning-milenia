@extends('admin.layouts.app')
@section('title', 'Karyawan')
@section('css')
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 0px 8px !important;
        }

        .alert-primary .progress {
            height: 6px;
            margin-top: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title">Karyawan Milenia Group</div>
                <a class="btn btn-primary btn-round ms-auto" href="#" id="syncBtn">
                    <i class="fa fa-sync me-1"></i>
                    Sync
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%">No</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Cabang</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">Golongan</th>
                                <th class="text-center">Status Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modalStore" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="progress-card my-auto">
                        <div class="progress-status">
                            <span class="text-muted" id="progress_title_status">Proses Upload Data</span>
                            <span class="text-muted fw-bold" id="progress_status"></span>
                        </div>
                        <div class="progress">
                            <div id="progress_bar"
                                class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                data-toggle="tooltip" data-placement="top">
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
@endsection
@section('js')
    <script src="{{ asset('js/core/jq-ajax-progress.min.js') }}"></script>
    <script>
        function formatToFiveDigits(number) {
            return String(number).padStart(5, '0');
        }

        $('#basic-datatables').DataTable({
            ajax: {
                url: "{{ route('admin.user.datatable-getAllEmployee') }}",
                dataSrc: 'data'
            },
            columns: [{
                    data: "ID",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'ID',
                    className: "text-center",
                },
                {
                    data: 'ID',
                    className: "text-center",
                    render: function(data, type, row) {
                        return `
                        <div class="avatar avatar-xxl p-2">
                            <img class="avatar-img " src="http://192.168.0.8/hrd-milenia/foto/${formatToFiveDigits(data)}.JPG" alt="img_${formatToFiveDigits(data)}">
                        </div>
                        `
                    }
                },
                {
                    data: 'Nama'
                },
                {
                    data: 'Jabatan'
                },
                {
                    data: 'Cabang'
                },
                {
                    data: 'Divisi'
                },
                {
                    data: 'Golongan'
                },
                {
                    data: 'statuskar'
                },
            ]
        });

        $('#syncBtn').click(function(e) {
            e.preventDefault();
            const modalStore = new bootstrap.Modal(
                document.getElementById("modalStore")
            );
            modalStore.show();

            var jqxhr = $.ajax({
                method: "GET",
                url: "{{ route('admin.user.api-sync') }}",
                cache: false,
                contentType: false,
                processData: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', uploadProgress, false); // Attach progress event handler
                    return xhr;
                }
            });

            jqxhr.done(function(data) {
                modalStore.hide();
                $.notify({
                    icon: "icon-check",
                    title: 'Sukses',
                    message: 'Berhasil melakukan sinkronisasi',
                }, {
                    type: "info",
                    allow_dismiss: true,
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    showProgressbar: true,
                    timer: 500,
                    delay: 1500,
                    onClose: redirectOnClose
                });
            }).fail(function(data) {
                modalStore.hide();
                $.notify({
                    icon: "icon-close",
                    title: 'Gagal',
                    message: `(${data.status}) ${data.responseJSON.message}`,
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

            function redirectOnClose() {
                window.location.replace("{{ route('admin.user.employee.index') }}");
            }

            // Event handler to update progress bar
            function uploadProgress(e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded * 100) / e.total;
                    $('#progress_status').text(Math.round(percentComplete) + '%');
                    $('#progress_bar').css('width', percentComplete + '%');
                    $('#progress_bar').attr('aria-valuenow', percentComplete);
                    if (percentComplete >= 100) {
                        $('#progress_title_status').text('Menyelesaikan proses...');
                    }
                }
            }
        });
    </script>

@endsection
