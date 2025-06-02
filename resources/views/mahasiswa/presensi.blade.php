@extends('layouts.app')

@section('title', 'Presensi Mahasiswa')
@section('header_title', 'Presensi Kuliah')

@section('content')
  <style>
    /* Notifikasi */
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

    /* Header Halaman */
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

    /* Tabel Data */
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
    min-width: 800px;
    }

    .data-table thead {
    background-color: #4361ee;
    color: white;
    }

    .time-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
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

    .page-title {
    margin: 0;
    color: var(--primary-color);
    font-size: 1.5rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Form Presensi */
    .attendance-form {
    display: flex;
    gap: 8px;
    align-items: center;
    }

    .status-select {
    min-width: 100px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    padding: 6px 12px;
    font-size: 0.9rem;
    }

    .attendance-btn {
    background-color: #4361ee;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    }

    .attendance-btn:hover {
    background-color: #3a56d4;
    }

    /* Tampilan Kosong */
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

    /* Responsif */
    @media (max-width: 992px) {

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

    .attendance-form {
      flex-direction: column;
      align-items: stretch;
      gap: 6px;
    }

    .status-select {
      width: 100%;
    }
    }

    @media (max-width: 576px) {

    .data-table td:nth-child(4),
    .data-table th:nth-child(4) {
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
    <h3 class="page-title">Daftar Jadwal Hari Ini ({{ $hariIni }})</h3>
    </div>

    @if($jadwalHariIni->isEmpty())
    <div class="data-table-container">
    <div class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <h3 class="mt-3">Tidak ada jadwal hari ini</h3>
      <p>Tidak ada jadwal kuliah yang terdaftar untuk kelas Anda hari ini.</p>
    </div>
    </div>
    @else
    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
      <th>Mata Kuliah</th>
      <th class="text-nowrap">Jam Kuliah</th>
      <th>Ruangan</th>
      <th>Dosen</th>
      <th class="text-center">Aksi</th>
      </tr>
      </thead>
      <tbody>
      @foreach($jadwalHariIni as $jadwal)
      <tr>
      <td>
      <div class="fw-bold">{{ $jadwal->mataKuliah->nama_mk }}</div>
      <small class="text-muted">{{ $jadwal->mataKuliah->kode_mk }}</small>
      </td>
      <td class="text-nowrap">
      <span class="time-badge">
      <i class="far fa-clock"></i>
      {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
      {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
      </span>
      </td>
      <td>
      <span class="ruangan-badge">{{ $jadwal->ruangan }}</span>
      </td>
      <td>{{ $jadwal->dosen->nama }}</td>
      <td class="text-center">
      <form action="{{ route('mahasiswa.presensi.submit') }}" method="POST" class="attendance-form">
      @csrf
      <input type="hidden" name="pengampu_mata_kuliah_id" value="{{ $jadwal->id }}">

      <select name="status_kehadiran" class="status-select @error('status_kehadiran') is-invalid @enderror"
      required>
      <option value="">Pilih Status</option>
      <option value="Hadir">Hadir</option>
      <option value="Sakit">Sakit</option>
      <option value="Izin">Izin</option>
      <option value="Alpha">Alpha</option>
      </select>
      <button type="submit" class="attendance-btn">
      <i class="fas fa-check"></i>
      <span class="d-none d-md-inline">Presensi</span>
      </button>
      @error('status_kehadiran')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      </form>
      </td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
    @endif
  </div>
@endsection