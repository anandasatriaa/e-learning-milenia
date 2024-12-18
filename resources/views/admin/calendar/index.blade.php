@extends('admin.layouts.app')
@section('title', 'Calendar')

@section('css')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.css' rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.css' rel="stylesheet">
    {{-- Selectize --}}
    <link href="{{ asset('vendor/selectize/selectize.bootstrap5.css') }}" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container .select2-results__option .avatar {
            display: flex;
            align-items: center;
        }
    
        .select2-container .select2-results__option img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 50%;
        }
    
        .select2-container .select2-selection__rendered img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 50%;
        }

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
        <div class="card-header">
            <div class="card-body row">
                <div class="col-6 col-md-6" id="calendar"></div>

                <!-- Form Input Acara -->
                <div class="mt-4 col-6 col-md-6">
                    <h5>Input Acara</h5>
                    <form id="eventForm">
                        <!-- Input Nama Acara -->
                        <div class="mb-3">
                            <label for="eventName" class="form-label">Nama Acara</label>
                            <input type="text" id="eventName" name="eventName" class="form-control" placeholder="Masukkan nama acara" required>
                        </div>

                        <!-- Dropdown Nama -->
<div class="mb-3">
    <label for="personName" class="form-label">Nama</label>
    <small class="text-danger d-block" style="font-size: 10px">*Nama tidak wajib diinput</small>
    <select id="personName" name="personName" class="form-select form-dropdown">
        <option value="">Pilih Nama</option>
        @foreach($users as $user)
            @php
                $formattedFoto = str_pad($user->id, 5, '0', STR_PAD_LEFT);
                $fotoUrl = "http://192.168.0.8/hrd-milenia/foto/{$formattedFoto}.JPG";
            @endphp
            <option value="{{ $user->Nama }}" data-foto="{{ $fotoUrl }}">{{ $user->Nama }}</option>
        @endforeach
    </select>
</div>


                        <!-- Dropdown Divisi -->
                        <div class="mb-3">
                            <label for="division" class="form-label">Divisi</label>
                            <select id="division" name="division" class="form-select form-dropdown" required>
                                <option value="">Pilih Divisi</option>
                                @foreach($users->pluck('Divisi')->unique() as $division)
                                    <option value="{{ $division }}">{{ $division }}</option>
                                @endforeach
                            </select>
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
                            <input type="color" id="backgroundColor" name="backgroundColor" class="form-control form-control-color" value="#ff0000" required>
                        </div>

                        <!-- Button Submit -->
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Selectize (jika digunakan)
    var selectCategory = $(".form-dropdown").selectize({
        respect_word_boundaries: false,
        closeAfterSelect: true,
        plugins: ["clear_button"],
    });

    // Inisialisasi FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        events: []
    });

    calendar.render();

    // Tangani perubahan warna background
    const backgroundColorInput = document.getElementById('backgroundColor');
    backgroundColorInput.addEventListener('input', function () {
        this.style.backgroundColor = this.value; // Mengatur warna background elemen input
    });

    // Tangani Form Submit
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var eventName = document.getElementById('eventName').value;
        var personName = document.getElementById('personName').value;
        var division = document.getElementById('division').value;
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;
        var backgroundColor = document.getElementById('backgroundColor').value;

        if (eventName || personName || division || startDate || endDate) {
            calendar.addEvent({
                title: `${eventName} - ${personName} (${division})`,
                start: startDate,
                end: endDate,
                backgroundColor: backgroundColor
            });

            alert('Acara berhasil ditambahkan!');

            // Reset form input
            e.target.reset();

            // Reset warna input ke default setelah form di-reset
            backgroundColorInput.style.backgroundColor = '#ffffff';
        }
    });
});

    </script>

<script>
    $(document).ready(function () {
        // Inisialisasi Select2
        // $('#personName').select2({
        //     theme: "bootstrap-5",
        //     placeholder: "Pilih Nama",
        //     allowClear: true,
        //     templateResult: formatOption, // Menentukan template untuk opsi
        //     templateSelection: formatOptionSelection // Menentukan template untuk opsi terpilih
        // });

        // Fungsi untuk menampilkan foto + nama di opsi dropdown
        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }

            var fotoUrl = $(option.element).data('foto');
            var $option = $(
                `<div class="avatar">
                    <img src="${fotoUrl}" alt="${option.text}" />
                    <span>${option.text}</span>
                </div>`
            );

            return $option;
        }

        // Fungsi untuk menampilkan foto + nama pada opsi terpilih
        function formatOptionSelection(option) {
            if (!option.id) {
                return option.text;
            }

            var fotoUrl = $(option.element).data('foto');
            var $selected = $(
                `<div class="avatar">
                    <img src="${fotoUrl}" alt="${option.text}" />
                    <span>${option.text}</span>
                </div>`
            );

            return $selected;
        }
    });
</script>

@endsection
