@extends('layouts.app')

@section('title', 'Edit Detail Pribadi')
@section('header_title', 'Edit Detail Pribadi')

@section('content')
  <div class="container-fluid px-0 px-md-3">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
      <h6 class="m-0 font-weight-bold">
      <i class="fas fa-edit me-2"></i>Formulir Edit Detail Pribadi
      </h6>
    </div>
    <div class="card-body p-4">
      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

      <form action="{{ route('mahasiswa.profil.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">
        {{-- Nama Lengkap --}}
        <div class="col-md-6">
        <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
          value="{{ old('nama', $mahasiswa->user->name ?? $mahasiswa->nama) }}" required>
        @error('nama')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        {{-- Email --}}
        <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
          value="{{ old('email', $mahasiswa->user->email ?? $mahasiswa->email) }}" required>
        @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        {{-- Nomor Telepon --}}
        <div class="col-md-6">
        <label for="telepon" class="form-label fw-semibold">Nomor Telepon</label>
        <input type="tel" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon"
          value="{{ old('telepon', $mahasiswa->telepon) }}" placeholder="Contoh: 081234567890">
        @error('telepon')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        {{-- Tanggal Lahir --}}
        <div class="col-md-6">
        <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir</label>
        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir"
          name="tanggal_lahir"
          value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : '') }}">
        @error('tanggal_lahir')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        {{-- Alamat --}}
        <div class="col-12">
        <label for="alamat" class="form-label fw-semibold">Alamat</label>
        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
          placeholder="Masukkan alamat lengkap Anda">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
        @error('alamat')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
      </div>

      <hr class="my-4">

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('mahasiswa.profil.detail') }}" class="btn btn-secondary">
        <i class="fas fa-times me-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i> Simpan Perubahan
        </button>
      </div>
      </form>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .form-label.fw-semibold {
    color: #333;
    /* Warna label lebih jelas */
    }

    .form-control:focus {
    border-color: var(--primary-color, #4361ee);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .btn-primary {
    background-color: var(--primary-color, #4361ee);
    border-color: var(--primary-color, #4361ee);
    }

    .btn-primary:hover {
    background-color: var(--primary-hover, #3a56d4);
    border-color: var(--primary-hover, #3a56d4);
    }

    .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    }

    .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
    }
  </style>
@endpush