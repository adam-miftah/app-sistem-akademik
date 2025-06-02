@extends('layouts.app')

@section('title', 'Jadwal Kuliah Mahasiswa')
@section('header_title', 'Jadwal Kuliah')

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
    /* remove underline if it's a link */
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

    /* Tabel Data */
    .data-table-container {
    overflow-x: auto;
    background: var(--card-background);
    border-radius: 15px;
    /* Sudut lebih melengkung */
    box-shadow: var(--shadow-light);
    margin-bottom: 2.5rem;
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

    .time-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    /* Jarak lebih besar */
    padding: 8px 14px;
    /* Padding lebih besar */
    border-radius: 8px;
    /* Sudut melengkung */
    background: #EAF7F5;
    /* Latar belakang badge lebih soft */
    color: var(--accent-color);
    font-weight: 600;
    font-size: 0.9rem;
    border: 1px solid #D1EBE7;
    /* Border tipis */
    }

    .time-badge i {
    color: #0D9488;
    /* Warna icon berbeda */
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

    .empty-state p {
    font-size: 1.1rem;
    font-weight: 500;
    }

    /* Responsif untuk Tablet */
    @media (max-width: 992px) {

    .data-table td:nth-child(1),
    .data-table th:nth-child(1) {
      display: none;
      /* Sembunyikan kolom kode MK di tablet */
    }

    .data-table {
      min-width: 700px;
    }

    .page-title {
      font-size: 1.8rem;
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

    .data-table th,
    .data-table td {
      padding: 0.8rem 1rem;
    }

    .data-table {
      min-width: 600px;
      /* Adjust min-width for smaller screens */
    }

    .time-badge {
      flex-direction: column;
      gap: 4px;
      padding: 6px 8px;
      font-size: 0.8rem;
    }
    }

    @media (max-width: 576px) {

    .data-table td:nth-child(2),
    /* Sembunyikan kolom Mata Kuliah di layar sangat kecil */
    .data-table th:nth-child(2) {
      display: none;
    }

    .data-table {
      min-width: 450px;
      /* Further reduce min-width */
    }

    .data-table th,
    .data-table td {
      padding: 0.6rem 0.8rem;
      font-size: 0.85rem;
    }

    .time-badge i {
      display: none;
      /* Sembunyikan ikon jam di layar sangat kecil */
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

    /* Ensure time-badge looks good in print */
    .time-badge {
      background: #f0f0f0 !important;
      color: #000 !important;
      border: 1px solid #ccc !important;
      padding: 5px 10px !important;
      border-radius: 5px !important;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-weight: normal;
      white-space: nowrap;
      /* Prevent breaking time */
    }

    .time-badge i {
      display: inline-block !important;
      /* Make sure icons are visible in print */
      font-size: 0.9em !important;
    }

    /* Ensure hidden columns remain hidden for print if media query applies */
    @media (max-width: 992px) {

      .data-table td:nth-child(1),
      .data-table th:nth-child(1) {
      display: none !important;
      }
    }

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
    <h3 class="page-title">Jadwal Kuliah</h3>
    <button onclick="window.print()" class="print-button">
      <i class="fas fa-print"></i> Cetak Jadwal
    </button>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>Kode MK</th>
        <th>Mata Kuliah</th>
        <th>Dosen</th>
        <th>Hari</th>
        <th>Jam</th>
        <th>Kelas</th>
        <th>Ruangan</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($sortedJadwalKuliahs as $jadwal)
      <tr data-hari="{{ $jadwal->hari }}">
      <td>{{ $jadwal->mataKuliah->kode_mk }}</td>
      <td>{{ $jadwal->mataKuliah->nama_mk }}</td>
      <td>{{ $jadwal->dosen->nama }}</td>
      <td>{{ $jadwal->hari }}</td>
      <td>
      <span class="time-badge">
        <i class="far fa-clock"></i>
        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
      </span>
      </td>
      <td><span>{{ $jadwal->kelas ?? '-' }}</span></td>
      <td>{{ $jadwal->ruangan }}</td>
      </tr>
    @empty
      <tr>
      <td colspan="7" class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <p>Tidak ada jadwal kuliah yang tersedia untuk Anda saat ini.</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection