@extends('admin.layouts.app')
@section('title', 'Dashboard')
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

        .circles-text {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            /* Memastikan teks berada di tengah secara vertikal */
            line-height: normal;
            /* Mencegah teks terlihat terlalu jauh ke bawah */
        }

        .legend-box {
            width: 30px;
            height: 15px;
            /* Opsional, buat kotak dengan sudut membulat */
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
                                <h3 class="op-7 mb-2">You can manage everything here!</h3>
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
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-user-friends"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Total Participants</p>
                                            <h4 class="card-title">{{ $totalParticipants }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-sm-6 col-md-6">
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
                                            <p class="card-category">Total Course</p>
                                            <h4 class="card-title">{{ $totalCourses }}</h4>
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

        <div class="row">
            <h3 class="fw-bold mb-3 text-center">Dashboard Analytic</h3>
            <div class="d-flex justify-content-center align-items-center">
                <div class="mb-3 container d-flex justify-content-end align-items-center">
                    <select id="divisi" class="form-control" style="width: 50%;">
                        <option value="">Semua Divisi</option>
                        <option value="divisi1">Divisi 1</option>
                        <option value="divisi2">Divisi 2</option>
                        <option value="divisi3">Divisi 3</option>
                        <option value="divisi4">Divisi 4</option>
                    </select>
                </div>
                <div class="mb-3 container d-flex justify-content-start align-items-center">
                    <select id="tahun" class="form-control" style="width: 50%;">
                        <option value="">Semua Tahun</option>
                        <option value="tahun1">2025</option>
                        <option value="tahun2">2026</option>
                        <option value="tahun3">2027</option>
                        <option value="tahun4">2028</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Growth of Learning Participant</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="growthParticipant"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Time Spend</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="timeSpend"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Login Average</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="loginAverage" style="width: 50%; height: 50%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Average Training Progress</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container d-flex flex-column justify-content-center align-items-center">
                            <div id="progress_user"></div>
                            <div class="chart-legend d-flex align-items-center mt-3">
                                <span class="legend-box bg-primary me-2"></span>
                                <span>Semua Divisi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Course Comparison</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="courseComparison"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Indikator Penilaian</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="radarChart"></canvas>
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
                    <p class="text-center"><b>Ini adalah rentang tanggal Peserta harus selesai mengerjakan course</b></p>
                    <ul>
                        <li>
                            <p><b>Pelatihan:</b> <span id="modalPelatihan"></span></p>
                        </li>
                        <li>
                            <p><b>Nama:</b> <span id="modalNama"></span></p>
                        </li>
                        <li>
                            <p><b>Divisi:</b> <span id="modalDivisi"></span></p>
                        </li>
                        <li>
                            <p><b>Rentang Tanggal:</b> <span id="modalTanggal"></span></p>
                        </li>
                    </ul>
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
    {{-- Selectize --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Full Calendar --}}
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
                        new Date(info.event.end.getTime() - 86400000) : null;

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

    {{-- Dropdown Selectize --}}
    <script>
        // Inisialisasi Selectize untuk dropdown
        $(document).ready(function() {
            $('#divisi').selectize({
                create: false, // Tidak membuat pilihan baru
                sortField: 'text' // Menyortir berdasarkan teks
            });
        });
        $(document).ready(function() {
            $('#tahun').selectize({
                create: false, // Tidak membuat pilihan baru
                sortField: 'text' // Menyortir berdasarkan teks
            });
        });
    </script>

    {{-- Chart.js --}}
    <script>
        var growthParticipant = document.getElementById('growthParticipant').getContext('2d'),
            timeSpend = document.getElementById('timeSpend').getContext('2d'),
            courseComparison = document.getElementById('courseComparison').getContext('2d'),
            loginAverage = document.getElementById('loginAverage').getContext('2d'),
            radarChart = document.getElementById('radarChart').getContext('2d');

        Circles.create({
            id: 'progress_user',
            radius: 100,
            value: @json($averageProgress),
            maxValue: 100,
            width: 10,
            text: function(value) {
                return '<span style="font-size: 30px; line-height: 1;">' + value.toFixed(1) + '%</span>';
            },
            colors: ['#eee', '#177dff'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        });

        var monthlyData = @json(array_values($monthlyData));
        var mygrowthParticipant = new Chart(growthParticipant, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Participants",
                    borderColor: "#1d7af3",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#1d7af3",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: 'transparent',
                    fill: true,
                    borderWidth: 2,
                    data: monthlyData
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        fontColor: '#1d7af3',
                    }
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                layout: {
                    padding: {
                        left: 15,
                        right: 15,
                        top: 15,
                        bottom: 15
                    }
                }
            }
        });

        var mytimeSpend = new Chart(timeSpend, {
            type: 'horizontalBar',
            data: {
                labels: @json($divisionLabels),
                datasets: [{
                    label: "Average Time Spend (in minutes)",
                    backgroundColor: 'rgb(23, 125, 255)',
                    borderColor: 'rgb(23, 125, 255)',
                    data: @json($divisionData),
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
            }
        });

        var mycourseComparison = new Chart(courseComparison, {
            type: 'bar',
            data: {
                labels: ["Semua Divisi"],
                datasets: [{
                        label: "Course in Progress",
                        backgroundColor: '#f3545d', // Merah untuk in progress
                        borderColor: '#f3545d', // Border sesuai dengan warna background
                        data: [{{ $inProgressData }}],
                    },
                    {
                        label: "Course Completed",
                        backgroundColor: '#198754', // Hijau untuk completed
                        borderColor: '#198754', // Border sesuai dengan warna background
                        data: [{{ $completedData }}],
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
            }
        });

        var myloginAverage = new Chart(loginAverage, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [10, 20, 30],
                    backgroundColor: ['#f3545d', '#fdaf4b', '#1d7af3']
                }],

                labels: [
                    'Red',
                    'Yellow',
                    'Blue'
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20
                    }
                }
            }
        });

        var myRadarChart = new Chart(radarChart, {
            type: 'radar',
            data: {
                labels: ['Running', 'Swimming', 'Eating', 'Cycling', 'Jumping'],
                datasets: [{
                    data: [20, 10, 30, 2, 30],
                    borderColor: '#1d7af3',
                    backgroundColor: 'rgba(29, 122, 243, 0.25)',
                    pointBackgroundColor: "#1d7af3",
                    pointHoverRadius: 4,
                    pointRadius: 3,
                    label: 'Team 1'
                }, {
                    data: [10, 20, 15, 30, 22],
                    borderColor: '#716aca',
                    backgroundColor: 'rgba(113, 106, 202, 0.25)',
                    pointBackgroundColor: "#716aca",
                    pointHoverRadius: 4,
                    pointRadius: 3,
                    label: 'Team 2'
                }, ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                }
            }
        });
    </script>

@endsection
