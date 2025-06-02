@extends('layouts.app')

@section('title', 'Kartu Hasil Studi (KHS) Mahasiswa')
@section('header_title', 'Kartu Hasil Studi (KHS)')

@section('content')
  <style>
    /* Global Styles & Variables (if not already in layouts/app.blade.php) */
    :root {
    --primary-color: #4A63E8;
    /* Biru cerah */
    --secondary-color: #6C757D;
    /* Abu-abu gelap */
    --accent-color: #1ABC9C;
    /* Hijau tosca untuk badge */
    --background-light: #F8F9FA;
    /* Latar belakang sangat terang */
    --card-background: #FFFFFF;
    /* Latar belakang kartu/kontainer */
    --border-color: #E0E0E0;
    /* Warna border halus */
    --text-color-dark: #343A40;
    /* Teks gelap */
    --text-color-light: #FDFDFD;
    /* Teks terang */
    --success-bg: #D4EDDA;
    --success-text: #155724;
    --danger-bg: #F8D7DA;
    --danger-text: #721C24;
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    body {
    font-family: 'Poppins', sans-serif;
    /* Gunakan font modern */
    background-color: var(--background-light);
    color: var(--text-color-dark);
    }

    /* Notifikasi */
    .alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    /* Sudut lebih melengkung */
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-weight: 500;
    box-shadow: var(--shadow-light);
    }

    .alert-success {
    background-color: var(--success-bg);
    color: var(--success-text);
    border-left: 5px solid #28A745;
    }

    .alert-danger {
    background-color: var(--danger-bg);
    color: var(--danger-text);
    border-left: 5px solid #DC3545;
    }

    .alert i {
    font-size: 1.25rem;
    }

    /* Header Halaman */
    .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
    /* Margin lebih besar */
    flex-wrap: wrap;
    gap: 1.5rem;
    }

    .page-title {
    margin: 0;
    color: var(--primary-color);
    font-size: 1.5rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Tombol Cetak */
    .print-button {
    padding: 0.9rem 1.8rem;
    /* Padding lebih besar */
    border-radius: 50px;
    /* Tombol kapsul */
    background-color: var(--primary-color);
    color: var(--text-color-light);
    border: none;
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    /* Transisi halus */
    box-shadow: 0 4px 10px rgba(74, 99, 232, 0.3);
    text-decoration: none;
    }

    .print-button:hover {
    background-color: #3A53D3;
    /* Slightly darker on hover */
    transform: translateY(-2px);
    /* Efek angkat */
    box-shadow: 0 6px 15px rgba(74, 99, 232, 0.4);
    }

    .print-button i {
    font-size: 1.1rem;
    }

    /* Informasi Mahasiswa */
    .student-info {
    background-color: var(--card-background);
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color-dark);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: var(--shadow-light);
    border-left: 5px solid var(--primary-color);
    }

    .student-info i {
    color: var(--primary-color);
    font-size: 1.5rem;
    }

    /* Tabel Data */
    .data-table-container {
    overflow-x: auto;
    background: var(--card-background);
    border-radius: 15px;
    /* Sudut lebih melengkung */
    box-shadow: var(--shadow-light);
    margin-bottom: 2rem;
    transition: box-shadow 0.3s ease;
    }

    .data-table-container:hover {
    box-shadow: var(--shadow-hover);
    }

    .data-table {
    width: 100%;
    border-collapse: separate;
    /* Gunakan separate untuk border-radius */
    border-spacing: 0;
    /* Hilangkan spasi antar sel */
    min-width: 1100px;
    /* Pastikan tabel tidak terlalu sempit */
    }

    .data-table thead {
    background-color: var(--primary-color);
    color: var(--text-color-light);
    font-size: 0.95rem;
    }

    .data-table thead th:first-child {
    border-top-left-radius: 15px;
    /* Sudut melengkung untuk header */
    }

    .data-table thead th:last-child {
    border-top-right-radius: 15px;
    }

    .grade-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    /* Ukuran badge lebih besar */
    height: 40px;
    border-radius: 50%;
    /* Lingkaran sempurna */
    font-weight: 700;
    /* Lebih tebal */
    font-size: 1rem;
    /* Ukuran font lebih besar */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .grade-a {
    background-color: #D4EDDA;
    color: #155724;
    }

    .grade-b {
    background-color: #CCE5FF;
    color: #004085;
    }

    .grade-c {
    background-color: #FFF3CD;
    color: #856404;
    }

    .grade-d {
    background-color: #F8D7DA;
    color: #721C24;
    }

    .grade-e {
    background-color: #F8D7DA;
    color: #721C24;
    }

    .grade-none {
    background-color: #E2E3E5;
    color: #383D41;
    }


    .data-table th {
    padding: 1.2rem 1.5rem;
    /* Padding lebih besar */
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    }

    .data-table td {
    padding: 1.2rem 1.5rem;
    /* Padding lebih besar */
    border-bottom: 1px solid var(--border-color);
    transition: background-color 0.2s ease;
    }

    .data-table tbody tr:last-child td {
    border-bottom: none;
    }

    .data-table tbody tr:hover {
    background-color: rgba(74, 99, 232, 0.05);
    /* Hover lebih halus */
    }

    /* Section Kelas */
    .class-section {
    margin-bottom: 3rem;
    /* Margin lebih besar antar kelas */
    border: 1px solid var(--border-color);
    border-radius: 15px;
    /* Sudut lebih melengkung */
    overflow: hidden;
    box-shadow: var(--shadow-light);
    background-color: var(--card-background);
    }

    .class-header {
    background-color: var(--background-light);
    /* Latar belakang header kelas yang lebih terang */
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    font-weight: 700;
    /* Lebih tebal */
    color: var(--primary-color);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    }

    .class-header i {
    color: var(--primary-color);
    font-size: 1.3rem;
    }


    /* Tampilan Kosong */
    .empty-state {
    text-align: center;
    padding: 3rem;
    /* Padding lebih besar */
    color: var(--secondary-color);
    background-color: var(--card-background);
    border-radius: 15px;
    box-shadow: var(--shadow-light);
    margin-top: 2rem;
    }

    .empty-state i {
    font-size: 3.5rem;
    /* Ukuran icon lebih besar */
    margin-bottom: 1.5rem;
    color: #ADB5BD;
    }

    .empty-state h3 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: var(--text-color-dark);
    }

    .empty-state p {
    font-size: 1.1rem;
    font-weight: 500;
    }

    /* Footer IPK */
    .ipk-row {
    background-color: var(--background-light);
    /* Latar belakang IPK row */
    font-weight: 700;
    color: var(--primary-color);
    /* Teks IPK row */
    }

    .ipk-row td {
    border-bottom: none !important;
    padding-top: 1.5rem !important;
    /* Lebih banyak padding atas */
    padding-bottom: 1.5rem !important;
    /* Lebih banyak padding bawah */
    }

    .ipk-value {
    font-size: 1.3rem;
    /* Ukuran font lebih besar */
    color: var(--primary-color);
    }

    /* Responsif */
    @media (max-width: 1200px) {
    .data-table {
      min-width: 900px;
    }

    .data-table td:nth-child(1),
    .data-table th:nth-child(1) {
      display: none;
      /* Sembunyikan Kode MK */
    }
    }

    @media (max-width: 992px) {
    .page-title {
      font-size: 1.8rem;
    }

    .student-info {
      font-size: 1rem;
      padding: 1.2rem;
    }

    .data-table {
      min-width: 750px;
    }

    .data-table td:nth-child(4),
    /* Sembunyikan Kehadiran */
    .data-table th:nth-child(4),
    .data-table td:nth-child(5),
    /* Sembunyikan Tugas */
    .data-table th:nth-child(5),
    .data-table td:nth-child(6),
    /* Sembunyikan UTS */
    .data-table th:nth-child(6) {
      display: none;
    }
    }

    @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .page-title {
      font-size: 1.5rem;
    }

    .print-button {
      width: 100%;
      justify-content: center;
      padding: 0.75rem 1rem;
      font-size: 0.9rem;
    }

    .student-info {
      font-size: 0.9rem;
      padding: 1rem;
    }

    .data-table th,
    .data-table td {
      padding: 0.8rem 1rem;
    }

    .data-table {
      min-width: 600px;
    }

    .grade-badge {
      width: 35px;
      height: 35px;
      font-size: 0.9rem;
    }

    .class-header {
      font-size: 1rem;
      padding: 1rem;
    }
    }

    @media (max-width: 576px) {
    .data-table {
      min-width: 480px;
    }

    .data-table td:nth-child(7),
    /* Sembunyikan UAS */
    .data-table th:nth-child(7),
    .data-table td:nth-child(3),
    /* Sembunyikan SKS */
    .data-table th:nth-child(3) {
      display: none;
    }

    .grade-badge {
      width: 30px;
      height: 30px;
      font-size: 0.85rem;
    }

    .ipk-value {
      font-size: 1.1rem;
    }

    .ipk-row td {
      padding: 1rem !important;
    }
    }

    /* --- Print Specific Styles (Optimized for Modern UI) --- */
    @media print {
    body * {
      visibility: hidden;
      /* Sembunyikan semua elemen di body secara default */
    }

    .content-area,
    .content-area * {
      visibility: visible;
      /* Tampilkan hanya elemen di dalam content-area */
    }

    .content-area {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      padding: 20px;
      box-shadow: none;
      /* Hilangkan bayangan saat dicetak */
      overflow: visible;
      /* Pastikan konten tidak terpotong */
      background-color: #fff;
      /* Pastikan latar belakang putih */
      margin: 0;
      /* Remove any external margins */
    }

    .page-header {
      display: flex;
      /* Gunakan flex untuk tata letak yang konsisten */
      justify-content: center;
      /* Pusatkan judul saat dicetak */
      align-items: center;
      margin-bottom: 25px;
      flex-direction: row;
      /* Pastikan header tetap di baris */
    }

    .page-title {
      color: #000 !important;
      /* Pastikan teks berwarna hitam saat dicetak */
      font-size: 28px !important;
      /* Ukuran font lebih besar untuk judul cetak */
      font-weight: 700 !important;
      text-align: center;
      /* Pusatkan teks judul */
      width: 100%;
      /* Make sure it takes full width for centering */
      text-shadow: none !important;
    }

    .print-button,
    .alert {
      display: none !important;
      /* Sembunyikan tombol cetak dan notifikasi */
    }

    .student-info {
      background-color: #f5f5f5 !important;
      border-left-color: #a0a0a0 !important;
      color: #000 !important;
      box-shadow: none !important;
      padding: 10px 15px !important;
      font-size: 1rem !important;
      margin-bottom: 15px !important;
    }

    .student-info i {
      color: #000 !important;
    }

    .class-section {
      border: 1px solid #ddd !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      margin-bottom: 20px !important;
      background-color: #fff !important;
    }

    .class-header {
      background-color: #e0e0e0 !important;
      border-bottom: 1px solid #ccc !important;
      color: #000 !important;
      padding: 10px 15px !important;
      font-size: 1.1rem !important;
    }

    .class-header i {
      color: #000 !important;
    }

    .data-table-container {
      overflow-x: visible;
      /* Pastikan tabel terlihat penuh tanpa scroll */
      box-shadow: none !important;
      border: none !important;
      /* Border handled by class-section */
      border-radius: 0 !important;
      /* Hilangkan border-radius */
      margin-bottom: 0 !important;
      /* Remove bottom margin */
    }

    .data-table {
      min-width: unset !important;
      /* Hapus min-width agar tabel dapat menyesuaikan */
      width: 100% !important;
      /* Ensure table takes full print width */
      border-collapse: collapse !important;
      /* Use collapse for consistent borders */
    }

    .data-table thead th,
    .data-table tbody td {
      border: 1px solid #ddd !important;
      /* Pastikan semua sel tabel memiliki border */
      color: #000 !important;
      /* Pastikan teks berwarna hitam */
      background-color: #fff !important;
      /* Pastikan latar belakang sel putih */
      padding: 10px 15px !important;
      /* Sesuaikan padding untuk cetak */
      font-size: 13px !important;
    }

    .data-table thead {
      background-color: #e0e0e0 !important;
      /* Latar belakang header tabel yang lebih terang */
      color: #000 !important;
    }

    .data-table th:first-child,
    .data-table th:last-child {
      border-radius: 0 !important;
      /* Remove border-radius on headers */
    }

    .data-table tbody tr:hover {
      background-color: #f8f8f8 !important;
      /* Hover effect yang lebih ringan untuk cetak */
    }

    .grade-badge {
      box-shadow: none !important;
      background-color: #f0f0f0 !important;
      /* Default background for print */
      color: #000 !important;
      border: 1px solid #ccc !important;
    }

    /* Specific grade colors for print */
    .grade-a {
      background-color: #d4edda !important;
      color: #155724 !important;
    }

    .grade-b {
      background-color: #cce5ff !important;
      color: #004085 !important;
    }

    .grade-c {
      background-color: #fff3cd !important;
      color: #856404 !important;
    }

    .grade-d {
      background-color: #f8d7da !important;
      color: #721c24 !important;
    }

    .grade-e {
      background-color: #f8d7da !important;
      color: #721c24 !important;
    }

    .grade-none {
      background-color: #e2e3e5 !important;
      color: #383d41 !important;
    }

    .ipk-row {
      background-color: #f0f0f0 !important;
      color: #000 !important;
      font-weight: bold !important;
    }

    .ipk-value {
      color: #000 !important;
    }

    /* Ensure hidden columns remain hidden for print if media query applies */
    @media (max-width: 1200px) {

      .data-table td:nth-child(1),
      .data-table th:nth-child(1) {
      display: none !important;
      }
    }

    @media (max-width: 992px) {

      .data-table td:nth-child(4),
      .data-table th:nth-child(4),
      .data-table td:nth-child(5),
      .data-table th:nth-child(5),
      .data-table td:nth-child(6),
      .data-table th:nth-child(6) {
      display: none !important;
      }
    }

    @media (max-width: 576px) {

      .data-table td:nth-child(7),
      .data-table th:nth-child(7),
      .data-table td:nth-child(3),
      .data-table th:nth-child(3) {
      display: none !important;
      }
    }
    }
  </style>

  <div class="content-area">
    @if(session('success'))
    <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error') }}
    </div>
    @endif

    <div class="page-header">
    <h3 class="page-title">Kartu Hasil Studi</h3>
    <button onclick="window.print()" class="print-button">
      <i class="fas fa-print"></i> Cetak KHS
    </button>
    </div>

    <div class="student-info">
    <i class="fas fa-user-graduate"></i>
    {{ $mahasiswa->nama }} (NIM: **{{ $mahasiswa->nim }}**)
    </div>

    @if($nilaiPerKelas->isEmpty())
    <div class="data-table-container">
    <div class="empty-state">
      <i class="fas fa-file-excel"></i>
      <h3 class="mt-3">Belum ada data nilai</h3>
      <p>Tidak ada data nilai yang tersedia saat ini untuk ditampilkan.</p>
    </div>
    </div>
    @else
    @foreach($nilaiPerKelas as $kelas => $nilaiList)
    <div class="class-section">
    <div class="class-header">
      <i class="fas fa-layer-group"></i>Kelas: **{{ $kelas }}**
    </div>

    <div class="data-table-container">
      <table class="data-table">
      <thead>
      <tr>
      <th>Kode MK</th>
      <th>Mata Kuliah</th>
      <th class="text-center">SKS</th>
      <th class="text-center">Kehadiran (%)</th>
      <th class="text-center">Tugas</th>
      <th class="text-center">UTS</th>
      <th class="text-center">UAS</th>
      <th class="text-center">Nilai Angka</th>
      <th class="text-center">Grade</th>
      <th class="text-center">Mutu</th>
      </tr>
      </thead>
      <tbody>
      @php
      $totalSKSPerKelas = 0;
      $totalSKSxMutuPerKelas = 0;
    @endphp

      @foreach($nilaiList as $nilai)
      <tr>
      <td class="fw-bold text-primary">{{ $nilai->mataKuliah->kode_mk }}</td>
      <td>{{ $nilai->mataKuliah->nama_mk }}</td>
      <td class="text-center">{{ $nilai->mataKuliah->sks }}</td>
      <td class="text-center">{{ $nilai->kehadiran ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_tugas ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_uts ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_uas ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_angka ?? '-' }}</td>
      <td class="text-center">
      <span class="grade-badge grade-{{ strtolower($nilai->nilai_huruf ?? 'none') }}">
      {{ $nilai->nilai_huruf ?? '-' }}
      </span>
      </td>
      <td class="text-center fw-bold">{{ $nilai->mutu ?? '-' }}</td>
      </tr>
      @php
      if ($nilai->mutu !== null && $nilai->mataKuliah && $nilai->mataKuliah->sks !== null) {
      $totalSKSPerKelas += $nilai->mataKuliah->sks;
      $totalSKSxMutuPerKelas += ($nilai->mataKuliah->sks * $nilai->mutu);
      }
      @endphp
      @endforeach

      <tr class="ipk-row">
      <td colspan="2" class="fw-bold">Indeks Prestasi Kelas (IPK)</td>
      <td class="text-center fw-bold">{{ $totalSKSPerKelas }}</td> {{-- Total SKS for the class --}}
      <td colspan="6"></td> {{-- Empty cells for alignment --}}
      <td class="text-center ipk-value">
      @if($totalSKSPerKelas > 0)
      {{ number_format($totalSKSxMutuPerKelas / $totalSKSPerKelas, 2) }}
      @else
      0.00
      @endif
      </td>
      </tr>
      </tbody>
      </table>
    </div>
    </div>
    @endforeach
    @endif
  </div>
@endsection