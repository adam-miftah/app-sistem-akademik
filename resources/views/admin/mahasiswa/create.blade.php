@extends('layouts.app')

@section('title', 'Tambah Mahasiswa Baru - Admin')
@section('header_title', 'Tambah Mahasiswa Baru')

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

        /* Textarea */
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
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

        /* Tombol Aksi Form */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Responsif untuk Tablet/Mobile */
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
                <h4 class="card-title">Form Tambah Mahasiswa</h4>
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

            <form action="{{ route('admin.mahasiswas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <div class="input-group">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="nim" name="nim" value="{{ old('nim') }}" class="form-control"
                            placeholder="Contoh: 2023001" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Mahasiswa</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="form-control"
                            placeholder="Contoh: Budi Santoso" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jurusan">Jurusan</label>
                    <div class="input-group">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}" class="form-control"
                            placeholder="Contoh: Teknik Informatika" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="angkatan">Angkatan (Tahun Masuk)</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" class="form-control"
                            placeholder="Contoh: 2023" required>
                    </div>
                </div>

                {{-- NEW: Input fields for User account (email, password) --}}
                <hr class="my-4">
                <h5 class="mb-3 text-primary">Informasi Akun Login</h5>

                <div class="form-group">
                    <label for="email">Email Login</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Contoh: budi.santoso@example.com" required>
                    </div>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password" required>
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
                {{-- END NEW --}}

                {{-- NEW: Additional personal details (moved below password for better flow) --}}
                <hr class="my-4">
                <h5 class="mb-3 text-primary">Detail Pribadi dan Akademik</h5>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir (Opsional)</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-day input-icon"></i>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="program_studi">Program Studi</label>
                    <div class="input-group">
                        <i class="fas fa-book-open input-icon"></i>
                        <select id="program_studi" name="program_studi" class="form-control">
                            <option value="">Pilih Program Studi</option>
                            @foreach ($programStudiOptions as $option)
                                <option value="{{ $option }}" {{ old('program_studi') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="prog_perkuliahan">Program Perkuliahan</label>
                    <div class="input-group">
                        <i class="fas fa-clock input-icon"></i>
                        <select id="prog_perkuliahan" name="prog_perkuliahan" class="form-control">
                            <option value="">Pilih Program Perkuliahan</option>
                            @foreach ($progPerkuliahanOptions as $option)
                                <option value="{{ $option }}" {{ old('prog_perkuliahan') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <div class="input-group">
                        <i class="fas fa-users input-icon"></i>
                        <select id="kelas" name="kelas" class="form-control">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelasOptions as $option)
                                <option value="{{ $option }}" {{ old('kelas') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status_mahasiswa">Status Mahasiswa</label>
                    <div class="input-group">
                        <i class="fas fa-info-circle input-icon"></i>
                        <select id="status_mahasiswa" name="status_mahasiswa" class="form-control" required>
                            <option value="">Pilih Status</option>
                            @foreach ($statusMahasiswaOptions as $option)
                                <option value="{{ $option }}" {{ old('status_mahasiswa') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- END NEW --}}

                <div class="form-group">
                    <label for="telepon">Telepon (opsional)</label>
                    <div class="input-group">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}" class="form-control"
                            placeholder="Contoh: 081234567890">
                    </div>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat (opsional)</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3"
                        placeholder="Alamat lengkap mahasiswa">{{ old('alamat') }}</textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Mahasiswa
                    </button>
                    <a href="{{ route('admin.mahasiswas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection