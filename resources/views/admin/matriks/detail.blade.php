@extends('admin.layouts.app')
@section('title', 'Detail Dashboard Matriks Kompetensi')
@section('css')
    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 10px !important;
        }

        .card .card-body,
        .card-light .card-body {
            padding: 10px !important;
        }

        .table thead th {
            padding: 10px !important;
        }

        /* .chart {
                    max-width: 250px;
                    margin: 35px auto;
                } */
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <form id="kompetensiForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="divisi_id" value="{{ $divisi->id }}">
            <input type="hidden" name="dashboard_id" value="{{ $dashboard->id ?? '' }}">
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn-danger" id="downloadPdf">
                    <li class="fas fa-file-pdf me-2"></li>Download PDF
                </a>
                <button type="submit" id="simpan" class="btn btn-primary ms-2">
                    <li class="fas fa-save me-2"></li>Simpan
                </button>
            </div>
            <div class="card bg-dark" id="contentToPrint">
                <div class="card-body">
                    <div class="card bg-primary">
                        <div class="card-body d-flex align-items-center">
                            <!-- Logo -->
                            <img src="{{ asset('img/milenia-logo.png') }}" width="70px" alt="Logo" class="me-3">

                            <!-- Konten teks dan input -->
                            <div>
                                <h1 class="card-title text-white my-0" style="line-height: 1.5;">Matriks Kompetensi
                                    {{ $divisi->divisi_name }}
                                </h1>
                                <input type="hidden" name="namaDashboard" id="namaDashboard"
                                    value="Matriks Kompetensi {{ $divisi->divisi_name }}">
                                <p class="card-text text-white my-0" style="line-height: 1.5;">PT Milenia Mega Mandiri</p>
                                <input type="text" id="kodeDashboard" name="kodeDashboard"
                                    value="{{ optional($dashboard)->kode_dashboard }}"
                                    class="form-control form-control-sm text-white border-0"
                                    style="background-color: transparent; line-height: 1.5; padding: 0 0 0 0 !important;"
                                    placeholder="contoh: FO-HR. 58">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="card me-3 border border-primary bg-gray2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <tr>
                                            <td colspan="3" class="fw-bold">TABEL KETERANGAN NILAI</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td class="bg-gray2">PENCAPAIAN</td>
                                            <td class="bg-gray2">ARTI</td>
                                            <td class="bg-gray2">KETERANGAN</td>
                                        </tr>
                                        <tr>
                                            <td>95% - 100%</td>
                                            <td>Sempurna</td>
                                            <td>Bisa dan dapat mengajari</td>
                                        </tr>
                                        <tr>
                                            <td>80% - 94%</td>
                                            <td>Baik Sekali</td>
                                            <td>Bisa tanpa pengawasan</td>
                                        </tr>
                                        <tr>
                                            <td>65% - 79%</td>
                                            <td>Baik</td>
                                            <td>Bisa dalam pengawasan</td>
                                        </tr>
                                        <tr>
                                            <td>20% - 64%</td>
                                            <td>Cukup</td>
                                            <td>Training</td>
                                        </tr>
                                        <tr>
                                            <td>0% - 19%</td>
                                            <td>Kurang</td>
                                            <td>Operator baru</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card me-3 border border-primary bg-gray2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr class="text-center">
                                            <td colspan="3" class="fw-bold">TABEL DEFINISI</td>
                                        </tr>
                                        <tr>
                                            <td>Training</td>
                                            <td>:</td>
                                            <td>Karyawan sudah mampu dan masih dilakukan pendampingan proses kerja</td>
                                        </tr>
                                        <tr>
                                            <td>Karyawan baru</td>
                                            <td>:</td>
                                            <td>Karyawan yang baru bergabung dan belum bisa melakukan proses kerja</td>
                                        </tr>
                                        <tr>
                                            <td>Bisa tanpa pengawasan</td>
                                            <td>:</td>
                                            <td>Karyawan sudah bisa bekerja secara mandiri tanpa perlu diawasi</td>
                                        </tr>
                                        <tr>
                                            <td>Bisa dan dapat mengajari</td>
                                            <td>:</td>
                                            <td>Karyawan sudah bisa bekerja dan mampu untuk mentraining karyawan baru</td>
                                        </tr>
                                        <tr>
                                            <td>Bisa dalam pengawasan</td>
                                            <td>:</td>
                                            <td>Karyawan sudah bisa bekerja namun masih dilakukan monitoring proses kerja
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card border border-primary bg-gray2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <tr class="text-start">
                                            <td colspan="3">
                                                <!-- Membuat d-flex untuk memisahkan teks dan form -->
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <span class="me-2">Tanggal Update:</span>
                                                    <input type="date" class="form-control form-control-sm w-auto"
                                                        name="tanggalUpdate" id="tanggalUpdate"
                                                        value="{{ optional($dashboard)->tgl_update }}" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Dibuat</td>
                                            <td>Diketahui</td>
                                            <td>Disetujui</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- Preview untuk TTD 1 -->
                                                <div id="preview1" class="mb-3">
                                                    @if (!empty($dashboard->image_ttd_1))
                                                        <img src="{{ asset('storage/' . $dashboard->image_ttd_1) }}"
                                                            alt="Preview TTD 1" style="height: 70px;">
                                                    @endif
                                                </div>
                                                <label for="ttd1" class="btn btn-outline-primary btn-sm"
                                                    style="font-size: 12px !important;">Upload TTD</label>
                                                <input type="file" class="form-control form-control-sm" accept="image/*"
                                                    id="ttd1" name="ttd1" onchange="previewImage(event, 'preview1')"
                                                    style="display: none;">
                                            </td>
                                            <td>
                                                <!-- Preview untuk TTD 2 -->
                                                <div id="preview2" class="mb-3">
                                                    @if (!empty($dashboard->image_ttd_2))
                                                        <img src="{{ asset('storage/' . $dashboard->image_ttd_2) }}"
                                                            alt="Preview TTD 2" style="height: 70px;">
                                                    @endif
                                                </div>
                                                <label for="ttd2" class="btn btn-outline-primary btn-sm"
                                                    style="font-size: 12px !important;">Upload TTD</label>
                                                <input type="file" class="form-control form-control-sm" accept="image/*"
                                                    id="ttd2" name="ttd2"
                                                    onchange="previewImage(event, 'preview2')" style="display: none;">
                                            </td>
                                            <td>
                                                <!-- Preview untuk TTD 3 -->
                                                <div id="preview3" class="mb-3">
                                                    @if (!empty($dashboard->image_ttd_3))
                                                        <img src="{{ asset('storage/' . $dashboard->image_ttd_3) }}"
                                                            alt="Preview TTD 3" style="height: 70px;">
                                                    @endif
                                                </div>
                                                <label for="ttd3" class="btn btn-outline-primary btn-sm"
                                                    style="font-size: 12px !important;">Upload TTD</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    accept="image/*" id="ttd3" name="ttd3"
                                                    onchange="previewImage(event, 'preview3')" style="display: none;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" id="nama1" name="nama1"
                                                    value="{{ optional($dashboard)->nama_ttd_1 }}"
                                                    class="form-control form-control-sm text-center border-0"
                                                    placeholder="Nama">
                                                <!-- Input nama biasa -->
                                            </td>
                                            <td>
                                                <input type="text" id="nama2" name="nama2"
                                                    value="{{ optional($dashboard)->nama_ttd_2 }}"
                                                    class="form-control form-control-sm text-center border-0"
                                                    placeholder="Nama">
                                                <!-- Input nama biasa -->
                                            </td>
                                            <td>
                                                <input type="text" id="nama3" name="nama3"
                                                    value="{{ optional($dashboard)->nama_ttd_3 }}"
                                                    class="form-control form-control-sm text-center border-0"
                                                    placeholder="Nama">
                                                <!-- Input nama biasa -->
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($users as $user)
                            @php
                                // Hitung total capaian untuk user ini
                                $totalCapaian = 0;
                                $jumlahKompetensi = count($user->courses); // Jumlah kursus/kompetensi

                                foreach ($user->courses as $course) {
                                    if ($course->presentase_kompetensi !== '-') {
                                        $totalCapaian += $course->presentase_kompetensi;
                                    }
                                }

                                // Hitung rata-rata capaian, jika jumlah kompetensi > 0
                                $rataRataCapaian = $jumlahKompetensi > 0 ? $totalCapaian / $jumlahKompetensi : 0;

                                // Tentukan kategori berdasarkan rata-rata capaian
                                if ($rataRataCapaian >= 95) {
                                    $kategori = 'Bisa dan dapat mengajari';
                                } elseif ($rataRataCapaian >= 80) {
                                    $kategori = 'Bisa tanpa pengawasan';
                                } elseif ($rataRataCapaian >= 65) {
                                    $kategori = 'Bisa dalam pengawasan';
                                } elseif ($rataRataCapaian >= 20) {
                                    $kategori = 'Training';
                                } else {
                                    $kategori = 'Operator baru';
                                }
                            @endphp

                            <div class="col-md-4">
                                <div class="card bg-gray2 border border-primary">
                                    <div class="card-body">
                                        <div class="row mx-0">
                                            <div class="col-xl-4 col-12 my-auto">
                                                @php
                                                    // Siapkan URL foto peserta
                                                    $formattedFoto = str_pad($user->user_id, 5, '0', STR_PAD_LEFT);
                                                    $cacheBuster = time();
                                                    $fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG?v={$cacheBuster}";
                                                @endphp
                                                <img src="{{ $fotoUrl }}" class="d-block mx-auto rounded"
                                                    height="150px" alt="Foto Karyawan" id="userFoto">
                                                <p class="text-center mt-2 fw-bold">{{ $user->user_name }}</p>
                                            </div>
                                            <div class="col-xl-8 col-12">
                                                <div id="radarChart{{ $user->user_id }}" class="chart"></div>
                                            </div>
                                        </div>
                                        <div class="row mx-0">
                                            <div class="col-md-5 text-center bg-primary text-white">
                                                <div style="font-size: 12px">RATA-RATA CAPAIAN NILAI</div>
                                                <div>
                                                    {{ $rataRataCapaian !== 0 ? number_format($rataRataCapaian) . '%' : '-' }}
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-7 text-center d-flex align-items-center justify-content-center bg-info fw-bold">
                                                Kategori: {{ $kategori }}
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table p-0">
                                                <thead>
                                                    <tr>
                                                        <th style="font-size: 10px">KOMPETENSI</th>
                                                        <th style="font-size: 10px">KETERANGAN</th>
                                                        <th style="font-size: 10px">MINIMAL</th>
                                                        <th style="font-size: 10px">MAKSIMAL</th>
                                                        <th style="font-size: 10px">CAPAIAN</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($user->courses as $course)
                                                    <tr class="text-center">
                                                        <td style="font-size: 10px">Kompetensi {{ $loop->iteration }}</td>
                                                        <td style="font-size: 10px">{{ $course->course_name }}</td>
                                                        <td style="font-size: 10px">80%</td>
                                                        <td style="font-size: 10px">100%</td>
                                                        <td style="font-size: 10px">
                                                            {{ $course->presentase_kompetensi === '-' ? '-' : $course->presentase_kompetensi . '%' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).ready(function() {
            $('#simpan').on('click', function(event) {
                event.preventDefault(); // Mencegah form submit normal

                // Ambil ID dashboard untuk update
                const dashboardId = $('input[name="dashboard_id"]').val();

                // Tentukan URL untuk store atau update menggunakan Blade URL() helper
                const url = dashboardId ?
                    '{{ url('/admin/dashboard-matriks-kompetensi/update') }}/' + dashboardId :
                    '{{ url('/admin/dashboard-matriks-kompetensi/store') }}'; // Gunakan URL store atau update sesuai kondisi

                // Kumpulkan data form
                const formData = new FormData($('#kompetensiForm')[0]);

                // Jika dashboardId ada, tambahkan sebagai parameter untuk membedakan update
                if (dashboardId) {
                    formData.append('_method',
                        'PUT'
                    ); // Menambahkan field _method untuk memberi tahu server bahwa ini adalah update
                    formData.append('dashboard_id', dashboardId); // Tambahkan ID dashboard ke formData
                }

                // Tampilkan data untuk debugging (opsional)
                formData.forEach(function(value, key) {
                    console.log(key + ": " + value);
                });

                // Kirim request AJAX menggunakan POST
                $.ajax({
                    url: url,
                    type: 'POST', // Gunakan POST untuk keduanya
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                    },
                    success: function(response) {
                        swal("Data berhasil disimpan!", {
                            icon: "success",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            }
                        }).then(() => {
                            location.reload(); // Reload halaman setelah berhasil
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        swal("Mohon isi data dengan benar.", {
                            icon: "error",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-danger'
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('downloadPdf').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Menggunakan html2canvas untuk merender konten menjadi gambar
            html2canvas(document.getElementById('contentToPrint'), {
                scale: 2,
            }).then(function(canvas) {
                const imgData = canvas.toDataURL('image/png');

                const doc = new jsPDF();
                const pageWidth = doc.internal.pageSize.width;
                const pageHeight = doc.internal.pageSize.height;

                // Menyesuaikan ukuran gambar agar pas dengan ukuran halaman PDF
                const imgWidth = Math.min(pageWidth, canvas.width * 0.5);
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                doc.save('Dashboard Matriks Kompetensi.pdf');
            });
        });
    </script>

    <script>
        // Fungsi untuk menampilkan pratinjau gambar
        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById(previewId);
                preview.innerHTML =
                    `<img src="${reader.result}" class="img-fluid" style="height: 80px">`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    {{-- Chart Karyawan --}}
    @foreach ($users as $user)
        <script>
            var options = {
                chart: {
                    type: 'radar',
                    height: 200, // Menentukan tinggi chart
                    width: '100%', // Menentukan lebar chart agar responsif
                    margin: {
                        top: 10, // Margin atas
                        bottom: 10, // Menghilangkan margin bawah yang berlebihan
                        left: 0,
                        right: 0
                    }
                },
                series: [{
                    name: 'Capaian',
                    data: [
                        @foreach ($user->courses as $course)
                            {{ $course->presentase_kompetensi === '-' ? 0 : $course->presentase_kompetensi }},
                        @endforeach
                    ]
                }, {
                    name: 'Target',
                    data: new Array({{ $user->courses->count() }}).fill(
                        80) // Menetapkan target 80 untuk setiap kompetensi
                }],
                xaxis: {
                    categories: [
                        @foreach ($user->courses as $course)
                            'Kompetensi {{ $loop->iteration }}',
                        @endforeach
                    ]
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    tickAmount: 5,
                    labels: {
                        formatter: function(value) {
                            return value + '%';
                        }
                    }
                },
                plotOptions: {
                    radar: {
                        size: 68,
                        polygons: {
                            strokeColor: '#e0e0e0',
                            fill: {
                                colors: ['#e0e0e0', '#e0e0e0']
                            }
                        }
                    }
                },
                colors: ['#1d7af3', '#ff0000'],
                markers: {
                    size: 3,
                    colors: ['#1d7af3', '#ff0000'],
                    hover: {
                        size: 4
                    }
                },
                
            };

            var chart = new ApexCharts(document.querySelector("#radarChart{{ $user->user_id }}"), options);
            chart.render();
        </script>
    @endforeach



@endsection
