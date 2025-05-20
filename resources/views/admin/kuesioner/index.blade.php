@extends('admin.layouts.app')
@section('title', 'Kuesioner')
@section('css')
    <style>
        .scale-radio {
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #dee2e6;
            position: relative;
            cursor: pointer;
        }

        .question-item {
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .question-item:last-child {
            border-bottom: none;
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
    </style>
@endsection
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-12">
                <!-- Tabel daftar kuesioner -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>Daftar Kuesioner</h2>
                            <!-- Tombol Add Kuesioner -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionnaireModal">
                                <i class="fas fa-plus"></i> Add Kuesioner
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="questionnaire-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Dibuat Pada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questionnaires as $idx => $q)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $q->title }}</td>
                                        <td>{{ $q->created_at->format('d M Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-open-edit" data-id="{{ $q->id }}"
                                                data-bs-toggle="modal" data-bs-target="#editQuestionnaireModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.kuesioner.feedback-kuesioner.destroy', $q) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada kuesioner.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Kuesioner -->
    <div class="modal fade" id="addQuestionnaireModal" tabindex="-1" role="dialog"
        aria-labelledby="addQuestionnaireModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.kuesioner.feedback-kuesioner.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQuestionnaireModalLabel">Buat Kuesioner Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Mulai form yang sudah dibuat sebelumnya --}}
                        <div class="form-group">
                            <label for="title">Judul Kuesioner</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="courses">Pilih Course</label>
                            <select name="course_ids[]" id="courses" class="form-control" multiple required>
                                @foreach ($courses as $course)
                                    {{-- Misal $course->path sudah berupa string "Kategori > Subkategori > ..." --}}
                                    <option value="{{ $course->id }}" data-path="{{ $course->path }}">
                                        {{ $course->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="questions-container">
                            <div class="question-item mb-2" data-index="0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5>Pertanyaan <span class="q-number">1</span></h5>
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-question"
                                        style="display:none">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label>Pertanyaan</label>
                                    <input type="text" class="form-control question-text" name="questions[0][text]"
                                        required>
                                </div>

                                <div class="row align-items-end mb-3">
                                    <div class="col-md-2">
                                        <label>Skala Min</label>
                                        <select class="form-control scale-min" name="questions[0][scale_min]">
                                            @for ($i = 0; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $i === 1 ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Skala Max</label>
                                        <select class="form-control scale-max" name="questions[0][scale_max]">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $i === 5 ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Label Min</label>
                                        <input type="text" class="form-control label-min" name="questions[0][label_min]"
                                            value="Sangat Tidak Sesuai">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Label Max</label>
                                        <input type="text" class="form-control label-max" name="questions[0][label_max]"
                                            value="Sangat Sesuai">
                                    </div>
                                </div>

                                <div class="border p-3 bg-light preview-scale">
                                    <h6 class="preview-question-text"></h6>
                                    <div class="d-flex justify-content-between mb-2 scale-labels"></div>
                                    <div class="d-flex justify-content-between mb-2 scale-radios"></div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <div class="text-start preview-label-min" style="width:150px"></div>
                                        <div class="text-end preview-label-max" style="width:150px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-add-question ms-4">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan
                            Kuesioner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editQuestionnaireModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="form-edit" action="" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kuesioner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- -- Judul --}}
                        <div class="mb-3">
                            <label>Judul Kuesioner</label>
                            <input type="text" class="form-control" id="edit-title" name="title" required>
                        </div>
                        {{-- -- Courses --}}
                        <div class="mb-4">
                            <label>Pilih Course</label>
                            <select name="course_ids[]" id="edit-courses" class="form-control" multiple required>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}" data-path="{{ $c->path }}">
                                        {{ $c->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- -- Questions --}}
                        <div id="edit-questions-container"></div>
                        <button type="button" class="btn btn-secondary btn-add-question-edit ms-4">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Kuesioner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')

    {{-- DATATABLE --}}
    <script>
        $(document).ready(function() {
            $('#questionnaire-table').DataTable({
                // opsi opsional:
                paging: true, // pagination on
                searching: true, // kolom search
                ordering: true, // sortable column
                info: true, // "Menampilkan 1–10 dari 50 entri"
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                columnDefs: [{
                        orderable: false,
                        targets: 3
                    } // kolom Aksi tidak sortable
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                }
            });
        });
    </script>

    {{-- MENAMBAH DAN MENGURANGI KUESIONER --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('questions-container');
            const template = container.querySelector('.question-item');

            function renderItem(item) {
                const min = +item.querySelector('.scale-min').value;
                const max = +item.querySelector('.scale-max').value;
                const lblMin = item.querySelector('.label-min').value;
                const lblMax = item.querySelector('.label-max').value;
                const txt = item.querySelector('.question-text').value;

                item.querySelector('.preview-question-text').textContent = txt;

                const labelsWrap = item.querySelector('.scale-labels');
                const radiosWrap = item.querySelector('.scale-radios');
                const previewLblMin = item.querySelector('.preview-label-min');
                const previewLblMax = item.querySelector('.preview-label-max');

                if (min >= max) {
                    labelsWrap.innerHTML = '<div class="text-danger">Skala Max harus > Min</div>';
                    radiosWrap.innerHTML = '';
                    previewLblMin.textContent = previewLblMax.textContent = '';
                    return;
                }

                const range = Array.from({
                    length: max - min + 1
                }, (_, i) => min + i);
                labelsWrap.innerHTML = range.map(i =>
                    `<div class="text-center" style="width:40px">${i}</div>`
                ).join('');
                radiosWrap.innerHTML = range.map(() => `
                <div class="form-check form-check-inline px-1">
                    <input class="form-check-input scale-radio" type="radio" disabled>
                </div>
                `).join('');

                previewLblMin.textContent = lblMin;
                previewLblMax.textContent = lblMax;
            }

            function initItem(item) {
                ['.scale-min', '.scale-max', '.label-min', '.label-max', '.question-text']
                .forEach(sel => {
                    item.querySelector(sel).addEventListener('input', () => renderItem(item));
                });
                item.querySelector('.btn-remove-question').addEventListener('click', () => {
                    item.remove();
                    updateNumbers();
                });
                renderItem(item);
            }

            function updateNumbers() {
                container.querySelectorAll('.question-item').forEach((itm, idx) => {
                    itm.setAttribute('data-index', idx);
                    itm.querySelector('.q-number').textContent = idx + 1;
                    itm.querySelector('.question-text').name = `questions[${idx}][text]`;
                    itm.querySelector('.scale-min').name = `questions[${idx}][scale_min]`;
                    itm.querySelector('.scale-max').name = `questions[${idx}][scale_max]`;
                    itm.querySelector('.label-min').name = `questions[${idx}][label_min]`;
                    itm.querySelector('.label-max').name = `questions[${idx}][label_max]`;
                    const showDel = container.children.length > 1;
                    itm.querySelector('.btn-remove-question').style.display = showDel ? 'inline-block' :
                        'none';
                });
            }

            document.querySelector('.btn-add-question').addEventListener('click', () => {
                const clone = template.cloneNode(true);
                container.appendChild(clone);
                initItem(clone);
                updateNumbers();
            });

            // Inisialisasi
            initItem(template);
            updateNumbers();
        });
    </script>

    {{-- INISIALISASI SELECT2 --}}
    <script>
        $(document).ready(function() {
            // Fungsi format option (nama + path)
            function formatCourse(opt) {
                if (!opt.id) return opt.text;
                const name = opt.text;
                let path = $(opt.element).data('path') || '';
                // buang nama di depan path jika perlu
                if (path.startsWith(name)) {
                    path = path.slice(name.length).replace(/^ *>\s*/, '');
                }
                return `
                <div>
                    <div>${name}</div>
                    ${path ? `<div class="text-muted" style="font-size:.85em;">${path}</div>` : ''}
                </div>
                `;
            }

            // Saat modal terbuka, inisialisasi Select2
            $('#addQuestionnaireModal').on('shown.bs.modal', function() {
                $('#courses')
                    // destroy dulu kalau sempat ter-inisialisasi
                    .select2({
                        dropdownParent: $('#addQuestionnaireModal')
                    })
                    .off('select2:opening select2:closing') // bersihkan duplicate binding
                    .select2('destroy')
                    // init ulang
                    .select2({
                        width: '100%',
                        dropdownAutoWidth: true,
                        dropdownParent: $('#addQuestionnaireModal'),
                        templateResult: formatCourse,
                        templateSelection: formatCourse,
                        escapeMarkup: m => m
                    });
            });
        });
    </script>

    {{-- POST KE DATABASE --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('#addQuestionnaireModal form');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const title = form.querySelector('#title').value;

                const courseSelect = form.querySelector('#courses');
                let courseIds;
                if (window.jQuery && $(courseSelect).data('select2')) {
                    courseIds = $(courseSelect).val();
                } else {
                    courseIds = Array.from(courseSelect.selectedOptions).map(opt => opt.value);
                }

                const questions = Array.from(form.querySelectorAll('.question-item')).map(item => {
                    const idx = item.getAttribute('data-index');
                    return {
                        text: item.querySelector(`input[name="questions[${idx}][text]"]`).value,
                        scale_min: parseInt(item.querySelector(
                            `select[name="questions[${idx}][scale_min]"]`).value),
                        scale_max: parseInt(item.querySelector(
                            `select[name="questions[${idx}][scale_max]"]`).value),
                        label_min: item.querySelector(`input[name="questions[${idx}][label_min]"]`)
                            .value,
                        label_max: item.querySelector(`input[name="questions[${idx}][label_max]"]`)
                            .value,
                    };
                });

                const payload = {
                    title: title,
                    course_ids: courseIds,
                    questions: questions
                };

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload),
                    })
                    .then(async res => {
                        const contentType = res.headers.get("content-type");
                        const isJson = contentType && contentType.includes("application/json");

                        const json = isJson ? await res.json() : {};

                        if (res.ok) {
                            swal({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Kuesioner berhasil disimpan!',
                                button: 'OK'
                            });

                            setTimeout(() => {
                                $('#addQuestionnaireModal').modal('hide');
                                location.reload();
                            }, 1000);
                        } else {
                            let errorMsg = 'Terjadi kesalahan.';

                            if (json.errors) {
                                errorMsg = Object.values(json.errors)
                                    .flat()
                                    .join('\n');
                            } else if (json.message) {
                                errorMsg = json.message;
                            }

                            swal({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: errorMsg,
                                button: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        swal({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Koneksi gagal atau kesalahan internal.',
                            button: 'OK'
                        });
                    });
            });
        });
    </script>

    {{-- MODAL EDIT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editForm = document.getElementById('form-edit');
            const editTitleInput = document.getElementById('edit-title');
            const editCourses = $('#edit-courses'); // jQuery + Select2
            const editContainer = document.getElementById('edit-questions-container');
            const addTemplate = document.querySelector('#questions-container .question-item');

            // Template URL dari named route, dengan placeholder :id
            const updateUrlTpl =
                "{{ route('admin.kuesioner.feedback-kuesioner.update', ['questionnaire' => ':id']) }}";
            const editUrlTpl =
                "{{ route('admin.kuesioner.feedback-kuesioner.edit', ['questionnaire' => ':id']) }}";

            // Fungsi render & init satu item
            function renderItem(item) {
                const min = +item.querySelector('.scale-min').value;
                const max = +item.querySelector('.scale-max').value;
                const lblMin = item.querySelector('.label-min').value;
                const lblMax = item.querySelector('.label-max').value;
                const txt = item.querySelector('.question-text').value;
                item.querySelector('.preview-question-text').textContent = txt;

                const labels = item.querySelector('.scale-labels');
                const radios = item.querySelector('.scale-radios');

                if (min >= max) {
                    labels.innerHTML = '<div class="text-danger">Max harus > Min</div>';
                    radios.innerHTML = '';
                } else {
                    const arr = Array.from({
                        length: max - min + 1
                    }, (_, i) => min + i);
                    labels.innerHTML = arr.map(i => `<div class="text-center" style="width:40px">${i}</div>`).join(
                        '');
                    radios.innerHTML = arr.map(() => `
                        <div class="form-check form-check-inline px-1">
                            <input type="radio" disabled class="form-check-input scale-radio">
                        </div>
                    `).join('');
                }

                item.querySelector('.preview-label-min').textContent = lblMin;
                item.querySelector('.preview-label-max').textContent = lblMax;
            }

            function initItem(item) {
                ['.question-text', '.scale-min', '.scale-max', '.label-min', '.label-max']
                .forEach(sel => item.querySelector(sel)
                    .addEventListener('input', () => renderItem(item)));

                item.querySelector('.btn-remove-question')
                    .addEventListener('click', () => {
                        item.remove();
                        updateEditNumbers();
                    });

                renderItem(item);
            }

            function updateEditNumbers() {
                Array.from(editContainer.querySelectorAll('.question-item')).forEach((itm, idx) => {
                    itm.setAttribute('data-index', idx);
                    itm.querySelector('.q-number').textContent = idx + 1;
                    itm.querySelector('.question-text').name = `questions[${idx}][text]`;
                    itm.querySelector('.scale-min').name = `questions[${idx}][scale_min]`;
                    itm.querySelector('.scale-max').name = `questions[${idx}][scale_max]`;
                    itm.querySelector('.label-min').name = `questions[${idx}][label_min]`;
                    itm.querySelector('.label-max').name = `questions[${idx}][label_max]`;
                });
            }

            // Drag & drop untuk edit
            Sortable.create(editContainer, {
                handle: '.q-number',
                animation: 150,
                onEnd: () => updateEditNumbers()
            });

            // Saat tombol Edit diklik
            document.querySelectorAll('.btn-open-edit').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');

                    // Set form action ke named route update
                    editForm.action = updateUrlTpl.replace(':id', id);

                    // Reset modal
                    editTitleInput.value = '';
                    editCourses.val(null).trigger('change');
                    editContainer.innerHTML = '';

                    // Fetch data via named route edit
                    fetch(editUrlTpl.replace(':id', id))
                        .then(res => res.json())
                        .then(data => {
                            // Judul
                            editTitleInput.value = data.title;
                            // Courses (Select2)
                            const courseIds = data.courses.map(c => c.id);
                            editCourses.val(courseIds).trigger('change');

                            // Pertanyaan
                            data.questions.forEach((q, idx) => {
                                const item = addTemplate.cloneNode(true);
                                item.setAttribute('data-index', idx);
                                item.querySelector('.btn-remove-question').style
                                    .display = 'inline-block';

                                // Update name & value
                                item.querySelector('.question-text').name =
                                    `questions[${idx}][text]`;
                                item.querySelector('.scale-min').name =
                                    `questions[${idx}][scale_min]`;
                                item.querySelector('.scale-max').name =
                                    `questions[${idx}][scale_max]`;
                                item.querySelector('.label-min').name =
                                    `questions[${idx}][label_min]`;
                                item.querySelector('.label-max').name =
                                    `questions[${idx}][label_max]`;

                                item.querySelector('.question-text').value = q.text;
                                item.querySelector('.scale-min').value = q.scale_min;
                                item.querySelector('.scale-max').value = q.scale_max;
                                item.querySelector('.label-min').value = q.label_min;
                                item.querySelector('.label-max').value = q.label_max;

                                editContainer.appendChild(item);
                                initItem(item);
                            });

                            updateEditNumbers();
                        });
                });
            });

            // Tambah pertanyaan di Edit
            document.querySelector('.btn-add-question-edit').addEventListener('click', () => {
                const idx = editContainer.children.length;
                const item = addTemplate.cloneNode(true);
                item.setAttribute('data-index', idx);
                item.querySelector('.btn-remove-question').style.display = 'inline-block';

                item.querySelector('.question-text').name = `questions[${idx}][text]`;
                item.querySelector('.scale-min').name = `questions[${idx}][scale_min]`;
                item.querySelector('.scale-max').name = `questions[${idx}][scale_max]`;
                item.querySelector('.label-min').name = `questions[${idx}][label_min]`;
                item.querySelector('.label-max').name = `questions[${idx}][label_max]`;

                editContainer.appendChild(item);
                initItem(item);
                updateEditNumbers();
            });

            // --- AJAX submit dengan SweetAlert (v1) ---
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const url = this.action;
                const formData = new FormData(this);

                // Tampilkan loading (tanpa tombol)
                swal({
                    title: 'Menyimpan perubahan...',
                    text: 'Mohon tunggu',
                    buttons: false,
                    closeOnClickOutside: false
                });

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async res => {
                        swal.close(); // tutup loading

                        if (res.ok) {
                            const data = await res.json();
                            if (data.success) {
                                // Berhasil
                                swal({
                                    title: 'Berhasil!',
                                    text: 'Kuesioner berhasil diperbarui.',
                                    icon: 'success',
                                    timer: 2000,
                                    buttons: false
                                });
                                $('#editQuestionnaireModal').modal('hide');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                // success:false
                                swal({
                                    title: 'Gagal',
                                    text: data.message ||
                                        'Terjadi kesalahan saat menyimpan.',
                                    icon: 'error'
                                });
                            }
                        } else if (res.status === 422) {
                            // Validation error
                            const err = await res.json();
                            const messages = Object.values(err.errors)
                                .flat()
                                .join('\n');
                            swal({
                                title: 'Validasi Gagal',
                                text: messages,
                                icon: 'warning'
                            });
                        } else {
                            // Error server lain
                            const text = await res.text();
                            swal({
                                title: `Error ${res.status}`,
                                text: text || 'Terjadi kesalahan pada server.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        swal.close();
                        swal({
                            title: 'Network Error',
                            text: err.message,
                            icon: 'error'
                        });
                    });
            });
        });
    </script>

    {{-- INISIALISASI SELECT2 EDIT --}}
    <script>
        $(document).ready(function() {
            // Re-use formatCourse dari Add
            function formatCourse(opt) {
                if (!opt.id) return opt.text;
                const name = opt.text;
                let path = $(opt.element).data('path') || '';
                if (path.startsWith(name)) {
                    path = path.slice(name.length).replace(/^ *>\s*/, '');
                }
                return `
          <div>
            <div>${name}</div>
            ${path?`<div class="text-muted" style="font-size:.85em;">${path}</div>`:''}
          </div>
        `;
            }

            // Inisialisasi Select2 untuk Edit modal
            $('#editQuestionnaireModal').on('shown.bs.modal', function() {
                $('#edit-courses')
                    .select2({
                        dropdownParent: $('#editQuestionnaireModal'),
                        width: '100%',
                        dropdownAutoWidth: true,
                        templateResult: formatCourse,
                        templateSelection: formatCourse,
                        escapeMarkup: m => m
                    });
            });

            // Optional: destroy ketika modal ditutup agar inisialisasi ulang bersih
            $('#editQuestionnaireModal').on('hidden.bs.modal', function() {
                $('#edit-courses').select2('destroy');
            });
        });
    </script>

    {{-- DELETE --}}
    <script>
        $(document).on('click', '.btn-delete', function() {
            const $btn = $(this);
            const $form = $btn.closest('form');
            const url = $form.attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

            swal({
                    title: "Yakin?",
                    text: "Data kuesioner akan dihapus permanen!",
                    icon: "warning",
                    buttons: ["Batal", "Hapus"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (!willDelete) return;

                    // loading
                    swal({
                        title: 'Menghapus…',
                        buttons: false,
                        closeOnClickOutside: false
                    });

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            swal.close();
                            if (data.success) {
                                swal("Berhasil!", "Kuesioner telah dihapus.", "success");
                                // remove row dari DataTable atau reload page
                                $form.closest('tr').fadeOut(300, () => $(this).remove());
                            } else {
                                swal("Gagal", "Gagal menghapus data.", "error");
                            }
                        })
                        .catch(err => {
                            swal.close();
                            swal("Error", err.message, "error");
                        });
                });
        });
    </script>
@endsection
