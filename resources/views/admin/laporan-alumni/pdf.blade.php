
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $jenisLaporan }}</title>

    <style>
        @page {
            margin: 30px 35px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #263449;
            font-size: 10px;
        }

        .kop {
            width: 100%;
            border-bottom: 3px solid #1d6045;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .kop td {
            vertical-align: middle;
        }

        .logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .identitas {
            text-align: center;
            padding-right: 75px;
        }

        .identitas h2 {
            color: #1d6045;
            font-size: 16px;
            margin: 0 0 5px;
        }

        .identitas p {
            margin: 3px 0;
            font-size: 10px;
        }

        .judul {
            text-align: center;
            margin: 18px 0;
        }

        .judul h3 {
            color: #1d6045;
            font-size: 14px;
            margin: 0 0 7px;
        }

        .judul p {
            margin: 4px 0;
        }

        .ringkasan {
            background: #f1f7f3;
            border: 1px solid #cbded1;
            padding: 12px;
            text-align: center;
            margin-bottom: 18px;
        }

        .angka {
            color: #1d6045;
            font-size: 23px;
            font-weight: bold;
            margin-top: 5px;
        }

        .bagian {
            margin-top: 18px;
        }

        .bagian h4 {
            color: #1d6045;
            font-size: 12px;
            border-bottom: 1px solid #dce9df;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .grafik-row {
            width: 100%;
            margin: 8px 0 15px;
            table-layout: fixed;
        }

        .grafik-row td {
            width: 50%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }

        .grafik-row img {
            width: 100%;
            max-width: 245px;
            height: 190px;
        }

        .grafik-row .judul-grafik {
            font-weight: bold;
            color: #475569;
            margin-bottom: 7px;
            font-size: 9px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data th {
            background: #1d6045;
            color: white;
            padding: 8px;
            text-align: left;
        }

        table.data td {
            border: 1px solid #dce3ed;
            padding: 7px;
        }

        table.data tr:nth-child(even) {
            background: #f5f8fc;
        }

        .kosong {
            text-align: center;
            color: #64748b;
            padding: 12px;
        }

        .footer {
            margin-top: 25px;
            padding-top: 9px;
            border-top: 1px solid #dce9df;
            text-align: right;
            color: #64748b;
            font-size: 8px;
        }
    </style>
</head>

<body>

    {{-- KOP LAPORAN DAN LOGO FST --}}
    <table class="kop">
        <tr>
            <td style="width: 85px; text-align: left;">
                @if (file_exists($logoFst))
                    <img src="{{ $logoFst }}" class="logo">
                @endif
            </td>

            <td class="identitas">
                <h2>FAKULTAS SAINS DAN TEKNOLOGI</h2>
                <p>SISTEM INFORMASI ALUMNI</p>
                <p>Universitas Islam Negeri Imam Bonjol Padang</p>
            </td>
        </tr>
    </table>

    {{-- JUDUL LAPORAN --}}
    <div class="judul">
        <h3>{{ $jenisLaporan }}</h3>

        @if ($nilaiPilihan !== '')
            <p>Filter: <strong>{{ $nilaiPilihan }}</strong></p>
        @endif

        <p>Tanggal Cetak: {{ $tanggalCetak }}</p>
    </div>

    {{-- JUMLAH ALUMNI --}}
    <div class="ringkasan">
        <div>Jumlah Alumni</div>
        <div class="angka">
            {{ number_format($jumlahAlumni, 0, ',', '.') }}
        </div>
        <div>Orang</div>
    </div>

    {{-- LAPORAN PROGRAM STUDI --}}
    @if ($kategori === 'keseluruhan' || $kategori === 'prodi')
        <div class="bagian">
            <h4>Statistik Alumni Berdasarkan Program Studi</h4>

            <table class="grafik-row">
                <tr>
                    <td>
                        <div class="judul-grafik">Diagram Lingkaran</div>
                        @if (!empty($chartProdiImage))
                            <img src="{{ $chartProdiImage }}">
                        @endif
                    </td>
                    <td>
                        <div class="judul-grafik">Grafik Batang Horizontal</div>
                        @if (!empty($chartHorizontalProdiImage))
                            <img src="{{ $chartHorizontalProdiImage }}">
                        @endif
                    </td>
                </tr>
            </table>

            <table class="data">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Program Studi</th>
                        <th>Jumlah Alumni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($berdasarkanProdi as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->program_studi }}</td>
                            <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="kosong">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- LAPORAN TAHUN LULUS --}}
    @if ($kategori === 'keseluruhan' || $kategori === 'tahun')
        <div class="bagian">
            <h4>Statistik Alumni Berdasarkan Tahun Lulus</h4>

            <table class="grafik-row">
                <tr>
                    <td>
                        <div class="judul-grafik">Diagram Lingkaran</div>
                        @if (!empty($chartTahunImage))
                            <img src="{{ $chartTahunImage }}">
                        @endif
                    </td>
                    <td>
                        <div class="judul-grafik">Grafik Batang Horizontal</div>
                        @if (!empty($chartHorizontalTahunImage))
                            <img src="{{ $chartHorizontalTahunImage }}">
                        @endif
                    </td>
                </tr>
            </table>

            <table class="data">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tahun Lulus</th>
                        <th>Jumlah Alumni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($berdasarkanTahunLulus as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->tahun_lulus }}</td>
                            <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="kosong">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- LAPORAN STATUS PEKERJAAN --}}
    @if ($kategori === 'keseluruhan' || $kategori === 'status')
        <div class="bagian">
            <h4>Statistik Alumni Berdasarkan Status Pekerjaan</h4>

            <table class="grafik-row">
                <tr>
                    <td>
                        <div class="judul-grafik">Diagram Lingkaran</div>
                        @if (!empty($chartStatusImage))
                            <img src="{{ $chartStatusImage }}">
                        @endif
                    </td>
                    <td>
                        <div class="judul-grafik">Grafik Batang Horizontal</div>
                        @if (!empty($chartHorizontalStatusImage))
                            <img src="{{ $chartHorizontalStatusImage }}">
                        @endif
                    </td>
                </tr>
            </table>

            <table class="data">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Status Pekerjaan</th>
                        <th>Jumlah Alumni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($berdasarkanStatus as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->status_setelah_lulus }}</td>
                            <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="kosong">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        Dicetak melalui Sistem Informasi Alumni Fakultas Sains dan Teknologi
    </div>

</body>
</html>
