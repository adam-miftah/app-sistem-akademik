@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Admin')

@section('content')
  <style>
    .dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
    }

    .stat-card {
    background: var(--white-bg);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: var(--shadow-light);
    transition: var(--transition);
    border-left: 4px solid var(--primary-color);
    display: flex;
    flex-direction: column;
    }

    .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-medium);
    }

    .stat-card h3 {
    font-size: 1rem;
    color: var(--text-light);
    margin-bottom: 0.5rem;
    font-weight: 500;
    }

    .stat-card p {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary-color);
    margin: 0;
    }

    .stat-card .card-icon {
    align-self: flex-end;
    font-size: 1.5rem;
    color: rgba(67, 97, 238, 0.2);
    margin-top: 0.5rem;
    }

    .section-title {
    font-size: 1.25rem;
    color: var(--text-color);
    margin: 2rem 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
    }

    .data-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white-bg);
    box-shadow: var(--shadow-light);
    border-radius: 12px;
    overflow: hidden;
    }

    .data-table thead {
    background-color: var(--primary-color);
    color: white;
    }

    .data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 500;
    }

    .data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    }

    .data-table tr:last-child td {
    border-bottom: none;
    }

    .data-table tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    }

    .announcement-form {
    background: var(--white-bg);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    margin-top: 2rem;
    }

    .announcement-form h3 {
    margin-top: 0;
    margin-bottom: 1.5rem;
    color: var(--text-color);
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

    .form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    resize: vertical;
    min-height: 120px;
    transition: var(--transition);
    }

    .form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .btn-primary {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    }

    .btn-primary:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
    }

    @media (max-width: 768px) {
    .dashboard-container {
      grid-template-columns: 1fr 1fr;
    }

    .stat-card p {
      font-size: 1.5rem;
    }
    }

    @media (max-width: 480px) {
    .dashboard-container {
      grid-template-columns: 1fr;
    }

    .data-table th,
    .data-table td {
      padding: 0.75rem;
    }
    }
  </style>

  <div class="dashboard-container">
    <div class="stat-card">
    <h3>Total Pengguna</h3>
    <p>{{ $totalUsers ?? '0' }}</p>
    <i class="fas fa-users card-icon"></i>
    </div>
    <div class="stat-card">
    <h3>Mata Kuliah</h3>
    <p>{{ $totalMataKuliah ?? '0' }}</p>
    <i class="fas fa-book card-icon"></i>
    </div>
    <div class="stat-card">
    <h3>Dosen</h3>
    <p>{{ $totalDosen ?? '0' }}</p>
    <i class="fas fa-chalkboard-teacher card-icon"></i>
    </div>
    <div class="stat-card">
    <h3>Mahasiswa</h3>
    <p>{{ $totalMahasiswa ?? '0' }}</p>
    <i class="fas fa-user-graduate card-icon"></i>
    </div>
  </div>

  <h2 class="section-title">Ringkasan Data Terbaru</h2>
  <div class="table-responsive">
    <table class="data-table">
    <thead>
      <tr>
      <th>Jenis Data</th>
      <th>Jumlah</th>
      <th>Terakhir Diperbarui</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($dataSummary as $jenis => $data)
      <tr>
      <td>{{ $jenis }}</td>
      <td>{{ $data['count'] ?? '0' }}</td>
      <td>
      @if ($data['last_updated'])
      {{ \Carbon\Carbon::parse($data['last_updated'])->format('d M Y H:i') }}
      @else
      Belum ada data
      @endif
      </td>
      </tr>
    @endforeach
    </tbody>
    </table>
  </div>

  <div class="announcement-form">
    <h3>Pengumuman Cepat</h3>
    <form>
    <div class="form-group">
      <label for="pengumuman">Tulis Pengumuman Baru</label>
      <textarea id="pengumuman" rows="4" placeholder="Ketik pengumuman di sini..."></textarea>
    </div>
    <button type="submit" class="btn-primary">
      <i class="fas fa-paper-plane"></i>
      Posting Pengumuman
    </button>
    </form>
  </div>
@endsection