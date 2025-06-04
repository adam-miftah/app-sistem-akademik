@extends('layouts.app')
@section('title', 'Edit Presensi Mahasiswa')
@section('header_title', 'Edit Presensi Mahasiswa')
@section('content')
  <div class="container-fluid">
    <div class="card">
    <div class="card-header">
      <h5>Edit Presensi untuk: {{ $presensiMahasiswa->mahasiswa->nama ?? 'N/A' }}</h5>
      <p>Mata Kuliah: {{ $presensiMahasiswa->pengampuMataKuliah->mataKuliah->nama_mk ?? 'N/A' }} <br>
      Tanggal: {{ \Carbon\Carbon::parse($presensiMahasiswa->tanggal)->isoFormat('D MMMM YYYY') }}
      </p>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.kelolaPresensi.update', $presensiMahasiswa->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="status_kehadiran" class="form-label">Status Kehadiran</label>
        <select class="form-select @error('status_kehadiran') is-invalid @enderror" id="status_kehadiran"
        name="status_kehadiran" required>
        <option value="Hadir" {{ $presensiMahasiswa->status_kehadiran == 'Hadir' ? 'selected' : '' }}>Hadir</option>
        <option value="Tidak Hadir" {{ $presensiMahasiswa->status_kehadiran == 'Tidak Hadir' ||
    $presensiMahasiswa->status_kehadiran == 'Alpha' ? 'selected' : '' }}>Tidak Hadir</option>
        {{-- Opsi Alpha, Izin, Sakit dihapus dari pilihan edit --}}
        </select>
        @error('status_kehadiran')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="{{ route('admin.kelolaPresensi.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
    </div>
  </div>
@endsection