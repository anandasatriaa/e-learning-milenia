@extends('pages.layouts.app')
@section('title', 'Dashboard')
@section('css')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.css' rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.css' rel="stylesheet">
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
        <div class="row">
            <!-- Kolom untuk Card Selamat Datang -->
            <div class="col-md-8">
                <div class="card border-0">
                    <div class="card-body px-5">
                        <div class="row align-items-center">
                            <!-- Kolom untuk teks -->
                            <div class="col-md-8 text-center text-md-start">
                                <h1 class="fw-bold mb-3">Hello,
                                    {{ ucfirst(strtolower(Str::before(Auth::user()->Nama, ' '))) }} !</h1>
                                <h3 class="op-7 mb-2">Lets learn something today!</h3>
                            </div>
                            <!-- Kolom untuk gambar -->
                            <div class="col-md-4 text-center text-md-end">
                                <img src="{{ asset('img/hello-rafiki.svg') }}" alt="Hello Img" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-angle-double-right"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Progress</p>
                                            <h4 class="card-title">{{ number_format($averageProgress, 2) }}%</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="fas fa-tachometer-alt"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Time Spend</p>
                                            <h4 class="card-title">{{ number_format($totalTimeSpendInHours, 2) }} hrs</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-book-reader"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Course In Progress</p>
                                            <h4 class="card-title">{{ $coursesInProgress }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom untuk Kalender -->
            <div class="col-md-4">
                <!-- Tambahkan kalender di sini (misalnya menggunakan library seperti FullCalendar atau lainnya) -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Calendar</h5>
                        <!-- Kalender content -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom untuk Card Data -->


        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"> <span class="bg-light p-1 rounded me-1">
                                    <i class="icon-graph"></i></span>
                                Progress Pelatihan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($courseEnrolled as $courseEnrolleds)
                                <div class="col-12 col-md-6">
                                    <div
                                        class="card card-stats card-round shadow-none {{ $courseEnrolleds->status != 'complete' ? 'bg-light' : 'bg-white border' }}">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-icon">
                                                    <img class="w-100 rounded"
                                                        src="{{ $courseEnrolleds->course->thumbnail_url }}"
                                                        alt="{{ $courseEnrolleds->course->thumbnail_url }}">
                                                </div>
                                                <div class="col ms-3 ms-sm-0">
                                                    <div class="number">
                                                        <h5 class="card-text text-truncate" style="line-height: 1.5rem;">
                                                            {{ $courseEnrolleds->course->nama_kelas }}</h5>
                                                        <h6 class="text-muted"
                                                            style="font-size: 0.8rem; line-height: 0.8rem;">
                                                            {{ $courseEnrolleds->modul_count }} Modul
                                                        </h6>
                                                        <div class="progress-card">
                                                            <div class="progress-status">
                                                                <span class="text-muted">
                                                                    @if (
                                                                        $courseEnrolleds->course->subCategory &&
                                                                            $courseEnrolleds->course->category &&
                                                                            $courseEnrolleds->course->divisiCategory &&
                                                                            $courseEnrolleds->course->learningCategory)
                                                                        {{ $courseEnrolleds->course->learningCategory->nama }}
                                                                        >
                                                                        {{ $courseEnrolleds->course->divisiCategory->nama }}
                                                                        >
                                                                        {{ $courseEnrolleds->course->category->nama }} >
                                                                        {{ $courseEnrolleds->course->subCategory->nama }}
                                                                    @elseif (
                                                                        $courseEnrolleds->course->category &&
                                                                            $courseEnrolleds->course->divisiCategory &&
                                                                            $courseEnrolleds->course->learningCategory)
                                                                        {{ $courseEnrolleds->course->learningCategory->nama }}
                                                                        >
                                                                        {{ $courseEnrolleds->course->divisiCategory->nama }}
                                                                        >
                                                                        {{ $courseEnrolleds->course->category->nama }}
                                                                    @elseif ($courseEnrolleds->course->divisiCategory && $courseEnrolleds->course->learningCategory)
                                                                        {{ $courseEnrolleds->course->learningCategory->nama }}
                                                                        >
                                                                        {{ $courseEnrolleds->course->divisiCategory->nama }}
                                                                    @elseif ($courseEnrolleds->course->learningCategory)
                                                                        {{ $courseEnrolleds->course->learningCategory->nama }}
                                                                    @else
                                                                        No Category
                                                                    @endif
                                                                </span>
                                                                <span
                                                                    class="text-muted fw-bold">{{ $courseEnrolleds->progress }}%</span>
                                                            </div>
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                    style="width: {{ $courseEnrolleds->progress }}%"
                                                                    aria-valuenow="{{ $courseEnrolleds->progress }}"
                                                                    aria-valuemin="0" aria-valuemax="100"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="{{ $courseEnrolleds->progress }}%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if ($courseEnrolleds->status != 'completed')
                                                    <a href="{{ route('pages.course.course.detail', $courseEnrolleds->course_id) }}"
                                                        class="btn btn-label-warning btn-round btn-sm me-2 stretched-link">
                                                        Lanjutkan <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('pages.course.course.detail', $courseEnrolleds->course_id) }}"
                                                        class="btn btn-label-success btn-round btn-sm me-2 stretched-link">
                                                        Selesai <i class="fas fa-search"></i>
                                                    </a>
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
        </div>
    </div>

    <!-- Modal Calendar -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detail Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><b>Ini adalah rentang tanggal Anda harus selesai mengerjakan course.</b></p>
                    <p><b>Pelatihan:</b> <span id="modalPelatihan"></span></p>
                    <p><b>Nama:</b> <span id="modalNama"></span></p>
                    <p><b>Divisi:</b> <span id="modalDivisi"></span></p>
                    <p><b>Rentang Tanggal:</b> <span id="modalTanggal"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Data events yang dikirim dari backend (Laravel)
            var events = @json($events); // Pastikan data events sudah tersedia di controller

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Tampilan kalender bulanan
                events: events, // Menambahkan event yang diambil dari database

                // Menambahkan divisi dan nama ke dalam tampilan event
                eventContent: function(arg) {
                    let content = document.createElement('div');
                    let title = arg.event.title;
                    let nama = arg.event.extendedProps.nama || ''; // Default jika nama tidak ada
                    let divisi = arg.event.extendedProps.divisi || ''; // Default jika divisi tidak ada

                    // Format "title - nama (divisi)"
                    content.innerHTML = `<b>"${title}"</b> - ${nama} (${divisi})`;

                    return {
                        domNodes: [content]
                    };
                },

                eventClick: function(info) {
                    // Konversi tanggal end (exclusive) menjadi inclusive dengan mengurangi 1 hari
                    let endDate = info.event.end ?
                        new Date(info.event.end.getTime() -
                            86400000) // Kurangi 1 hari (86400000 ms = 1 hari)
                        :
                        null;

                    // Isi data ke dalam modal
                    document.getElementById('modalPelatihan').innerText = info.event.title;
                    document.getElementById('modalNama').innerText = info.event.extendedProps.nama ||
                        '-';
                    document.getElementById('modalDivisi').innerText = info.event.extendedProps
                        .divisi || '-';
                    document.getElementById('modalTanggal').innerText =
                        info.event.start.toLocaleDateString() +
                        ' - ' +
                        (endDate ? endDate.toLocaleDateString() : 'Tidak ditentukan');

                    // Tampilkan modal
                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    eventModal.show();
                },


                // Menambahkan pengaturan lainnya, jika diperlukan
                eventColor: '#378006', // Menentukan warna event default
            });

            // Render kalender
            calendar.render();
        });
    </script>


@endsection
