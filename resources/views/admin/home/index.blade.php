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

        .chart-container {
            position: relative;
            /* Membuat container menjadi relatif agar elemen lain dapat diposisikan absolut */
        }

        #formatted-average-time {
            position: absolute;
            /* Memposisikan elemen secara absolut */
            top: 45%;
            /* Menempatkan elemen di tengah secara vertikal */
            left: 50%;
            /* Menempatkan elemen di tengah secara horizontal */
            transform: translate(-50%, -50%);
            /* Menyesuaikan agar elemen benar-benar di tengah */
            font-size: 20px;
            /* Ukuran font */
            color: black;
            /* Warna teks */
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
                                            <p class="card-category">Total Course Enrolls</p>
                                            <h4 class="card-title">{{ $totalCourseEnrolls }}</h4>
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
                <div class="mb-3 container d-flex justify-content-center align-items-center">
                    <select id="divisi" class="form-control" style="width: 30%;">
                        <option value="">Pilih Divisi</option>
                        <option value="Semua Divisi">SEMUA DIVISI</option>
                        @foreach ($divisionFilter as $divisi)
                            <option value="{{ $divisi }}">{{ $divisi }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="mb-3 container d-flex justify-content-start align-items-center">
                    <select id="tahun" class="form-control" style="width: 50%;">
                        <option value="semuatahun">Semua Tahun</option>
                        @foreach ($years as $year)
                            <option value="tahun{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div> --}}
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Grafik Peserta Pelatihan</div>
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
                        <div class="card-title">Durasi Pembelajaran</div>
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
                        <div class="card-title">Waktu Akses</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="loginAverage" style="width: 50%; height: 50%"></canvas>
                            <span id="formatted-average-time">{{ $formattedAverageLoginTime }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Rata-rata Progess Pelatihan</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container d-flex flex-column justify-content-center align-items-center">
                            <div id="progress_user"></div>
                            <div class="chart-legend d-flex align-items-center mt-3">
                                <span class="legend-box me-2" style="background-color: #6f42c1;"></span>
                                <span id="selected-divisi">Semua Divisi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Perbandingan Progress Pelatihan</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="courseComparison"></canvas>
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
                    let startDate = info.event.start;
                    let rawEnd = info.event.end;

                    // Jika `end` kosong, anggap sama dengan `start`
                    let endDate = rawEnd ? new Date(rawEnd.getTime() - 86400000) : startDate;

                    // Isi data ke dalam modal
                    document.getElementById('modalPelatihan').innerText = info.event.title;
                    document.getElementById('modalNama').innerText = info.event.extendedProps.nama ||
                        '-';
                    document.getElementById('modalDivisi').innerText = info.event.extendedProps
                        .divisi || '-';

                    let tanggalRentang;

                    if (startDate.toDateString() === endDate.toDateString()) {
                        tanggalRentang = startDate.toLocaleDateString() + ' - ' + endDate
                            .toLocaleDateString();
                    } else {
                        tanggalRentang = startDate.toLocaleDateString() + ' - ' + endDate
                            .toLocaleDateString();
                    }

                    document.getElementById('modalTanggal').innerText = tanggalRentang;

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
            const selectDivisi = $('#divisi').selectize({
                create: false, // Tidak membuat pilihan baru
                sortField: 'text', // Menyortir berdasarkan teks
                onChange: function(selectedDivisi) {
                    if (selectedDivisi) {
                        $('#selected-divisi').text(selectedDivisi);
                        var divisiLabel = selectedDivisi === 'Semua Divisi' ? 'Semua Divisi' :
                            selectedDivisi;

                        fetch("{{ url('admin/get-chart-data') }}?divisi=" + selectedDivisi)
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                // Update monthly chart
                                mygrowthParticipant.data.datasets[0].data = data.monthlyData;
                                mygrowthParticipant.update();

                                // Update division time spend chart
                                mytimeSpend.data.labels = data.divisionLabels;
                                mytimeSpend.data.datasets[0].data = data.divisionData;
                                mytimeSpend.update();

                                // Update login average chart
                                myloginAverage.data.datasets[0].data = data.loginAverage;
                                myloginAverage.update();

                                // Update formatted average login time
                                const formattedAverageLoginTime = data.formattedAverageLoginTime;
                                document.getElementById('formatted-average-time').textContent =
                                    formattedAverageLoginTime;

                                // Update progress chart
                                const averageProgress = data.averageProgress || 0;
                                Circles.create({
                                    id: 'progress_user',
                                    radius: 100,
                                    value: averageProgress,
                                    maxValue: 100,
                                    width: 10,
                                    text: function(value) {
                                        return '<span style="font-size: 30px; line-height: 1;">' +
                                            value.toFixed(1) + '%</span>';
                                    },
                                    colors: ['#eee', '#6f42c1'],
                                    duration: 400,
                                    wrpClass: 'circles-wrp',
                                    textClass: 'circles-text',
                                    styleWrapper: true,
                                    styleText: true
                                });

                                // Update course comparison chart
                                mycourseComparison.data.labels = [divisiLabel];
                                mycourseComparison.data.datasets[0].data = [data.inProgressData];
                                mycourseComparison.data.datasets[1].data = [data.completedData];
                                mycourseComparison.update();
                            })
                            .catch(function(error) {
                                console.error('Error fetching chart data:', error);
                            });
                    }
                }
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
            loginAverage = document.getElementById('loginAverage').getContext('2d');

        Circles.create({
            id: 'progress_user',
            radius: 100,
            value: @json($averageProgress),
            maxValue: 100,
            width: 10,
            text: function(value) {
                return '<span style="font-size: 30px; line-height: 1;">' + value.toFixed(1) + '%</span>';
            },
            colors: ['#eee', '#6f42c1'],
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
                    label: "Peserta Enrolled",
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
                    label: "Rata-rata Durasi Pembelajaran (menit)",
                    backgroundColor: '#d63384',
                    borderColor: '#d63384',
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
                plugins: {
                    tooltip: {
                        enabled: true
                    }
                }
            },
            plugins: [{
                id: 'barValues',
                afterDatasetsDraw: function(chart) {
                    var ctx = chart.ctx;
                    chart.data.datasets.forEach(function(dataset, i) {
                        var meta = chart.getDatasetMeta(i);
                        meta.data.forEach(function(bar, index) {
                            var value = dataset.data[index];
                            ctx.fillStyle = '#000'; // Warna teks
                            ctx.font = '12px Arial'; // Gaya font
                            ctx.textAlign = 'left';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(value, bar._model.x + 5, bar._model
                                .y); // Posisi teks
                        });
                    });
                }
            }]
        });

        var divisiLabel = "Semua Divisi";
        var mycourseComparison = new Chart(courseComparison, {
            type: 'bar',
            data: {
                labels: [divisiLabel],
                datasets: [{
                        label: "Course in Progress",
                        backgroundColor: '#fdaf4b', // Merah untuk in progress
                        borderColor: '#fdaf4b', // Border sesuai dengan warna background
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
                plugins: {
                    tooltip: {
                        enabled: true
                    }
                }
            },
            plugins: [{
                id: 'barValues',
                afterDatasetsDraw: function(chart) {
                    var ctx = chart.ctx;

                    // Hitung total data untuk persentase
                    var total = chart.data.datasets.reduce((sum, dataset) => {
                        return sum + dataset.data[0];
                    }, 0);

                    chart.data.datasets.forEach(function(dataset, i) {
                        var meta = chart.getDatasetMeta(i);
                        meta.data.forEach(function(bar, index) {
                            var value = dataset.data[index];
                            var percentage = ((value / total) * 100).toFixed(2) +
                                '%'; // Hitung persen
                            ctx.fillStyle = '#fff'; // Warna teks
                            ctx.font = '12px Arial'; // Gaya font
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';

                            // Posisi teks di atas bar
                            ctx.fillText(percentage, bar._model.x, bar._model.y - -20);
                        });
                    });
                }
            }]
        });

        var myloginAverage = new Chart(loginAverage, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $loginCategoryData['Jam Kerja (07:00 - 17:30)'] }},
                        {{ $loginCategoryData['Luar Jam Kerja (17:31 - 06:59)'] }}
                    ],
                    backgroundColor: ['#0d6efd', '#fdaf4b']
                }],
                labels: [
                    'Jam Kerja (07:00 - 17:30)',
                    'Luar Jam Kerja (17:31 - 06:59)'
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
                },
                plugins: {
                    tooltip: {
                        enabled: true
                    }
                }
            },
            plugins: [{
                id: 'percentages',
                afterDatasetsDraw: function(chart) {
                    var ctx = chart.ctx;
                    var total = chart.data.datasets[0].data.reduce((sum, value) => sum + value,
                        0); // Hitung total data
                    var meta = chart.getDatasetMeta(0);

                    chart.data.datasets[0].data.forEach(function(value, index) {
                        var percentage = ((value / total) * 100).toFixed(2) +
                            '%'; // Hitung persen
                        var model = meta.data[index]._model; // Model untuk sektor tertentu
                        var midAngle = (model.startAngle + model.endAngle) /
                            2; // Sudut tengah sektor
                        var radius = (model.outerRadius + model.innerRadius) /
                            2; // Posisi radius tengah

                        // Hitung posisi teks
                        var x = model.x + Math.cos(midAngle) * radius;
                        var y = model.y + Math.sin(midAngle) * radius;

                        // Gambar persentase
                        ctx.fillStyle = '#fff'; // Warna teks
                        ctx.font = '12px Arial'; // Gaya font
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(percentage, x, y);
                    });
                }
            }]
        });
    </script>

@endsection
