{{-- resources/views/dosen/settings/change_password.blade.php --}}
@extends('layouts.app')

@section('title', 'Ubah Password Dosen')
@section('header_title', 'Ubah Password')

@section('content')
  <div class="content-area">
    @if (session('success'))
    <div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    </div>
    @endif

    <div class="page-header">
    <h3 class="page-title">Ubah Password Akun Anda</h3>
    </div>

    <div class="form-container"
    style="background: var(--white-bg); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow-light);">
    <form action="{{ route('dosen.change_password') }}" method="POST">
      @csrf
      <div class="mb-3">
      <label for="new_password" class="form-label">Password Baru</label>
      <input type="password" class="form-control" id="new_password" name="new_password" required>
      </div>
      <div class="mb-3">
      <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
      <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation"
        required>
      </div>
      <button type="submit" class="btn btn-primary">Ubah Password</button>
    </form>
    </div>
  </div>
@endsection

{{-- Anda mungkin perlu menambahkan gaya CSS untuk .form-control, .form-label, .mb-3, dll. di layouts/app.blade.php atau
di sini --}}
<style>
  .form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--text-color);
    background-color: var(--white-bg);
    box-sizing: border-box;
    margin-bottom: 1rem;
  }

  .form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-color);
  }

  .mb-3 {
    margin-bottom: 1.5rem;
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

  .alert-danger ul {
    margin: 0;
    padding-left: 1.25rem;
  }
</style>