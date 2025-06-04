@extends('layouts.app')

@section('title', 'Edit Data Mahasiswa - Admin')
@section('header_title', 'Edit Data Mahasiswa')

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

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
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
                <h4 class="card-title">Form Edit Mahasiswa: {{ $mahasiswa->nama }}</h4>
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

            <form action="{{ route('admin.mahasiswas.update', $mahasiswa->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <div class="input-group">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                            class="form-control" placeholder="Contoh: 2023001" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Mahasiswa</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}"
                            class="form-control" placeholder="Contoh: Budi Santoso" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jurusan">Jurusan</label>
                    <div class="input-group">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan', $mahasiswa->jurusan) }}"
                            class="form-control" placeholder="Contoh: Teknik Informatika" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="angkatan">Angkatan (Tahun Masuk)</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                            class="form-control" placeholder="Contoh: 2023" required>
                    </div>
                </div>

                {{-- NEW: Additional personal details --}}
                <hr class="my-4">
                <h5 class="mb-3 text-primary">Detail Pribadi dan Akademik</h5>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir (Opsional)</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-day input-icon"></i>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir) }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="program_studi">Program Studi</label>
                    <div class="input-group">
                        <i class="fas fa-book-open input-icon"></i>
                        <select id="program_studi" name="program_studi" class="form-control">
                            <option value="">Pilih Program Studi</option>
                            @foreach ($programStudiOptions as $option)
                                <option value="{{ $option }}" {{ old('program_studi', $mahasiswa->program_studi) == $option ? 'selected' : '' }}>
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
                                <option value="{{ $option }}" {{ old('prog_perkuliahan', $mahasiswa->prog_perkuliahan) == $option ? 'selected' : '' }}>
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
                                <option value="{{ $option }}" {{ old('kelas', $mahasiswa->kelas) == $option ? 'selected' : '' }}>
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
                                <option value="{{ $option }}" {{ old('status_mahasiswa', $mahasiswa->status_mahasiswa) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->email) }}"
                            class="form-control" placeholder="Contoh: budi.santoso@example.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon (opsional)</label>
                    <div class="input-group">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $mahasiswa->telepon) }}"
                            class="form-control" placeholder="Contoh: 081234567890">
                    </div>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat (opsional)</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="3"
                        placeholder="Alamat lengkap mahasiswa">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                </div>



                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Mahasiswa
                    </button>
                    <a href="{{ route('admin.mahasiswas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection