@extends('layouts.app')

@section('content')
  <div class="container">
    <h2>Tambah Absen Dosen Baru</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    </div>
    @endif

    <form action="{{ route('admin.absenDosens.store') }}" method="POST">
    @csrf
    <div class="form-group mb-3">
      <label for="dosen_id">Dosen:</label>
      <select name="dosen_id" id="dosen_id" class="form-control" required>
      <option value="">-- Pilih Dosen --</option>
      @foreach ($dosens as $dosen)
      <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>
    @endforeach
      </select>
    </div>
    <div class="form-group mb-3">
      <label for="tanggal">Tanggal:</label>
      <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
    </div>
    <div class="form-group mb-3">
      <label for="waktu_masuk">Waktu Masuk:</label>
      <input type="time" name="waktu_masuk" id="waktu_masuk" class="form-control" value="{{ old('waktu_masuk') }}">
    </div>
    <div class="form-group mb-3">
      <label for="waktu_keluar">Waktu Keluar:</label>
      <input type="time" name="waktu_keluar" id="waktu_keluar" class="form-control" value="{{ old('waktu_keluar') }}">
    </div>
    <div class="form-group mb-3">
      <label for="status">Status:</label>
      <select name="status" id="status" class="form-control" required>
      <option value="">-- Pilih Status --</option>
      <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
      <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
      <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
      <option value="Alpha" {{ old('status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
      </select>
    </div>
    <div class="form-group mb-3">
      <label for="keterangan">Keterangan:</label>
      <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.absenDosens.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
@endsection