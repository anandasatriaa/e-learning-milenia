<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Divisi</th>
            <th>Nama Course/Kelas</th>
            <th>Tgl Enroll</th>
            <th>Tgl Selesai Mengerjakan</th>
            <th>Nilai Quiz</th>
            <th>Nilai Essay</th>
            <th>Nilai Praktek</th>
            <th>Kompetensi (%)</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($users as $user)
            @foreach ($user->courses as $enroll)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $user->Nama }}</td>
                    <td>{{ $user->Divisi }}</td>
                    <td>{{ $enroll->course->nama_kelas ?? '' }}</td>
                    <td>{{ optional($enroll->enroll_date)->format('Y-m-d') }}</td>
                    <td>{{ optional($enroll->finish_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ $enroll->nilai->nilai_quiz ?? 0 }}</td>
                    <td>{{ $enroll->nilai->nilai_essay ?? 0 }}</td>
                    <td>{{ $enroll->nilai->nilai_praktek ?? 0 }}</td>
                    <td>{{ $enroll->nilai->presentase_kompetensi ?? 0 }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>