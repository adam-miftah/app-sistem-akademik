@extends('layouts.app')

@section('title', 'Kelola Nilai Mahasiswa Dosen')
@section('header_title', 'Kelola Nilai Mahasiswa')

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

    .btn-primary {
    background-color: #4361ee;
    color: white;
    border: none;
    padding: 0.625rem 1.25rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    }

    .btn-primary:hover {
    background-color: #3a56d4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
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
    min-width: 1100px;
    }

    .data-table thead {
    background-color: #4361ee;
    color: white;
    }

    .grade-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-weight: 600;
    }

    .grade-A {
    background-color: #d4edda;
    color: #155724;
    }

    .grade-B {
    background-color: #cce5ff;
    color: #004085;
    }

    .grade-C {
    background-color: #fff3cd;
    color: #856404;
    }

    .grade-D {
    background-color: #f8d7da;
    color: #721c24;
    }

    .grade-E {
    background-color: #f8d7da;
    color: #721c24;
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

    /* Class Header */
    .class-header {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin: 1.5rem 0 1rem;
    font-weight: 600;
    color: #4361ee;
    display: flex;
    align-items: center;
    gap: 0.75rem;
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

    /* Footer Row */
    .summary-row {
    background-color: #f8f9fa;
    font-weight: 600;
    }

    .summary-row td {
    border-bottom: none !important;
    }

    .ipk-value {
    color: #4361ee;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {

    .data-table td:nth-child(4),
    .data-table th:nth-child(4),
    .data-table td:nth-child(5),
    .data-table th:nth-child(5) {
      display: none;
    }
    }

    @media (max-width: 992px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .data-table {
      min-width: 900px;
    }
    }

    @media (max-width: 768px) {

    .data-table th,
    .data-table td {
      padding: 0.75rem;
    }

    .grade-badge {
      width: 30px;
      height: 30px;
    }
    }

    @media (max-width: 576px) {

    .data-table td:nth-child(2),
    .data-table th:nth-child(2),
    .data-table td:nth-child(6),
    .data-table th:nth-child(6),
    .data-table td:nth-child(7),
    .data-table th:nth-child(7) {
      display: none;
    }

    .btn-primary {
      width: 100%;
      justify-content: center;
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
    <h3 class="page-title">Daftar Nilai Mahasiswa</h3>
    <a href="{{ route('dosen.kelolaNilaiMahasiswa.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Input Nilai Baru
    </a>
    </div>

    @if($nilaiPerKelas->isEmpty())
    <div class="data-table-container">
    <div class="empty-state">
      <i class="fas fa-clipboard-list"></i>
      <h3 class="mt-3">Anda belum menginput nilai</h3>
      <p>Belum ada data nilai yang tersedia untuk mahasiswa Anda.</p>
    </div>
    </div>
    @else
    @foreach($nilaiPerKelas as $kelas => $nilaiList)
    <div class="class-header">
    <i class="fas fa-users"></i>
    <span>Kelas: {{ $kelas }}</span>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
      <th>Mahasiswa</th>
      <th>NIM</th>
      <th>Mata Kuliah</th>
      <th>Kode MK</th>
      <th>SKS</th>
      <th class="text-center">Kehadiran</th>
      <th class="text-center">Tugas</th>
      <th class="text-center">UTS</th>
      <th class="text-center">UAS</th>
      <th class="text-center">Nilai Akhir</th>
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
      <td>{{ $nilai->mahasiswa->nama }}</td>
      <td>{{ $nilai->mahasiswa->nim }}</td>
      <td>{{ $nilai->mataKuliah->nama_mk }}</td>
      <td>{{ $nilai->mataKuliah->kode_mk }}</td>
      <td>{{ $nilai->mataKuliah->sks }}</td>
      <td class="text-center">{{ $nilai->kehadiran ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_tugas ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_uts ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_uas ?? '-' }}</td>
      <td class="text-center">{{ $nilai->nilai_angka ?? '-' }}</td>
      <td class="text-center">
      @if($nilai->nilai_huruf)
      <span class="grade-badge grade-{{ $nilai->nilai_huruf }}">
      {{ $nilai->nilai_huruf }}
      </span>
      @else
      -
      @endif
      </td>
      <td class="text-center">{{ number_format($nilai->mutu, 2) }}</td>
      </tr>
      @php
      if ($nilai->mutu !== null && $nilai->mataKuliah->sks !== null) {
      $totalSKSPerKelas += $nilai->mataKuliah->sks;
      $totalSKSxMutuPerKelas += ($nilai->mataKuliah->sks * $nilai->mutu);
      }
      @endphp
    @endforeach
      </tbody>
      <tfoot>
      <tr class="summary-row">
      <td colspan="4" class="text-center">Total SKS Kelas Ini</td>
      <td class="text-center">{{ $totalSKSPerKelas }}</td>
      <td colspan="5" class="text-center">IPK Kelas</td>
      <td colspan="2" class="text-center ipk-value">
      @if($totalSKSPerKelas > 0)
      {{ number_format($totalSKSxMutuPerKelas / $totalSKSPerKelas, 2) }}
      @else
      0.00
      @endif
      </td>
      </tr>
      </tfoot>
    </table>
    </div>
    @endforeach
    @endif
  </div>
@endsection