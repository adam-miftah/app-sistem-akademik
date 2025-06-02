@extends('layouts.app')

@section('title', 'Tambah Dosen Baru - Admin')
@section('header_title', 'Tambah Dosen Baru')

@section('content')
  <style>
    /* Form Container */
    .form-container {
    background: var(--white-bg);
    padding: 15px;
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    }

    .card-header {
    background-color: var(--primary-color);
    color: white;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 10px 10px 0 0 !important;
    }

    .card-title {
    margin: 0;
    font-size: 1.25rem;
    }

    /* Error Notification */
    .alert-error {
    background-color: #fce8e8;
    color: #cc0000;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #dc3545;
    }

    /* Form Groups */
    .form-group {
    margin-bottom: 1.5rem;
    }

    .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-weight: 500;
    }

    .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    transition: var(--transition);
    }

    .form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    /* Form Actions */
    .form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    }

    /* Input Groups with Icons */
    .input-group {
    position: relative;
    }

    .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    }

    .input-group .form-control {
    padding-left: 2.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
    .form-container {
      padding: 1.5rem;
    }

    .form-actions {
      flex-direction: column;
    }

    .btn {
      width: 100%;
    }
    }
  </style>

  <div class="content-area">
    <div class="form-container">
    <div class="card-header">
      <h4 class="card-title">Form Tambah Dosen</h4>
    </div>

    @if ($errors->any())
    <div class="alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <ul style="margin: 0.5rem 0 0 1rem;">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('admin.dosens.store') }}" method="POST">
      @csrf

      {{-- Bagian untuk Data Akun User --}}
      <div class="form-group">
      <label for="user_name">Nama</label>
      <div class="input-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" id="user_name" name="user_name" value="{{ old('user_name') }}" class="form-control"
        placeholder="Masukkan nama pengguna" required>
      </div>
      @error('user_name')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>

      <div class="form-group">
      <label for="user_email">Email</label>
      <div class="input-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" id="user_email" name="user_email" value="{{ old('user_email') }}" class="form-control"
        placeholder="Masukkan email pengguna" required>
      </div>
      @error('user_email')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>

      <div class="form-group">
      <label for="password">Password</label>
      <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password"
        required>
      </div>
      @error('password')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>

      <div class="form-group">
      <label for="password_confirmation">Konfirmasi Password</label>
      <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
        placeholder="Konfirmasi password" required>
      </div>
      @error('password_confirmation')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>

      <hr style="margin: 2rem 0; border-color: var(--border-color);">
      <h4>Data Dosen</h4>
      <p class="text-muted">Informasi spesifik mengenai profil dosen.</p>

      {{-- Bagian untuk Data Dosen --}}
      <div class="form-group">
      <label for="nama">Nama Dosen</label>
      <div class="input-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="form-control"
        placeholder="Masukkan nama dosen" required>
      </div>
      @error('nama')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>
      <div class="form-group">
      <label for="email">Email Dosen</label>
      <div class="input-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control"
        placeholder="Masukkan email dosen" required>
      </div>
      @error('email')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>
      <div class="form-group">
      <label for="nidn">NIDN (opsional)</label>
      <div class="input-group">
        <i class="fas fa-id-card input-icon"></i>
        <input type="text" id="nidn" name="nidn" value="{{ old('nidn') }}" class="form-control"
        placeholder="Masukkan NIDN dosen">
      </div>
      @error('nidn')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>
      <div class="form-group">
      <label for="prodi">Program Studi (opsional)</label>
      <div class="input-group">
        <i class="fas fa-graduation-cap input-icon"></i>
        <input type="text" id="prodi" name="prodi" value="{{ old('prodi') }}" class="form-control"
        placeholder="Masukkan program studi dosen">
      </div>
      @error('prodi')
      <div class="text-danger">{{ $message }}</div>
    @enderror
      </div>
      <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Dosen
      </button>
      <a href="{{ route('admin.dosens.index') }}" class="btn btn-secondary">
        <i class="fas fa-times"></i> Batal
      </a>
      </div>
    </form>
    </div>
  </div>
@endsection