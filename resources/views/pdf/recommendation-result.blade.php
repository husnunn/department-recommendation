<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Hasil Rekomendasi Jurusan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 24px; margin-bottom: 8px; }
        .meta { color: #555; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .note { margin-top: 24px; font-size: 11px; color: #444; line-height: 1.5; }
    </style>
</head>
<body>
    <h1>Hasil Rekomendasi Jurusan</h1>
    <p class="meta">
        <strong>{{ $student->nama }}</strong><br>
        @if ($student->nisn) NISN: {{ $student->nisn }}<br> @endif
        @if ($student->kelas) Kelas: {{ $student->kelas }}<br> @endif
        @if ($student->asal_sekolah) Asal sekolah: {{ $student->asal_sekolah }}<br> @endif
        Tanggal: {{ $session->finished_at?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '—' }}
    </p>

    <h2>Top rekomendasi</h2>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Jurusan</th>
                <th>Persentase kecocokan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $row)
                <tr>
                    <td>{{ $row->rank }}</td>
                    <td>{{ $row->major->nama_jurusan ?? '—' }}</td>
                    <td>{{ number_format((float) $row->score, 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="note">
        Hasil ini adalah rekomendasi berdasarkan data kuesioner dan nilai akademik.
        Keputusan akhir tetap dapat mempertimbangkan minat pribadi, konsultasi guru BK, dan pilihan kampus.
    </p>
</body>
</html>
