@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS) Mahasiswa')
@section('header_title', 'Kartu Rencana Studi (KRS)')

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

    /* Informasi Semester */
    .semester-info {
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

    .semester-info i {
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
    min-width: 900px;
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

    .sks-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e7ff;
    /* Latar belakang badge SKS yang lebih lembut */
    color: #4A63E8;
    /* Warna teks sesuai primary */
    font-weight: 700;
    font-size: 1rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

    /* Footer Total SKS */
    .total-sks-row {
    background-color: var(--background-light);
    /* Latar belakang total SKS row */
    font-weight: 700;
    color: var(--primary-color);
    /* Teks total SKS row */
    }

    .total-sks-row td {
    border-bottom: none !important;
    padding-top: 1.5rem !important;
    /* Lebih banyak padding atas */
    padding-bottom: 1.5rem !important;
    /* Lebih banyak padding bawah */
    }

    /* Card Note */
    .card-note {
    padding: 1.5rem;
    /* Padding lebih besar */
    background-color: var(--background-light);
    border-top: 1px solid var(--border-color);
    border-bottom-left-radius: 15px;
    /* Sudut melengkung */
    border-bottom-right-radius: 15px;
    font-size: 0.95rem;
    /* Ukuran font sedikit lebih besar */
    color: var(--secondary-color);
    display: flex;
    align-items: flex-start;
    /* Align icon to top */
    gap: 0.75rem;
    }

    .card-note i {
    color: var(--secondary-color);
    font-size: 1.1rem;
    margin-top: 0.15rem;
    /* Adjust icon vertical alignment */
    }

    /* Responsif untuk Tablet */
    @media (max-width: 992px) {
    .data-table {
      min-width: 600px;
    }

    .page-title {
      font-size: 1.8rem;
    }

    .semester-info {
      font-size: 1rem;
      padding: 1.2rem;
    }
    }

    /* Responsif untuk Mobile */
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

    .semester-info {
      font-size: 0.9rem;
      padding: 1rem;
    }

    .data-table th,
    .data-table td {
      padding: 0.8rem 1rem;
    }

    .data-table {
      min-width: 500px;
    }

    .sks-badge {
      width: 35px;
      height: 35px;
      font-size: 0.9rem;
    }

    .card-note {
      padding: 1rem;
      font-size: 0.85rem;
    }

    .card-note i {
      font-size: 1rem;
    }
    }

    @media (max-width: 576px) {
    .data-table {
      min-width: 400px;
    }

    /* Further reduce min-width for very small screens */
    .data-table td:nth-child(2),
    /* Sembunyikan Nama Mata Kuliah di layar sangat kecil */
    .data-table th:nth-child(2) {
      display: none;
    }

    .sks-badge {
      width: 30px;
      height: 30px;
      font-size: 0.8rem;
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

    .semester-info {
      background-color: #f5f5f5 !important;
      border-left-color: #a0a0a0 !important;
      color: #000 !important;
      box-shadow: none !important;
      padding: 10px 15px !important;
      font-size: 1rem !important;
      margin-bottom: 15px !important;
    }

    .semester-info i {
      color: #000 !important;
    }

    .data-table-container {
      overflow-x: visible;
      /* Pastikan tabel terlihat penuh tanpa scroll */
      box-shadow: none !important;
      border: 1px solid #ccc !important;
      /* Tambahkan border untuk tabel saat dicetak */
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

    .sks-badge {
      box-shadow: none !important;
      background-color: #f0f0f0 !important;
      /* Default background for print */
      color: #000 !important;
      border: 1px solid #ccc !important;
      width: 35px !important;
      height: 35px !important;
      font-size: 0.9em !important;
    }

    .total-sks-row {
      background-color: #f0f0f0 !important;
      color: #000 !important;
      font-weight: bold !important;
    }

    .card-note {
      background-color: #f5f5f5 !important;
      border-top: 1px solid #ddd !important;
      border-bottom-left-radius: 0 !important;
      border-bottom-right-radius: 0 !important;
      color: #000 !important;
      padding: 10px 15px !important;
      font-size: 0.9em !important;
    }

    .card-note i {
      color: #000 !important;
    }

    /* Ensure hidden columns remain hidden for print if media query applies */
    @media (max-width: 576px) {

      .data-table td:nth-child(2),
      .data-table th:nth-child(2) {
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
    <h3 class="page-title">Kartu Rencana Studi</h3>
    <button onclick="window.print()" class="print-button">
      <i class="fas fa-print"></i> Cetak KRS
    </button>
    </div>

    <div class="semester-info">
    <i class="fas fa-calendar-alt"></i>
    Informasi KRS untuk {{ $displaySemesterTitle }}
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>Kode MK</th>
        <th>Mata Kuliah</th>
        <th>Kelas</th>
        <th class="text-center">SKS</th>
      </tr>
      </thead>
      <tbody>
      @if($krsDetails->isEmpty())
      <tr>
      <td colspan="4" class="empty-state">
      <i class="fas fa-book-open"></i>
      <h3 class="mt-3">Belum ada mata kuliah yang terdaftar</h3>
      <p>Anda belum memiliki mata kuliah yang terdaftar pada semester ini.</p>
      <p>Silakan hubungi bagian akademik untuk informasi lebih lanjut mengenai pengisian KRS.</p>
      </td>
      </tr>
    @else
      @foreach($krsDetails as $item)
      <tr>
      <td>{{ $item->mataKuliah->kode_mk }}</td>
      <td>{{ $item->mataKuliah->nama_mk }}</td>
      <td>{{ $item->kelas }}</td>
      <td class="text-center">
      <span class="sks-badge">{{ $item->mataKuliah->sks }}</span>
      </td>
      </tr>
      @endforeach
      <tr class="total-sks-row">
        <td colspan="3" class="text-end">TOTAL SKS</td> {{-- Align text to the right --}}
        <td class="text-center">
        <span class="sks-badge">{{ $totalSKS }}</span>
        </td>
      </tr>
    @endif
      </tbody>
    </table>

    @if(!$krsDetails->isEmpty())
    <div class="card-note">
      <i class="fas fa-info-circle"></i>
      <div>
      Tampilan ini menunjukkan daftar mata kuliah yang telah Anda **rencana** untuk ambil pada semester ini.
      Pastikan Anda telah mengonfirmasi KRS Anda sesuai dengan prosedur akademik yang berlaku.
      </div>
    </div>
    @endif
    </div>
  </div>
@endsection