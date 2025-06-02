@extends('layouts.app')

@section('title', 'Edit Dosen - Admin')
@section('header_title', 'Edit Dosen')

@section('content')
  <style>
    /* Reuse the same styles from the create form */
    .form-container {
    background: var(--white-bg);
    padding: 15px;
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    }

    /* .form-title {
    margin-top: 0;
    margin-bottom: 1.5rem;
    color: var(--text-color);
    font-size: 1.5rem;
    font-weight: 600;
    } */

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

    .alert-error {
    background-color: #fce8e8;
    color: #cc0000;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #dc3545;
    }

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

    .form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    }

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
      <h4 class="card-title">Form Edit Dosen: {{ $dosen->nama }}</h4>
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

    <form action="{{ route('admin.dosens.update', $dosen->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
      <label for="nama">Nama Dosen</label>
      <div class="input-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" class="form-control"
        placeholder="Masukkan nama dosen" required>
      </div>
      </div>
      <div class="form-group">
      <label for="email">Email Dosen</label>
      <div class="input-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" id="email" name="email" value="{{ old('email', $dosen->email) }}" class="form-control"
        placeholder="Masukkan email dosen" required>
      </div>
      </div>
      <div class="form-group">
      <label for="nidn">NIDN (opsional)</label>
      <div class="input-group">
        <i class="fas fa-id-card input-icon"></i>
        <input type="text" id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" class="form-control"
        placeholder="Masukkan NIDN dosen">
      </div>
      </div>
      <div class="form-group">
      <label for="prodi">Program Studi (opsional)</label>
      <div class="input-group">
        <i class="fas fa-graduation-cap input-icon"></i>
        <input type="text" id="prodi" name="prodi" value="{{ old('prodi', $dosen->prodi) }}" class="form-control"
        placeholder="Masukkan program studi dosen">
      </div>
      </div>
      <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Update Dosen
      </button>
      <a href="{{ route('admin.dosens.index') }}" class="btn btn-secondary">
        <i class="fas fa-times"></i> Batal
      </a>
      </div>
    </form>
    </div>
  </div>
@endsection