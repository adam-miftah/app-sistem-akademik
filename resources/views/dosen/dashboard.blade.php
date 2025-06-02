@extends('layouts.app')

@section('title', 'Dashboard Dosen')
@section('header_title', 'Dashboard Dosen')

@section('content')
  <div class="container-fluid px-0 px-lg-3">
    {{-- Welcome Header with Date --}}
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 gap-3">
      <div>
        <h1 class="h3 mb-1 text-gray-800">Selamat Datang</h1>
        <h2 class="h4 mb-0 text-primary fw-semibold">{{ $dosen->nama }}</h2>
      </div>
      <div class="d-flex align-items-center">
        <span class="badge bg-primary bg-opacity-10 text-primary p-2">
          <i class="fas fa-calendar-alt fa-sm me-2"></i>
          {{ now()->translatedFormat('l, d F Y') }}
        </span>
      </div>
    </div>

    {{-- Alert for success/error messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-0 mx-lg-0 mb-4" role="alert">
      <div class="d-flex align-items-center">
      <i class="fas fa-check-circle me-2"></i>
      <span>{{ session('success') }}</span>
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mx-0 mx-lg-0 mb-4" role="alert">
      <div class="d-flex align-items-center">
      <i class="fas fa-exclamation-circle me-2"></i>
      <span>{{ session('error') }}</span>
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
      {{-- Card: Total Mata Kuliah Diajar --}}
      <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                <i class="fas fa-book fa-lg"></i>
              </div>
              <div>
                <h6 class="mb-1 text-muted">Mata Kuliah Diajar</h6>
                <h3 class="mb-0 fw-bold">{{ $totalMataKuliahDiajar }}</h3>
              </div>
            </div>
          </div>
          <div class="card-footer bg-transparent border-0 py-3 px-4">
            <a href="#" class="text-primary text-decoration-none d-flex align-items-center justify-content-between">
              <span class="small">Lihat Detail</span>
              <i class="fas fa-arrow-right fa-sm"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Card: Total Jadwal Mengajar --}}
      <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 me-3">
                <i class="fas fa-calendar-alt fa-lg"></i>
              </div>
              <div>
                <h6 class="mb-1 text-muted">Jadwal Mengajar</h6>
                <h3 class="mb-0 fw-bold">{{ $totalJadwalMengajar }}</h3>
              </div>
            </div>
          </div>
          <div class="card-footer bg-transparent border-0 py-3 px-4">
            <a href="{{ route('dosen.lihatJadwalMengajar') }}" class="text-success text-decoration-none d-flex align-items-center justify-content-between">
              <span class="small">Lihat Jadwal</span>
              <i class="fas fa-arrow-right fa-sm"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Card: Total Nilai Diinput --}}
      <div class="col-xl-4 col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center">
              <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 me-3">
                <i class="fas fa-clipboard-list fa-lg"></i>
              </div>
              <div>
                <h6 class="mb-1 text-muted">Nilai Diinput</h6>
                <h3 class="mb-0 fw-bold">{{ $totalNilaiDiinput }}</h3>
              </div>
            </div>
          </div>
          <div class="card-footer bg-transparent border-0 py-3 px-4">
            <a href="{{ route('dosen.kelolaNilaiMahasiswa') }}" class="text-info text-decoration-none d-flex align-items-center justify-content-between">
              <span class="small">Kelola Nilai</span>
              <i class="fas fa-arrow-right fa-sm"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Content --}}
    <div class="row g-4">
      {{-- Jadwal Mengajar Mendatang --}}
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-primary">
              <i class="fas fa-calendar-day me-2"></i>Jadwal Mengajar Mendatang
            </h5>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="scheduleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="scheduleDropdown">
                <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Unduh Jadwal</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Cetak</a></li>
              </ul>
            </div>
          </div>
          <div class="card-body p-0">
            @if($upcomingSchedules->isEmpty())
        <div class="d-flex flex-column align-items-center justify-content-center p-4 text-center" style="min-height: 200px;">
          <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
          <p class="text-muted mb-2">Tidak ada jadwal mengajar mendatang</p>
          <small class="text-muted">Anda tidak memiliki jadwal mengajar dalam waktu dekat</small>
        </div>
        @else
            <div class="list-group list-group-flush">
            @foreach($upcomingSchedules as $jadwal)
          <div class="list-group-item list-group-item-action border-0 py-3 px-4">
          <div class="d-flex w-100 justify-content-between align-items-start mb-2">
          <h6 class="mb-0 fw-semibold text-primary">{{ $jadwal->mataKuliah->nama_mk }}</h6>
          <small class="text-muted">{{ $jadwal->hari }}</small>
          </div>
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2">
          <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-light text-dark">
            <i class="fas fa-clock me-1"></i>
            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
            </span>
            <span class="badge bg-light text-dark">
            <i class="fas fa-door-open me-1"></i>
            {{ $jadwal->ruangan }}
            </span>
          </div>
          <span class="badge bg-primary bg-opacity-10 text-primary">
            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->diffForHumans() }}
          </span>
          </div>
          </div>
          @endforeach
            </div>
        @endif
          </div>
          <div class="card-footer bg-white border-0 py-3 px-4">
            <a href="{{ route('dosen.lihatJadwalMengajar') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
              <span>Lihat Semua Jadwal</span>
              <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- Nilai Terbaru yang Diinput --}}
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-primary">
              <i class="fas fa-clipboard-check me-2"></i>Nilai Terbaru yang Diinput
            </h5>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="gradesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="gradesDropdown">
                <li><a class="dropdown-item" href="#"><i class="fas fa-plus me-2"></i>Tambah Nilai</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-export me-2"></i>Ekspor</a></li>
              </ul>
            </div>
          </div>
          <div class="card-body p-0">
            @if($recentGradesInputted->isEmpty())
        <div class="d-flex flex-column align-items-center justify-content-center p-4 text-center" style="min-height: 200px;">
          <i class="fas fa-clipboard-list text-muted fa-4x mb-3"></i>
          <p class="text-muted mb-3">Belum ada nilai yang baru diinput</p>
          <a href="{{ route('dosen.kelolaNilaiMahasiswa') }}" class="btn btn-primary">
          <i class="fas fa-plus me-1"></i> Input Nilai Sekarang
          </a>
        </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover mb-0">
            <thead class="bg-light">
              <tr>
              <th class="border-0">Mahasiswa</th>
              <th class="border-0">Mata Kuliah</th>
              <th class="border-0 text-end">Nilai</th>
              <th class="border-0 text-center">Grade</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentGradesInputted as $nilai)
            <tr>
            <td>
            <div class="d-flex align-items-center">
              <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
              <span>{{ substr($nilai->mahasiswa->nama, 0, 1) }}</span>
              </div>
              <span>{{ $nilai->mahasiswa->nama }}</span>
            </div>
            </td>
            <td>{{ $nilai->mataKuliah->nama_mk }}</td>
            <td class="text-end fw-semibold">{{ $nilai->nilai_angka ?? '-' }}</td>
            <td class="text-center">
            @if($nilai->nilai_huruf)
            <span class="badge 
            @if($nilai->nilai_huruf == 'A') bg-success bg-opacity-10 text-success
          @elseif($nilai->nilai_huruf == 'B') bg-info bg-opacity-10 text-info
          @elseif($nilai->nilai_huruf == 'C') bg-warning bg-opacity-10 text-warning
          @else bg-danger bg-opacity-10 text-danger
          @endif">
            {{ $nilai->nilai_huruf }}
            </span>
          @else
          <span class="badge bg-light text-secondary">-</span>
          @endif
            </td>
            </tr>
          @endforeach
            </tbody>
            </table>
          </div>
        @endif
          </div>
          <div class="card-footer bg-white border-0 py-3 px-4">
            <a href="{{ route('dosen.kelolaNilaiMahasiswa') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
              <span>Kelola Semua Nilai</span>
              <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mt-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-semibold text-primary">
              <i class="fas fa-bolt me-2"></i>Aksi Cepat
            </h5>
          </div>
          <div class="card-body py-3 px-4">
            <div class="row g-3">
              <div class="col-xl-3 col-md-6">
                <a href="{{ route('dosen.lihatJadwalMengajar') }}" class="card action-card h-100 text-decoration-none">
                  <div class="card-body text-center p-4">
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 mb-3 mx-auto">
                      <i class="fas fa-calendar-alt fa-lg"></i>
                    </div>
                    <h6 class="mb-0">Jadwal Mengajar</h6>
                  </div>
                </a>
              </div>
              <div class="col-xl-3 col-md-6">
                <a href="{{ route('dosen.kelolaNilaiMahasiswa') }}" class="card action-card h-100 text-decoration-none">
                  <div class="card-body text-center p-4">
                    <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 mb-3 mx-auto">
                      <i class="fas fa-clipboard-list fa-lg"></i>
                    </div>
                    <h6 class="mb-0">Input Nilai</h6>
                  </div>
                </a>
              </div>
              <div class="col-xl-3 col-md-6">
                <a href="#" class="card action-card h-100 text-decoration-none">
                  <div class="card-body text-center p-4">
                    <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 mb-3 mx-auto">
                      <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </div>
                    <h6 class="mb-0">Materi Kuliah</h6>
                  </div>
                </a>
              </div>
              <div class="col-xl-3 col-md-6">
                <a href="#" class="card action-card h-100 text-decoration-none">
                  <div class="card-body text-center p-4">
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 mb-3 mx-auto">
                      <i class="fas fa-tasks fa-lg"></i>
                    </div>
                    <h6 class="mb-0">Tugas & Quiz</h6>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('styles')
    <style>
    /* Custom Card Styles */
    .card {
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    /* Icon Shapes */
    .icon-shape {
      width: 48px;
      height: 48px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    /* Avatar Styles */
    .avatar-sm {
      width: 32px;
      height: 32px;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    /* Badge Styles */
    .badge {
      font-weight: 500;
      padding: 0.35em 0.65em;
    }

    /* Table Styles */
    .table-hover tbody tr {
      transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(var(--bs-primary-rgb), 0.03);
    }

    /* List Group Styles */
    .list-group-item {
      transition: all 0.2s ease;
    }

    .list-group-item:hover {
      background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .card-body {
      padding: 1.25rem;
      }

      .icon-shape {
      width: 40px;
      height: 40px;
      }
    }

    @media (max-width: 576px) {
      .header-left h3 {
      font-size: 1rem;
      }

      .content-wrapper {
      padding: 1rem;
      }
    }
    </style>
  @endpush
@endsection