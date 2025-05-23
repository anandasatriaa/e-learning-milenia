@extends('admin.layouts.app')
@section('title', 'Preview Nilai Peserta')
@section('css')
    <style>
        .card-round {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 1rem;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 6px 12px !important;
        }

        .table thead th {
            padding: 6px 12px !important;
        }

        .table-responsive {
            border-radius: 1rem;
            overflow: hidden;
        }

        .table-main {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table-main thead tr th:first-child {
            border-top-left-radius: 0.75rem;
        }

        .table-main thead tr th:last-child {
            border-top-right-radius: 0.75rem;
        }

        .table-main tbody tr:last-child td:first-child {
            border-bottom-left-radius: 0.75rem;
        }

        .table-main tbody tr:last-child td:last-child {
            border-bottom-right-radius: 0.75rem;
        }

        .collapsible {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        .collapsible:not(.no-hover):hover {
            background-color: #f8faff;
            box-shadow: inset 0 0 0 2px rgba(52, 152, 219, 0.2),
                0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .collapsible-toggle::after {
            content: "▶";
            font-size: 0.8em;
            position: absolute;
            right: 15px;
            transition: transform 0.3s ease;
            transform: translateY(-50%);
        }

        .collapsible[aria-expanded="true"] .collapsible-toggle::after {
            transform: translateY(-50%) rotate(90deg);
        }

        .child-table {
            background-color: #f8f9fc;
            box-shadow: inset 0 4px 6px -6px rgba(0, 0, 0, 0.1);
        }

        .badge-score {
            padding: 0.5em 0.8em;
            border-radius: 1rem;
            font-weight: 500;
        }

        .quiz-badge {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .essay-badge {
            background-color: #f0f4c3;
            color: #827717;
        }

        .module-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .empty-state {
            color: #95a5a6;
            font-style: italic;
        }

        .nested-table {
            margin: 0.5rem 0;
            border-left: 3px solid #3498db;
        }

        /* Paksa container Select2 agar sesuai dengan width yang kita tentukan */
        .select2-container {
            width: 450px !important;
        }

        /* Pilihan multi-tag: gunakan flex & wrap, batasi tinggi, tambahkan scroll */
        .select2-container--default .select2-selection--multiple {
            min-height: 2.5em;
            max-height: 5.5em;
            /* misal 2 baris tag */
            overflow-y: auto;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap;
            gap: 0.25rem;
            /* jarak antar tag */
            padding: 0.25rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin: 0 !important;
            padding: 0 1.5em;
            height: auto;
            display: inline-flex;
            align-items: center;
        }

        .select2-course-option {
            display: flex;
            flex-direction: column;
        }

        .select2-course-option .course-title {
            font-weight: 600;
        }

        .select2-course-option .course-path {
            margin-top: 2px;
        }
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-12">
                <!-- Informational Banner -->
                <div class="alert alert-info rounded mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Nilai akhir peserta akan muncul ketika admin selesai melakukan input.
                </div>
                <div class="card card-round">
                    <div class="card-body">
                        <div class="card-title mb-4"><i class="fas fa-award"></i> Nilai Peserta</div>
                        <form action="{{ route('admin.preview.preview-nilai') }}" method="GET" id="filter-form">
                            <div class="d-flex flex-wrap mb-3 gap-2">
                                <!-- Filter Peserta -->
                                <div class="d-flex flex-column me-3">
                                    <label for="filter-peserta" class="form-label">Filter Peserta</label>
                                    <select name="peserta[]" id="filter-peserta" class="form-select" multiple>
                                        @foreach ($allUsers as $u)
                                            <option value="{{ $u->ID }}"
                                                {{ in_array($u->ID, request('peserta', [])) ? 'selected' : '' }}>
                                                {{ $u->Nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Divisi -->
                                <div class="d-flex flex-column me-3">
                                    <label for="filter-divisi" class="form-label">Filter Divisi</label>
                                    <select name="divisi[]" id="filter-divisi" class="form-select" multiple>
                                        @foreach ($allDivisions as $div)
                                            <option value="{{ $div }}"
                                                {{ in_array($div, request('divisi', [])) ? 'selected' : '' }}>
                                                {{ $div }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Course -->
                                <div class="d-flex flex-column me-3">
                                    <label for="filter-course" class="form-label">Filter Course</label>
                                    <select name="course[]" id="filter-course" class="form-select" multiple>
                                        @foreach ($allCourses as $c)
                                            <option value="{{ $c->id }}" data-path="{{ $c->path }}"
                                                {{ in_array($c->id, request('course', [])) ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" name="export" value="excel" class="btn btn-outline-success">
                                    <i class="fas fa-file-excel me-1"></i> Export Excel
                                </button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-main">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Peserta</th>
                                        <th>Divisi</th>
                                        <th>Email</th>
                                        <th>Total Course</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $i => $user)
                                        @php
                                            $formattedFoto = str_pad($user->ID, 5, '0', STR_PAD_LEFT);
                                            $cacheBuster = time(); // agar update terus

                                            $clientIp = request()->ip();

                                            // Pilih base URL berdasarkan IP
                                            if (
                                                $clientIp === '127.0.0.1' ||
                                                \Illuminate\Support\Str::startsWith($clientIp, '192.168.0.')
                                            ) {
                                                $baseUrl = 'http://192.168.0.8/hrd-milenia/foto/';
                                            } else {
                                                $baseUrl = 'http://pc.dyndns-office.com:8001/hrd-milenia/foto/';
                                            }

                                            $fotoUrl = $baseUrl . "{$formattedFoto}.JPG?v={$cacheBuster}";
                                        @endphp

                                        <tr class="collapsible" data-bs-toggle="collapse"
                                            data-bs-target="#courses{{ $i }}" aria-expanded="false"
                                            aria-controls="courses{{ $i }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <img src="{{ $fotoUrl }}" alt="Foto {{ $user->Nama }}"
                                                            class="rounded-circle" width="40" height="40">
                                                    </div>
                                                    <div>{{ $user->Nama }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $user->Divisi }}</td>
                                            <td>{{ $user->email_karyawan }}</td>
                                            <td>{{ $user->total_course }} Course</td>
                                            <td class="text-right collapsible-toggle"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="p-0">
                                                <div id="courses{{ $i }}" class="collapse child-table"
                                                    aria-labelledby="courses{{ $i }}">
                                                    <div class="p-4">
                                                        <div class="nested-table">
                                                            <table class="table mb-0">
                                                                <thead>
                                                                    <tr class="bg-light">
                                                                        <th>Course</th>
                                                                        <th>Tgl Enroll</th>
                                                                        <th>Selesai</th>
                                                                        <th>Progress</th>
                                                                        <th>Time Spend</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($user->courses as $j => $enr)
                                                                        <tr class="collapsible" data-bs-toggle="collapse"
                                                                            data-bs-target="#modules{{ $i }}-{{ $j }}">
                                                                            <td class="module-title">
                                                                                <div class="d-flex align-items-center">
                                                                                    @php
                                                                                        $cacheBuster = time();
                                                                                        $thumbnail = $enr->course
                                                                                            ->thumbnail
                                                                                            ? asset(
                                                                                                    'storage/course/thumbnail/' .
                                                                                                        $enr->course
                                                                                                            ->thumbnail,
                                                                                                ) .
                                                                                                '?cb=' .
                                                                                                $cacheBuster
                                                                                            : 'https://via.placeholder.com/40x40?text=📘';
                                                                                    @endphp
                                                                                    <img src="{{ $thumbnail }}"
                                                                                        alt="Thumbnail" class="rounded me-2"
                                                                                        width="40" height="40"
                                                                                        style="object-fit: cover;">
                                                                                    <span>{{ $enr->course->nama_kelas }}</span>
                                                                                </div>
                                                                            </td>

                                                                            {{-- Enroll Date --}}
                                                                            <td>{{ \Carbon\Carbon::parse($enr->enroll_date)->format('d M Y') }}
                                                                            </td>

                                                                            {{-- Finish Date --}}
                                                                            <td>
                                                                                @if ($enr->finish_date)
                                                                                    <i
                                                                                        class="fas fa-check text-success me-1"></i>
                                                                                    {{ \Carbon\Carbon::parse($enr->finish_date)->format('d M Y') }}
                                                                                @endif
                                                                            </td>

                                                                            {{-- Progress Bar --}}
                                                                            <td style="width:25%">
                                                                                <div class="progress" style="height: 6px;">
                                                                                    <div class="progress-bar {{ $enr->progress_bar == 100 ? 'bg-success' : 'bg-warning' }}"
                                                                                        role="progressbar"
                                                                                        style="width:{{ $enr->progress_bar }}%"
                                                                                        aria-valuenow="{{ $enr->progress_bar }}"
                                                                                        aria-valuemin="0"
                                                                                        aria-valuemax="100">
                                                                                    </div>
                                                                                </div>
                                                                                <small
                                                                                    class="text-muted">{{ $enr->progress_bar }}%</small>
                                                                            </td>

                                                                            {{-- Time Spent --}}
                                                                            <td><i class="fas fa-clock me-1"></i>
                                                                                {{ gmdate('H\h i\m', $enr->time_spend) }}
                                                                            </td>

                                                                            <td class="text-right collapsible-toggle"></td>
                                                                        </tr>

                                                                        {{-- Detail Modul --}}
                                                                        <tr>
                                                                            <td colspan="6" class="p-0">
                                                                                <div id="modules{{ $i }}-{{ $j }}"
                                                                                    class="collapse">
                                                                                    <div class="p-3">
                                                                                        <div class="nested-table pl-3">
                                                                                            <table class="table mb-0">
                                                                                                <thead class="bg-light">
                                                                                                    <tr>
                                                                                                        <th>Nama Modul</th>
                                                                                                        <th>Benar / Soal</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    @foreach ($enr->modules as $mod)
                                                                                                        @php
                                                                                                            $totalSoal =
                                                                                                                $mod->total_soal ??
                                                                                                                0;
                                                                                                            $benar =
                                                                                                                $mod->quiz_score ??
                                                                                                                0;
                                                                                                            $class =
                                                                                                                'text-muted';
                                                                                                            if (
                                                                                                                $benar ===
                                                                                                                    $totalSoal &&
                                                                                                                $totalSoal >
                                                                                                                    0
                                                                                                            ) {
                                                                                                                $class =
                                                                                                                    'text-success';
                                                                                                            } elseif (
                                                                                                                $benar >
                                                                                                                0
                                                                                                            ) {
                                                                                                                $class =
                                                                                                                    'text-warning';
                                                                                                            } elseif (
                                                                                                                $totalSoal >
                                                                                                                0
                                                                                                            ) {
                                                                                                                $class =
                                                                                                                    'text-danger';
                                                                                                            }
                                                                                                        @endphp
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                <i
                                                                                                                    class="fas fa-book-open me-1 text-secondary"></i>
                                                                                                                {{ $mod->nama_modul }}
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                <span
                                                                                                                    class="d-inline-flex align-items-center px-2 py-1 rounded @php
$bgClass = 'bg-secondary text-white';
                                                                                                                    if ($benar === $totalSoal && $totalSoal > 0) {
                                                                                                                        $bgClass = 'bg-success text-white';
                                                                                                                    } elseif ($benar > 0) {
                                                                                                                        $bgClass = 'bg-warning text-dark';
                                                                                                                    } elseif ($totalSoal > 0) {
                                                                                                                        $bgClass = 'bg-danger text-white';
                                                                                                                    } @endphp {{ $bgClass }}"
                                                                                                                    style="font-size: 0.875rem;">
                                                                                                                    <i
                                                                                                                        class="fas fa-check-circle me-1"></i>
                                                                                                                    <strong>Quiz:</strong>&nbsp;{{ $benar }}/{{ $totalSoal }}
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endforeach

                                                                                                    <tr
                                                                                                        class="bg-light fw-bold">
                                                                                                        <td>
                                                                                                            <i
                                                                                                                class="fas fa-calculator me-1 text-secondary"></i>
                                                                                                            Nilai Akhir
                                                                                                        </td>
                                                                                                        <td colspan="2">
                                                                                                            @php
                                                                                                                // Gunakan optional untuk mencegah property on null
                                                                                                                $catName = optional(
                                                                                                                    $enr
                                                                                                                        ->course
                                                                                                                        ->category,
                                                                                                                )->nama;
                                                                                                            @endphp

                                                                                                            @if ($catName === 'Matriks Kompetensi')
                                                                                                                <small
                                                                                                                    class="text-primary fw-bold d-block">
                                                                                                                    Quiz:
                                                                                                                    {{ $enr->nilai->nilai_quiz }}
                                                                                                                    |
                                                                                                                    Essay:
                                                                                                                    {{ $enr->nilai->nilai_essay }}
                                                                                                                    |
                                                                                                                    Praktek:
                                                                                                                    {{ $enr->nilai->nilai_praktek }}
                                                                                                                    |
                                                                                                                    Kompetensi:
                                                                                                                    {{ $enr->nilai->presentase_kompetensi }}%
                                                                                                                </small>
                                                                                                            @else
                                                                                                                <small
                                                                                                                    class="text-primary fw-bold d-block">
                                                                                                                    Quiz:
                                                                                                                    {{ $enr->nilai->nilai_quiz }}
                                                                                                                    |
                                                                                                                    Essay:
                                                                                                                    {{ $enr->nilai->nilai_essay }}
                                                                                                                </small>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
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
    </div>
@endsection
@section('js')

    <script>
        $(document).ready(function() {
            function formatCourse(option) {
                if (!option.id) {
                    // placeholder
                    return option.text;
                }
                // ambil nama dan path
                const name = option.text;
                const path = $(option.element).data('path')
                    .replace(name + '', '') // hapus nama_kelas dari path
                    .replace(/^ >\s*/, ''); // bersihkan leading " > "
                // bangun elemen dua baris
                const $container = $(`
          <div class="select2-course-option">
            <div class="course-title">${name}</div>
            <div class="course-path text-muted" style="font-size:0.85em;">${path}</div>
          </div>
        `);
                return $container;
            }

            $('#filter-peserta, #filter-divisi').select2({
                placeholder: 'Pilih satu atau lebih',
                width: 'resolve',
                dropdownAutoWidth: true
            });

            $('#filter-course').select2({
                placeholder: 'Pilih satu atau lebih',
                width: 'resolve',
                dropdownAutoWidth: true,
                templateResult: formatCourse,
                templateSelection: formatCourse, // juga di dalam kotak setelah dipilih
                escapeMarkup: m => m // sudah berupa HTML
            });

            $('#filter-peserta, #filter-divisi, #filter-course').on('change', function() {
                $('#filter-form').submit();
            });
        });
    </script>
@endsection
