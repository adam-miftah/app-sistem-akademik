@extends('layouts.app')

@section('title', 'Input Nilai Mahasiswa - Admin')
@section('header_title', 'Input Nilai Mahasiswa')

@section('content')
  <style>
    /* Container Form */
    .form-container {
    background: var(--white-bg);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    }

    /* Judul Form */
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

    /* Notifikasi Error */
    .alert-error {
    background-color: #fce8e8;
    color: #cc0000;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #dc3545;
    }

    /* Grup Form */
    .form-group {
    margin-bottom: 1.5rem;
    }

    .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-weight: 500;
    }

    /* Input Control */
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

    /* Select Custom */
    select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3csvg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    }

    /* Grup dengan Ikon */
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

    /* Input Nilai */
    .nilai-input {
    max-width: 200px;
    }

    /* Tombol Aksi Form */
    .form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    }

    /* Responsive for Tablet/Mobile */
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

    .nilai-input {
      max-width: 100%;
    }
    }
  </style>

  <div class="content-area">
    <div class="form-container">
    <div class="card-header">
      <h4 class="card-title">Form Input Nilai Mahasiswa</h4>
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

    <form action="{{ route('admin.nilaiMahasiswas.store') }}" method="POST">
      @csrf
      <div class="form-group">
      <label for="mahasiswa_id">Mahasiswa</label>
      <div class="input-group">
        <i class="fas fa-user-graduate input-icon"></i>
        <select id="mahasiswa_id" name="mahasiswa_id" class="form-control" required>
        <option value="">Pilih Mahasiswa</option>
        @foreach ($mahasiswas as $mhs)
      <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
        {{ $mhs->nim }} - {{ $mhs->nama }}
      </option>
      @endforeach
        </select>
      </div>
      </div>
      <div class="form-group">
      <label for="mata_kuliah_id">Mata Kuliah</label>
      <div class="input-group">
        <i class="fas fa-book input-icon"></i>
        <select id="mata_kuliah_id" name="mata_kuliah_id" class="form-control" required>
        <option value="">Pilih Mata Kuliah</option>
        @foreach ($mataKuliahs as $mk)
      <option value="{{ $mk->id }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
        {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
      </option>
      @endforeach
        </select>
      </div>
      </div>
      <div class="form-group">
      <label for="dosen_id">Dosen Pemberi Nilai (Opsional)</label>
      <div class="input-group">
        <i class="fas fa-chalkboard-teacher input-icon"></i>
        <select id="dosen_id" name="dosen_id" class="form-control">
        <option value="">Pilih Dosen (Jika Ada)</option>
        @foreach ($dosens as $dosen)
      <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
        {{ $dosen->nama }}
      </option>
      @endforeach
        </select>
      </div>
      </div>

      {{-- Input field for 'kelas' --}}
      <div class="form-group">
      <label for="kelas">Kelas</label>
      <div class="input-group">
        <i class="fas fa-users input-icon"></i> {{-- Icon for class --}}
        <select id="kelas" name="kelas" class="form-control" required>
        <option value="">Pilih Kelas</option>
        @foreach ($kelasOptions as $kelas)
      <option value="{{ $kelas }}" {{ old('kelas') == $kelas ? 'selected' : '' }}>
        {{ $kelas }}
      </option>
      @endforeach
        </select>
      </div>
      </div>

      <hr class="my-4">
      <h5 class="mb-3 text-primary">Komponen Nilai</h5>

      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
        <label for="nilai_tugas">Nilai Tugas (0-100)</label>
        <div class="input-group">
          <i class="fas fa-tasks input-icon"></i>
          <input type="number" step="0.01" id="nilai_tugas" name="nilai_tugas" class="form-control"
          value="{{ old('nilai_tugas', $nilaiMahasiswa->nilai_tugas ?? '') }}" placeholder="Contoh: 80" min="0"
          max="100">
        </div>
        </div>
      </div>
      </div>

      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
        <label for="nilai_uts">Nilai UTS (0-100)</label>
        <div class="input-group">
          <i class="fas fa-file-alt input-icon"></i>
          <input type="number" step="0.01" id="nilai_uts" name="nilai_uts" class="form-control"
          value="{{ old('nilai_uts', $nilaiMahasiswa->nilai_uts ?? '') }}" placeholder="Contoh: 75" min="0"
          max="100">
        </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
        <label for="nilai_uas">Nilai UAS (0-100)</label>
        <div class="input-group">
          <i class="fas fa-scroll input-icon"></i>
          <input type="number" step="0.01" id="nilai_uas" name="nilai_uas" class="form-control"
          value="{{ old('nilai_uas', $nilaiMahasiswa->nilai_uas ?? '') }}" placeholder="Contoh: 88" min="0"
          max="100">
        </div>
        </div>
      </div>
      </div>
      {{-- Removed: Input field for 'nilai_angka' --}}
      {{-- <div class="form-group">
      <label for="nilai_angka">Nilai Angka Akhir (0-100, Opsional)</label>
      <div class="input-group">
        <i class="fas fa-percentage input-icon"></i>
        <input type="number" step="0.01" id="nilai_angka" name="nilai_angka" class="form-control nilai-input"
        value="{{ old('nilai_angka') }}" placeholder="Contoh: 85.50" min="0" max="100">
      </div>
      <small class="text-muted">Jika komponen nilai diisi, nilai angka ini akan dihitung otomatis saat ditampilkan.
        Jika tidak, Anda bisa mengisinya manual.</small>
      </div> --}}

      <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Nilai
      </button>
      <a href="{{ route('admin.nilaiMahasiswas.index') }}" class="btn btn-secondary">
        <i class="fas fa-times"></i> Batal
      </a>
      </div>
    </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const mahasiswaSelect = document.getElementById('mahasiswa_id');
    const mataKuliahSelect = document.getElementById('mata_kuliah_id'); // New: Get mata_kuliah select
    const kelasSelect = document.getElementById('kelas');
    const dosenSelect = document.getElementById('dosen_id'); // New: Get dosen select

    // Function to fetch and set kelas
    function updateKelas() {
      const mahasiswaId = mahasiswaSelect.value;
      console.log('Update Kelas: Mahasiswa dipilih, ID:', mahasiswaId);

      if (mahasiswaId) {
      const fetchUrl = `/admin/mahasiswas/${mahasiswaId}/kelas`;
      console.log('Update Kelas: Melakukan fetch ke:', fetchUrl);

      fetch(fetchUrl)
        .then(response => {
        console.log('Update Kelas: Response status:', response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
        })
        .then(data => {
        console.log('Update Kelas: Data diterima:', data);
        if (data.kelas) {
          kelasSelect.value = data.kelas;
          console.log('Update Kelas: Kelas diisi otomatis:', data.kelas);
        } else {
          kelasSelect.value = '';
          console.log('Update Kelas: Kelas tidak ditemukan atau kosong, reset field.');
        }
        // After updating kelas, attempt to update dosen
        updateDosen();
        })
        .catch(error => {
        console.error('Update Kelas: Error fetching student class:', error);
        kelasSelect.value = '';
        updateDosen(); // Still attempt to update dosen even on class fetch error
        });
      } else {
      kelasSelect.value = '';
      console.log('Update Kelas: Tidak ada mahasiswa dipilih, reset field kelas.');
      updateDosen(); // Reset dosen if no student is selected
      }
    }

    // Function to fetch and set dosen
    function updateDosen() {
      const mataKuliahId = mataKuliahSelect.value;
      const kelas = kelasSelect.value;
      console.log('Update Dosen: Mata Kuliah ID:', mataKuliahId, 'Kelas:', kelas);

      if (mataKuliahId && kelas) {
      const fetchUrl = `/admin/pengampu/dosen?mata_kuliah_id=${mataKuliahId}&kelas=${kelas}`;
      console.log('Update Dosen: Melakukan fetch ke:', fetchUrl);

      fetch(fetchUrl)
        .then(response => {
        console.log('Update Dosen: Response status:', response.status);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
        })
        .then(data => {
        console.log('Update Dosen: Data diterima:', data);
        if (data.dosen_id) {
          dosenSelect.value = data.dosen_id;
          console.log('Update Dosen: Dosen diisi otomatis:', data.dosen_id);
        } else {
          dosenSelect.value = ''; // Reset if dosen not found
          console.log('Update Dosen: Dosen tidak ditemukan untuk MK/Kelas ini, reset field.');
        }
        })
        .catch(error => {
        console.error('Update Dosen: Error fetching dosen:', error);
        dosenSelect.value = ''; // Reset on error
        });
      } else {
      dosenSelect.value = ''; // Reset if MK or Kelas is not selected
      console.log('Update Dosen: Mata Kuliah atau Kelas tidak lengkap, reset field dosen.');
      }
    }

    // Event listeners
    mahasiswaSelect.addEventListener('change', updateKelas); // When student changes, update class, then update dosen
    mataKuliahSelect.addEventListener('change', updateDosen); // When mata kuliah changes, update dosen
    kelasSelect.addEventListener('change', updateDosen); // When kelas changes, update dosen

    // Initial calls if old values are present (for form reloads/validation errors)
    // Ensure kelas is updated first, then dosen
    if (mahasiswaSelect.value) {
      console.log('Initial load: Mahasiswa terpilih, memicu updateKelas.');
      updateKelas();
    } else if (mataKuliahSelect.value && kelasSelect.value) { // If student is NOT selected, but MK and Class are
      console.log('Initial load: MK dan Kelas terpilih (tanpa mahasiswa), memicu updateDosen.');
      updateDosen();
    }
    });
  </script>
@endsection