@extends('admin.layouts.app')
@section('title', 'Calendar')

@section('css')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.css' rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.css' rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-inner">
    <div class="card">
        <div class="card-header">
            <div class="card-body" style="width: 600px">
                <div id="calendar"></div>

                <!-- Form Input Acara -->
                <div class="mt-4">
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
                            <select id="personName" name="personName" class="form-select" required>
                                <option value="">Pilih Nama</option>
                                <option value="John Doe">John Doe</option>
                                <option value="Jane Smith">Jane Smith</option>
                                <option value="Michael Johnson">Michael Johnson</option>
                            </select>
                        </div>

                        <!-- Dropdown Divisi -->
                        <div class="mb-3">
                            <label for="division" class="form-label">Divisi</label>
                            <select id="division" name="division" class="form-select" required>
                                <option value="">Pilih Divisi</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="IT">IT</option>
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
    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                events: []
            });

            calendar.render();

            // Tangani Form Submit
            document.getElementById('eventForm').addEventListener('submit', function(e) {
                e.preventDefault();

                var eventName = document.getElementById('eventName').value;
                var personName = document.getElementById('personName').value;
                var division = document.getElementById('division').value;
                var startDate = document.getElementById('startDate').value;
                var endDate = document.getElementById('endDate').value;
                var backgroundColor = document.getElementById('backgroundColor').value;

                if (eventName && personName && division && startDate && endDate) {
                    calendar.addEvent({
                        title: `${eventName} - ${personName} (${division})`,
                        start: startDate,
                        end: endDate,
                        backgroundColor: backgroundColor
                    });

                    alert('Acara berhasil ditambahkan!');

                    // Reset form input
                    e.target.reset();
                }
            });
        });
    </script>
@endsection
