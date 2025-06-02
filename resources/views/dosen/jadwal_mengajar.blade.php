@extends('layouts.app')

@section('title', 'Jadwal Mengajar Dosen')
@section('header_title', 'Jadwal Mengajar')

@section('content')
  <style>
    /* Notification Styles */
    .alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #28a745;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    }

    .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #dc3545;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    }

    /* Header Styles */
    .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
    }

    .page-title {
    margin: 0;
    color: var(--text-color);
    font-size: 1.5rem;
    font-weight: 600;
    }

    .current-date {
    background-color: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #6c757d;
    }

    /* Table Styles */
    .data-table-container {
    overflow-x: auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
    }

    .data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
    }

    .data-table thead {
    background-color: #4361ee;
    color: white;
    }

    .time-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 6px;
    background: #f0fdf9;
    color: #0d9488;
    font-weight: 500;
    }

    .ruangan-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 500;
    font-size: 0.85rem;
    }

    .data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 500;
    }

    .data-table td {
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    }

    .data-table tr:last-child td {
    border-bottom: none;
    }

    .data-table tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    }

    /* Empty State */
    .empty-state {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
    }

    .empty-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #adb5bd;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {

    .data-table td:nth-child(2),
    .data-table th:nth-child(2),
    .data-table td:nth-child(3),
    .data-table th:nth-child(3) {
      display: none;
    }
    }

    @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .data-table th,
    .data-table td {
      padding: 0.75rem;
    }
    }

    @media (max-width: 576px) {

    .data-table td:nth-child(7),
    .data-table th:nth-child(7) {
      display: none;
    }

    .time-badge i {
      display: none;
    }
    }
  </style>

  <div class="content-area">
    @if(session('success'))
    <div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error') }}
    </div>
    @endif

    <div class="page-header">
    <h3 class="page-title">Jadwal Mengajar Anda</h3>
    <div class="current-date">
      <i class="far fa-calendar-alt me-2"></i>
      {{ now()->translatedFormat('l, d F Y') }}
    </div>
    </div>

    @if($jadwalMengajar->isEmpty())
    <div class="data-table-container">
    <div class="empty-state">
      <i class="far fa-calendar-times"></i>
      <h3 class="mt-3">Anda belum memiliki jadwal mengajar</h3>
      <p>Silakan hubungi administrasi jika ini sebuah kesalahan</p>
    </div>
    </div>
    @else
    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
      <th>Mata Kuliah</th>
      <th>Kode</th>
      <th>SKS</th>
      <th>Hari</th>
      <th>Jam</th>
      <th>Ruangan</th>
      <th>Kelas</th>
      </tr>
      </thead>
      <tbody>
      @foreach($jadwalMengajar as $jadwal)
      <tr>
      <td>
      <div>{{ $jadwal->mataKuliah->nama_mk }}</div>
      </td>
      <td>{{ $jadwal->mataKuliah->kode_mk }}</td>
      <td>{{ $jadwal->mataKuliah->sks }}</td>
      <td>{{ $jadwal->hari }}</td>
      <td>
      <span class="time-badge">
      <i class="far fa-clock"></i>
      {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
      {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
      </span>
      </td>
      <td>
      <span class="ruangan-badge">{{ $jadwal->ruangan }}</span>
      </td>
      <td>{{ $jadwal->kelas }}</td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
    @endif
  </div>
@endsection