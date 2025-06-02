@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah Baru - Admin')
@section('header_title', 'Tambah Mata Kuliah Baru')

@section('content')
    <style>
        /* Form Container */
        .form-container {
            background: var(--white-bg);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
        }

        /* Form Title */
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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
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
                <h4 class="card-title">Form Tambah Mata Kuliah</h4>
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

            <form action="{{ route('admin.mataKuliahs.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="kode_mk">Kode Mata Kuliah</label>
                    <div class="input-group">
                        <i class="fas fa-code input-icon"></i>
                        <input type="text" id="kode_mk" name="kode_mk" value="{{ old('kode_mk') }}" class="form-control"
                            placeholder="Contoh: IF401" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nama_mk">Nama Mata Kuliah</label>
                    <div class="input-group">
                        <i class="fas fa-book input-icon"></i>
                        <input type="text" id="nama_mk" name="nama_mk" value="{{ old('nama_mk') }}" class="form-control"
                            placeholder="Contoh: Pemrograman Web" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sks">SKS</label>
                    <div class="input-group">
                        <i class="fas fa-hourglass-half input-icon"></i>
                        <input type="number" id="sks" name="sks" value="{{ old('sks') }}" class="form-control" min="1"
                            max="6" required>
                    </div>
                </div>
                {{-- PERUBAHAN DI SINI: DARI SEMESTER MENJADI KELAS --}}
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <div class="input-group">
                        <i class="fas fa-chalkboard input-icon"></i>
                        <select id="kelas" name="kelas" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            <option value="Reguler" {{ old('kelas') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Khusus" {{ old('kelas') == 'Khusus' ? 'selected' : '' }}>Khusus</option>
                            <option value="Pagi" {{ old('kelas') == 'Pagi' ? 'selected' : '' }}>Pagi</option>
                            <option value="Malam" {{ old('kelas') == 'Malam' ? 'selected' : '' }}>Malam</option>
                            {{-- Tambahkan opsi kelas lain sesuai kebutuhan Anda --}}
                        </select>
                    </div>
                    @error('kelas')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- AKHIR PERUBAHAN --}}
                <div class="form-group">
                    <label for="deskripsi">Deskripsi (opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"
                        placeholder="Deskripsi singkat mata kuliah">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Mata Kuliah
                    </button>
                    <a href="{{ route('admin.mataKuliahs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection