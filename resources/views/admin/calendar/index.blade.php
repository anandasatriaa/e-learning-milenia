@extends('admin.layouts.app')
@section('title', 'Calendar')

@section('css')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.css' rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.css' rel="stylesheet">
    {{-- Selectize --}}
    <link href="{{ asset('vendor/selectize/selectize.bootstrap5.css') }}" rel="stylesheet" crossorigin="anonymous" />

    <style>
        .fc .fc-button-primary:disabled {
            background-color: #0d6efd !important;
        }

        .fc-toolbar .fc-button {
            background-color: #0d6efd !important;
        }
    </style>

@endsection

@section('content')
    <div class="page-inner">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-header">
                <div class="card-body row">
                    <div class="col-6 col-md-6" id="calendar"></div>

                    <!-- Form Input Acara -->
                    <div class="mt-4 col-6 col-md-6">
                        <h5>Input Pelatihan</h5>
                        <form action="{{ route('admin.calendar.calendar.store') }}" method="POST" id="eventForm">
                            @csrf
                            <!-- Input Nama Acara -->
                            <div class="mb-3">
                                <label for="eventName" class="form-label">Nama Pelatihan</label>
                                <input type="text" id="eventName" name="eventName" class="form-control"
                                    placeholder="Masukkan nama pelatihan" required>
                            </div>

                            <!-- Dropdown Divisi -->
                            <div class="mb-3">
                                <label for="division" class="form-label">Divisi</label>
                                <select id="division" name="division" class="form-select form-dropdown" required>
                                    <option value="">Pilih Divisi</option>
                                    @foreach ($usersWithFoto->pluck('Divisi')->unique() as $division)
                                        <option value="{{ $division }}">{{ $division }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown Nama -->
                            <div class="mb-3">
                                <label for="personName" class="form-label">Nama</label>
                                <small class="text-danger d-block" style="font-size: 10px">*Nama tidak wajib diinput</small>
                                <div class="dropdown">
                                    <select id="personName" name="personName" class="form-select">
                                        <option value="">Pilih Nama</option>
                                        @foreach ($usersWithFoto as $user)
                                            <option value="{{ $user->Nama }}" data-foto="{{ $user->fotoUrl }}">
                                                {{ $user->Nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Input Rentang Tanggal -->
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Tanggal Mulai</label>
                                <input type="date" id="startDate" name="startDate" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="endDate" class="form-label">Tanggal Selesai</label>
                                <input type="date" id="endDate" name="endDate" class="form-control" required>
                            </div>

                            <!-- Input Warna Background -->
                            <div class="mb-3">
                                <label for="backgroundColor" class="form-label">Pilih Warna Background</label>
                                <input type="color" id="backgroundColor" name="backgroundColor"
                                    class="form-control form-control-color" value="#ff0000" required>
                            </div>

                            <!-- Button Submit -->
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hapus Jadwal Pelatihan</h4>
            </div>
            <div class="card-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-hapus" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Pelatihan</th>
                                    <th>Nama/Divisi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Color</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $event->acara }}</td>
                                        <td>
                                            @if ($event->nama)
                                                {{ $event->nama }} - {{ $event->divisi }}
                                            @else
                                                {{ $event->divisi }}
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}</td>
                                        <td><span class="badge"
                                                style="background-color: {{ $event->bg_color }}">{{ $event->bg_color }}</span>
                                        </td>
                                        <td>
                                            <button type="button" id="btnHapus_{{ $event->id }}"
                                                class="btn btn-icon btn-round btn-danger" data-id="{{ $event->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Progress -->
    <div class="modal" id="modalStore" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="progress-card my-auto">
                        <div class="progress-status">
                            <span class="text-muted" id="progress_title_status">Proses Upload Data</span>
                            <span class="text-muted fw-bold" id="progress_status">0%</span>
                        </div>
                        <div class="progress">
                            <div id="progress_bar"
                                class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" data-toggle="tooltip" data-placement="top">
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
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>
    {{-- Selectize --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Calendar --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                events: @json($eventsForFullCalendar), // Menggunakan data events yang sudah diproses
            });

            calendar.render();

            // Tangani perubahan warna background
            const backgroundColorInput = document.getElementById('backgroundColor');
            backgroundColorInput.addEventListener('input', function() {
                this.style.backgroundColor = this.value; // Mengatur warna background elemen input
            });

            // Tangani Form Submit
            document.getElementById('eventForm').addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form has been submitted');

                var eventName = document.getElementById('eventName').value;
                var personName = document.getElementById('personName').value;
                var division = document.getElementById('division').value;
                var startDate = document.getElementById('startDate').value;
                var endDate = document.getElementById('endDate').value;
                var backgroundColor = document.getElementById('backgroundColor').value;

                // Output data ke console
                console.log('Data yang didapat:');
                console.log('Nama Acara:', eventName);
                console.log('Nama:', personName);
                console.log('Divisi:', division);
                console.log('Tanggal Mulai:', startDate);
                console.log('Tanggal Selesai:', endDate);
                console.log('Warna Latar Belakang:', backgroundColor);

                if (eventName && division && startDate && endDate) {
                    // Ambil user_id berdasarkan personName jika ada
                    var userId = personName ? getUserIdByPersonName(personName) : null;

                    // Log user_id untuk debugging
                    console.log('User ID:', userId); // Menambahkan log untuk user_id

                    // Mengirimkan data menggunakan AJAX
                    $.ajax({
                        url: "{{ route('admin.calendar.calendar.store') }}",
                        method: 'POST',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            eventName: eventName,
                            personName: personName,
                            division: division,
                            startDate: startDate,
                            endDate: endDate,
                            backgroundColor: backgroundColor,
                            userId: getUserIdByPersonName(
                                personName
                            ) // Menambahkan user_id (null jika personName tidak ada)
                        },
                        beforeSend: function() {
                            // Tampilkan modal progress sebelum request dikirim
                            $('#modalStore').modal('show');
                            $('#progress_bar').css('width', '0%');
                            $('#progress_status').text('0%');
                        },
                        success: function(response) {
                            console.log('Data berhasil disimpan:', response);

                            // Update progress ke 100% saat berhasil
                            $('#progress_bar').css('width', '100%');
                            $('#progress_status').text('100%');

                            // Menambahkan event ke calendar setelah berhasil
                            calendar.addEvent({
                                id: response
                                    .id, // Menggunakan ID yang dikirimkan dari server
                                title: response.title, // Judul event
                                start: response.start, // Tanggal mulai
                                end: response.end, // Tanggal selesai
                                backgroundColor: response
                                    .backgroundColor // Warna latar belakang
                            });

                            // Reset form input
                            document.getElementById('eventForm').reset();
                            backgroundColorInput.style.backgroundColor = '#ffffff';

                            swal("Jadwal berhasil ditambahkan!", {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                }
                                }).then(() => {
                                    // Setelah swal ditutup, lakukan refresh halaman
                                    location.reload();
                                });
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan saat mengirim data:', error);
                            $('#progress_status').text('Gagal!');
                            $('#progress_bar').css('width', '100%');
                        },
                        complete: function() {
                            // Menyembunyikan spinner loading setelah request selesai
                            setTimeout(function() {
                                $('#modalStore').modal('hide');
                            }, 500); // Delay sejenak sebelum modal ditutup
                        }
                    });
                }
            });
        });


        // Fungsi untuk mendapatkan user_id berdasarkan personName
        function getUserIdByPersonName(personName) {
            // Mengambil data pengguna dari JavaScript yang dikirim dari controller
            var users =
                @json($usersWithFoto); // Mengambil data pengguna dari server dan mengonversinya menjadi array JavaScript

            // Cari user berdasarkan nama
            var user = users.find(user => user.Nama === personName);
            return user ? user.ID : null; // Mengembalikan user_id atau null jika tidak ditemukan
        }
    </script>

    {{-- Selectize --}}
    <script>
        // Inisialisasi Selectize
        $('#personName').selectize({
            render: {
                option: function(data, escape) {
                    // Template untuk opsi dengan gambar
                    return `
                    <div class="option">
                        <img src="${escape(data.foto)}" alt="Foto" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%; margin-right: 10px;">
                        ${escape(data.text)}
                    </div>`;
                },
                item: function(data, escape) {
                    // Template untuk opsi yang dipilih
                    return `
                    <div class="item">
                        <img src="${escape(data.foto)}" alt="Foto" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%; margin-right: 10px;">
                        ${escape(data.text)}
                    </div>`;
                }
            },
            onInitialize: function() {
                // Tambahkan data `foto` dari atribut data-foto
                const selectize = this;
                $('#personName option').each(function() {
                    const foto = $(this).data('foto');
                    const value = $(this).val();
                    const text = $(this).text();
                    selectize.addOption({
                        value: value,
                        text: text,
                        foto: foto
                    });
                });
            }
        });
    </script>

<script>
    $(document).ready(function() {
        $('.form-dropdown').selectize({
        });
    });
</script>

    {{-- Data Table --}}
    <script>
        $(document).ready(function() {
            $('#table-hapus').DataTable({});
        });
    </script>

    {{-- Button Hapus --}}
    <script>
        $(document).on('click', '[id^="btnHapus_"]', function(e) {
            e.preventDefault(); // Mencegah aksi default tombol jika sudah terhubung ke form

            let eventId = $(this).data('id'); // Mengambil ID dari tombol yang diklik

            swal({
                title: 'Yakin menghapus ini?',
                text: "Anda tidak dapat mengembalikannya!",
                type: 'warning',
                buttons: {
                    cancel: {
                        visible: true,
                        text: 'Tidak!',
                        className: 'btn btn-danger'
                    },
                    confirm: {
                        text: 'Ya, hapus!',
                        className: 'btn btn-success'
                    }
                }
            }).then((willDelete) => {
                if (willDelete) {
                    // Mengirim permintaan DELETE menggunakan Ajax
                    $.ajax({
                        url: `{{ url('admin/calendar/course-schedule/calendar/destroy') }}/${eventId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Token CSRF untuk keamanan
                        },
                        success: function(response) {
                            swal("Data berhasil dihapus!", {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                }
                            });
                            // Menghapus baris data di tabel setelah dihapus
                            $('#btnHapus_' + eventId).closest('tr')
                                .remove(); // Menghapus baris yang sesuai

                            swal("Jadwal berhasil dihapus!", {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                }
                                }).then(() => {
                                    // Setelah swal ditutup, lakukan refresh halaman
                                    location.reload();
                                });
                        },
                        error: function() {
                            swal("Error!", "Something went wrong.", "error");
                        }
                    });
                } else {
                    swal("Data tidak jadi dihapus!", {
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        }
                    });
                }
            });
        });
    </script>

@endsection
