<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pegawai</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Data Pegawai</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Tempat Lahir</th>
                <th>Alamat</th>
                <th>Tgl Lahir</th>
                <th>L/P</th>
                <th>Gol</th>
                <th>Eselon</th>
                <th>Jabatan</th>
                <th>Tempat Tugas</th>
                <th>Agama</th>
                <th>Unit Kerja</th>
                <th>No.HP</th>
                <th>NPWP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $data['nip'] }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>{{ $data['tempat_lahir'] }}</td>
                <td>{{ $data['alamat'] }}</td>
                <td>{{ $data['tgl_lahir'] }}</td>
                <td>{{ $data['jenis_kelamin'] === 'l' ? 'L' : 'P' }}</td>
                <td>{{ $data['golongan'][0]['golongan'] ?? '' }}</td>
                <td>{{ $data['jabatan'][0]['eselon'] ?? '' }}</td>
                <td>{{ $data['jabatan'][0]['nama_jabatan'] ?? '' }}</td>
                <td>{{ $data['unit_kerja'][0]['tempat_tugas'] ?? '' }}</td>
                <td>{{ $data['agama'] }}</td>
                <td>{{ $data['unit_kerja'][0]['nama_unit_kerja'] ?? '' }}</td>
                <td>{{ $data['no_hp'] ?? '-' }}</td>
                <td>{{ $data['npwp'] }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
