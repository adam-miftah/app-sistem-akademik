@extends('layouts.app')

@section('title', 'Edit Penugasan Dosen - Admin')
@section('header_title', 'Edit Penugasan Dosen')

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
        }
    </style>

    <div class="content-area">
        <div class="form-container">
            <div class="card-header">
                <h4 class="card-title">Form Edit Penugasan Dosen</h4>
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

            <form action="{{ route('admin.pengampuMataKuliah.update', $pengampuMataKuliah->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="dosen_id">Dosen Pengampu</label>
                    <div class="input-group">
                        <i class="fas fa-chalkboard-teacher input-icon"></i>
                        <select id="dosen_id" name="dosen_id" class="form-control" required>
                            <option value="">Pilih Dosen</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('dosen_id', $pengampuMataKuliah->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
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
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->nama_mk }}
                                </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="hari">Hari</label>
                    <select id="hari" name="hari" class="form-control" required>
                        <option value="">Pilih Hari</option>
                        @foreach ($haris as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $pengampuMataKuliah->hari) == $hari ? 'selected' : '' }}>
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="time-picker">
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <div class="input-group">
                            <i class="fas fa-clock input-icon"></i>
                            <input type="time" id="jam_mulai" name="jam_mulai" class="form-control"
                                value="{{ old('jam_mulai', \Carbon\Carbon::parse($pengampuMataKuliah->jam_mulai)->format('H:i')) }}"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <div class="input-group">
                            <i class="fas fa-clock input-icon"></i>
                            <input type="time" id="jam_selesai" name="jam_selesai" class="form-control"
                                value="{{ old('jam_selesai', \Carbon\Carbon::parse($pengampuMataKuliah->jam_selesai)->format('H:i')) }}"
                                required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ruangan">Ruangan</label>
                    <div class="input-group">
                        <i class="fas fa-door-open input-icon"></i>
                        <input type="text" id="ruangan" name="ruangan"
                            value="{{ old('ruangan', $pengampuMataKuliah->ruangan) }}" class="form-control"
                            placeholder="Contoh: A101, Lab Komputer 1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <div class="input-group">
                        <i class="fas fa-users input-icon"></i>
                        <select id="kelas" name="kelas" class="form-control">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelasOptions as $option)
                                <option value="{{ $option }}" {{ old('kelas', $pengampuMataKuliah->kelas) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Penugasan
                    </button>
                    <a href="{{ route('admin.pengampuMataKuliah.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection