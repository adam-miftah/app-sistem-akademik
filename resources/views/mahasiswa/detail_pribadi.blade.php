@extends('layouts.app')

@section('title', 'Detail Pribadi Mahasiswa')
@section('header_title', 'Detail Pribadi')

@section('content')
  <div class="container-fluid px-0 px-md-3">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
      <h6 class="m-0 font-weight-bold">
      <i class="fas fa-user-graduate me-2"></i>Informasi Pribadi dan Akademik
      </h6>
    </div>

    <div class="card-body">
      <div class="row g-4">
      <!-- Data Pribadi -->
      <div class="col-lg-6">
        <div class="profile-section">
        <div class="section-header bg-light px-3 py-2 mb-3 rounded">
          <h5 class="m-0 text-primary">
          <i class="fas fa-id-card me-2"></i>Data Pribadi
          </h5>
        </div>

        <div class="info-card">
          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-user me-2"></i>Nama Lengkap
          </div>
          <div class="info-value">{{ $mahasiswa->nama }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-id-badge me-2"></i>NIM
          </div>
          <div class="info-value">{{ $mahasiswa->nim }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-envelope me-2"></i>Email
          </div>
          <div class="info-value">{{ $mahasiswa->email }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-phone me-2"></i>Telepon
          </div>
          <div class="info-value">{{ $mahasiswa->telepon ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-birthday-cake me-2"></i>Tanggal Lahir
          </div>
          <div class="info-value">
            {{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d-m-Y') : '-' }}
          </div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-map-marker-alt me-2"></i>Alamat
          </div>
          <div class="info-value">{{ $mahasiswa->alamat ?? '-' }}</div>
          </div>
        </div>
        </div>
      </div>

      <!-- Data Akademik -->
      <div class="col-lg-6">
        <div class="profile-section">
        <div class="section-header bg-light px-3 py-2 mb-3 rounded">
          <h5 class="m-0 text-primary">
          <i class="fas fa-graduation-cap me-2"></i>Data Akademik
          </h5>
        </div>

        <div class="info-card">
          <div class="info-item">
          <div class="info-label">
            <i class="bi bi-calendar me-2"></i>Angkatan
          </div>
          <div class="info-value">{{ $mahasiswa->angkatan }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-university me-2"></i>Program Studi
          </div>
          <div class="info-value">{{ $mahasiswa->program_studi ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-chalkboard-teacher me-2"></i>Prog. Perkuliahan
          </div>
          <div class="info-value">{{ $mahasiswa->prog_perkuliahan ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="bi bi-easel me-2"></i>Kelas
          </div>
          <div class="info-value">{{ $mahasiswa->kelas ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-user-check me-2"></i>Status Mahasiswa
          </div>
          <div class="info-value">
            <span class="status-badge status-{{ strtolower($mahasiswa->status_mahasiswa ?? 'none') }}">
            {{ $mahasiswa->status_mahasiswa ?? '-' }}
            </span>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .card {
    border-radius: 12px;
    overflow: hidden;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
    border-radius: 0 !important;
    }

    .profile-section {
    height: 100%;
    }

    .section-header {
    border-left: 4px solid #4361ee;
    }

    .info-card {
    background-color: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    height: calc(100% - 50px);
    }

    .info-item {
    display: flex;
    flex-wrap: wrap;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
    border-bottom: none;
    }

    .info-label {
    flex: 0 0 40%;
    font-weight: 600;
    color: #6c757d;
    display: flex;
    align-items: center;
    }

    .info-value {
    flex: 1;
    font-weight: 500;
    }

    .status-badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
    }

    .status-aktif {
    background-color: #d4edda;
    color: #155724;
    }

    .status-nonaktif {
    background-color: #f8d7da;
    color: #721c24;
    }

    .status-cutii {
    background-color: #fff3cd;
    color: #856404;
    }

    .status-lulus {
    background-color: #cce5ff;
    color: #004085;
    }

    .status-none {
    background-color: #e2e3e5;
    color: #383d41;
    }

    @media (max-width: 992px) {
    .info-label {
      flex: 0 0 45%;
    }
    }

    @media (max-width: 768px) {
    .info-label {
      flex: 0 0 100%;
      margin-bottom: 0.25rem;
    }

    .info-value {
      flex: 0 0 100%;
      padding-left: 1.75rem;
    }
    }

    @media (max-width: 576px) {
    .container-fluid {
      padding-left: 10px;
      padding-right: 10px;
    }

    .info-card {
      padding: 0.75rem;
    }

    .info-item {
      padding: 0.5rem 0;
    }
    }
  </style>
@endpush