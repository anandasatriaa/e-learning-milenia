@extends('pages.layouts.app')
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
                <!-- Banner Info -->
                <div class="alert alert-info rounded mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Nilai akhir peserta akan muncul ketika admin selesai melakukan input.
                </div>

                <div class="card card-round">
                    <div class="card-body">
                        <div class="card-title mb-4"><i class="fas fa-award"></i> Preview Nilai</div>
                        <div class="table-responsive">
                            <table class="table table-main">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Tgl Enroll</th>
                                        <th>Selesai</th>
                                        <th>Progress</th>
                                        <th>Time Spend</th>
                                        <th>Nilai Akhir</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrolls as $i => $enr)
                                        <tr class="collapsible" data-bs-toggle="collapse"
                                            data-bs-target="#course{{ $i }}">
                                            <td class="module-title">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $cacheBuster = time();
                                                        $thumbnail = $enr->course->thumbnail
                                                            ? asset(
                                                                    'storage/course/thumbnail/' .
                                                                        $enr->course->thumbnail,
                                                                ) .
                                                                '?cb=' .
                                                                $cacheBuster
                                                            : 'https://via.placeholder.com/40x40?text=📘';
                                                    @endphp
                                                    <img src="{{ $thumbnail }}" alt="Thumbnail" class="rounded me-2"
                                                        width="40" height="40" style="object-fit: cover;">
                                                    <span>{{ $enr->course->nama_kelas }}</span>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($enr->enroll_date)->format('d M Y') }}</td>
                                            <td>
                                                @if ($enr->finish_date)
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    {{ \Carbon\Carbon::parse($enr->finish_date)->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td style="width:25%">
                                                <div class="progress" style="height:6px;">
                                                    <div class="progress-bar {{ $enr->progress_bar == 100 ? 'bg-success' : 'bg-warning' }}"
                                                        role="progressbar" style="width:{{ $enr->progress_bar }}%"
                                                        aria-valuenow="{{ $enr->progress_bar }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $enr->progress_bar }}%</small>
                                            </td>
                                            <td>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ gmdate('H\h i\m', $enr->time_spend) }}
                                            </td>
                                            @php $catName = optional($enr->course->category)->nama; @endphp
                                            @php $catName = optional($enr->course->category)->nama; @endphp
                                            <td>
                                                <div class="gap-2">
                                                    <!-- Quiz -->
                                                    <span class="badge bg-primary d-flex align-items-center">
                                                        <i class="fas fa-question-circle me-1"></i>
                                                        Quiz: {{ $enr->nilai->nilai_quiz }}
                                                    </span>

                                                    <!-- Essay -->
                                                    <span class="badge bg-secondary d-flex align-items-center">
                                                        <i class="fas fa-file-alt me-1"></i>
                                                        Essay: {{ $enr->nilai->nilai_essay }}
                                                    </span>

                                                    @if ($catName === 'Matriks Kompetensi')
                                                        <!-- Praktek -->
                                                        <span class="badge bg-success d-flex align-items-center">
                                                            <i class="fas fa-flask me-1"></i>
                                                            Praktek: {{ $enr->nilai->nilai_praktek }}
                                                        </span>

                                                        <!-- Kompetensi -->
                                                        <span class="badge bg-info text-dark d-flex align-items-center">
                                                            <i class="fas fa-percentage me-1"></i>
                                                            Kompetensi: {{ $enr->nilai->presentase_kompetensi }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-right collapsible-toggle">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="7" class="p-0">
                                                <div id="course{{ $i }}" class="collapse child-table">
                                                    <div class="p-3">
                                                        <table class="table mb-0">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th>Nama Modul</th>
                                                                    <th>Benar / Soal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($enr->modules as $mod)
                                                                  <tr>
                                                                    <td>
                                                                      <i class="fas fa-book-open me-1 text-secondary"></i>
                                                                      {{ $mod->nama_modul }}
                                                                    </td>
                                                                    <td>
                                                                      <span class="badge {{
                                                                          $mod->quiz_score === $mod->total_soal && $mod->total_soal > 0
                                                                            ? 'bg-success text-white'
                                                                            : ($mod->quiz_score > 0
                                                                                ? 'bg-warning text-dark'
                                                                                : ($mod->total_soal > 0
                                                                                    ? 'bg-danger text-white'
                                                                                    : 'bg-secondary text-white'))
                                                                        }}">
                                                                        <i class="fas fa-check-circle me-1"></i> <strong>Quiz:</strong>&nbsp;{{ $mod->quiz_score }}/{{ $mod->total_soal }}
                                                                      </span>
                                                                    </td>
                                                                  </tr>
                                                                @endforeach
                                                              </tbody>
                                                        </table>
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
@endsection
