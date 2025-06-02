@extends('layouts.app')

@section('title', 'Edit Nilai Mahasiswa - Admin')
@section('header_title', 'Edit Nilai Mahasiswa')

@section('content')
    <style>
        /* Reuse styles from create form */
        .form-container {
            background: var(--white-bg);
            padding: 2rem;
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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
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

        .nilai-input {
            max-width: 200px;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
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

            .nilai-input {
                max-width: 100%;
            }
        }
    </style>

    <div class="content-area">
        <div class="form-container">
            <div class="card-header">
                <h4 class="card-title">Form Edit Nilai Mahasiswa: {{ $nilaiMahasiswa->mahasiswa->nama }} -
                    {{ $nilaiMahasiswa->mataKuliah->nama_mk }}
                </h4>
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

            <form action="{{ route('admin.nilaiMahasiswas.update', $nilaiMahasiswa->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="mahasiswa_id">Mahasiswa</label>
                    <div class="input-group">
                        <i class="fas fa-user-graduate input-icon"></i>
                        <select id="mahasiswa_id" name="mahasiswa_id" class="form-control" required>
                            <option value="">Pilih Mahasiswa</option>
                            @foreach ($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $nilaiMahasiswa->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
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
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $nilaiMahasiswa->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
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
                                <option value="{{ $dosen->id }}" {{ old('dosen_id', $nilaiMahasiswa->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                {{-- NEW: Input field for 'kelas' --}}
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <div class="input-group">
                        <i class="fas fa-users input-icon"></i> {{-- Icon for class --}}
                        <select id="kelas" name="kelas" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelasOptions as $kelas)
                                <option value="{{ $kelas }}" {{ old('kelas', $nilaiMahasiswa->kelas) == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- NEW: Input fields for grade components --}}
                <hr class="my-4">
                <h5 class="mb-3 text-primary">Komponen Nilai</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kehadiran">Nilai Kehadiran (0-100)</label>
                            <div class="input-group">
                                <i class="fas fa-user-check input-icon"></i>
                                <input type="number" step="0.01" id="kehadiran" name="kehadiran" class="form-control"
                                    value="{{ old('kehadiran', $nilaiMahasiswa->kehadiran) }}" placeholder="Contoh: 90"
                                    min="0" max="100">
                            </div>
                            <small class="text-muted">Isi persentase kehadiran atau nilai dari komponen kehadiran.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_tugas">Nilai Tugas (0-100)</label>
                            <div class="input-group">
                                <i class="fas fa-tasks input-icon"></i>
                                <input type="number" step="0.01" id="nilai_tugas" name="nilai_tugas" class="form-control"
                                    value="{{ old('nilai_tugas', $nilaiMahasiswa->nilai_tugas) }}" placeholder="Contoh: 80"
                                    min="0" max="100">
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
                                    value="{{ old('nilai_uts', $nilaiMahasiswa->nilai_uts) }}" placeholder="Contoh: 75"
                                    min="0" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_uas">Nilai UAS (0-100)</label>
                            <div class="input-group">
                                <i class="fas fa-scroll input-icon"></i>
                                <input type="number" step="0.01" id="nilai_uas" name="nilai_uas" class="form-control"
                                    value="{{ old('nilai_uas', $nilaiMahasiswa->nilai_uas) }}" placeholder="Contoh: 88"
                                    min="0" max="100">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Removed: Input field for 'nilai_angka' --}}
                {{-- <div class="form-group">
                    <label for="nilai_angka">Nilai Angka Akhir (0-100, Opsional)</label>
                    <div class="input-group">
                        <i class="fas fa-percentage input-icon"></i>
                        <input type="number" step="0.01" id="nilai_angka" name="nilai_angka"
                            class="form-control nilai-input" value="{{ old('nilai_angka', $nilaiMahasiswa->nilai_angka) }}"
                            placeholder="Contoh: 85.50" min="0" max="100">
                    </div>
                    <small class="text-muted">Jika komponen nilai diisi, nilai angka ini akan dihitung otomatis saat
                        ditampilkan.
                        Jika tidak, Anda bisa mengisinya manual.</small>
                </div> --}}

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Nilai
                    </button>
                    <a href="{{ route('admin.nilaiMahasiswas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
