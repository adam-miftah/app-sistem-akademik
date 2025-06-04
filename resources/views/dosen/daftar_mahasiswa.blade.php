@extends('layouts.app')

@section('title', 'Daftar Mahasiswa Dosen')
@section('header_title', 'Daftar Mahasiswa')

@section('content')
  <style>
    /* Notification Styles */
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border-left: 4px solid #28a745;
      display: flex;
      align-items: center;
      gap: 0.75rem;
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

    /* Header Styles */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .page-title {
      margin: 0;
      color: var(--text-color);
      font-size: 1.5rem;
      font-weight: 600;
    }

    /* Filter Form */
    .filter-form {
      background-color: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }

    .form-select {
      border-radius: 6px;
      border: 1px solid #e2e8f0;
      padding: 0.5rem 1rem;
    }

    .btn-primary {
      background-color: #4361ee;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary:hover {
      background-color: #3a56d4;
    }

    /* Table Styles */
    .data-table-container {
      overflow-x: auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      margin-bottom: 2rem;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 900px;
    }

    .data-table thead {
      background-color: #4361ee;
      color: white;
    }

    .data-table th {
      padding: 1rem;
      text-align: left;
      font-weight: 500;
    }

    .data-table td {
      padding: 1rem;
      border-bottom: 1px solid #f0f0f0;
    }

    .data-table tr:last-child td {
      border-bottom: none;
    }

    .data-table tr:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }

    /* Attendance Badges */
    .attendance-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .badge-present {
      background-color: #d4edda;
      color: #155724;
    }

    .badge-absent {
      background-color: #f8d7da;
      color: #721c24;
    }

    /* Switch Styles */
    .form-switch .form-check-input {
      width: 3em;
      height: 1.5em;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 2rem;
      color: #6c757d;
    }

    .empty-state i {
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #adb5bd;
    }
    
    

    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .data-table td:nth-child(4),
      .data-table th:nth-child(4),
      .data-table td:nth-child(5),
      .data-table th:nth-child(5) {
        display: none;
      }
    }

    @media (max-width: 768px) {
      .filter-form .row {
        flex-direction: column;
        gap: 1rem;
      }

      .data-table th,
      .data-table td {
        padding: 0.75rem;
      }
    }

    @media (max-width: 576px) {
      .data-table td:nth-child(3),
      .data-table th:nth-child(3) {
        display: none;
      }
    }
  </style>

  <div class="content-area">
    @if(session('success'))
      <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
      </div>
    @endif

    <div class="page-header">
      <h3 class="page-title">Daftar Mahasiswa</h3>
    </div>

    {{-- Filter Form --}}
    <form action="{{ route('dosen.lihatDaftarMahasiswa') }}" method="GET" class="filter-form">
      <div class="row align-items-center g-3">
        <div class="col-md-4">
          <select name="kelas" id="kelas_filter" class="form-select">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasOptions as $option)
              <option value="{{ $option }}" {{ $selectedKelas == $option ? 'selected' : '' }}>
                {{ $option }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <select name="mata_kuliah_id" id="mata_kuliah_filter" class="form-select">
            <option value="">-- Semua Mata Kuliah --</option>
            @foreach($mataKuliahsDiajar as $mk)
              <option value="{{ $mk->id }}" {{ $selectedMataKuliah == $mk->id ? 'selected' : '' }}>
                {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-auto">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter me-1"></i> Filter
          </button>
        </div>
      </div>
    </form>

    @if($mahasiswas->isEmpty())
      <div class="data-table-container">
        <div class="empty-state">
          <i class="fas fa-user-graduate"></i>
          <h3 class="mt-3">Tidak ada mahasiswa yang terkait</h3>
          <p>Sesuaikan filter atau hubungi administrasi jika ini sebuah kesalahan</p>
        </div>
      </div>
    @else
      <div class="data-table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Mata Kuliah</th>
              <th>Kelas</th>
              <th>Jam</th>
              <th>Ruangan</th>
              <th>Presensi Hari Ini</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mahasiswas as $mhs)
              @php
                $isHadirToday = $presensiStatusHariIni->has($mhs->id) && $presensiStatusHariIni->get($mhs->id) === 'Hadir';

                $displayedMataKuliah = '';
                $displayedJam = '-';
                $displayedRuangan = '-';

                if ($selectedMataKuliah) {
                  $filteredMk = $mataKuliahsDiajar->firstWhere('id', $selectedMataKuliah);
                  $displayedMataKuliah = $filteredMk ? $filteredMk->nama_mk . ' (' . $filteredMk->kode_mk . ')' : '-';

                  if ($pengampuDetailForDisplay && $pengampuDetailForDisplay->mata_kuliah_id == $selectedMataKuliah && $pengampuDetailForDisplay->kelas == ($mhs->kelas ?? '')) {
                    $displayedJam = \Carbon\Carbon::parse($pengampuDetailForDisplay->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($pengampuDetailForDisplay->jam_selesai)->format('H:i');
                    $displayedRuangan = $pengampuDetailForDisplay->ruangan;
                  }
                } else {
                  $displayedMataKuliah = 'Pilih Mata Kuliah terlebih dahulu';
                }
              @endphp
              <tr id="mahasiswa-row-{{ $mhs->id }}">
                <td>
                  <div>{{ $mhs->nama }}</div>
                </td>
                <td>{{ $displayedMataKuliah }}</td>
                <td>{{ $mhs->kelas ?? '-' }}</td>
                <td>{{ $displayedJam }}</td>
                <td>{{ $displayedRuangan }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="form-check form-switch">
                      <input class="form-check-input presensi-toggle" type="checkbox" role="switch"
                        id="presensiToggle{{ $mhs->id }}" data-mahasiswa-id="{{ $mhs->id }}" {{ $isHadirToday ? 'checked' : '' }}>
                    </div>
                    <span id="presensi-status-text-{{ $mhs->id }}"
                      class="attendance-badge {{ $isHadirToday ? 'badge-present' : 'badge-absent' }}">
                      {{ $isHadirToday ? 'Hadir' : 'Tidak Hadir' }}
                    </span>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

 @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const presensiToggles = document.querySelectorAll('.presensi-toggle');
    const contentArea = document.querySelector('.content-area'); // Container untuk alert dinamis

    // Fungsi untuk menampilkan pesan alert dinamis
    function showAlert(type, message) {
        // Hapus alert lama jika ada
        const existingAlert = contentArea.querySelector('.dynamic-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        const alertElement = document.createElement('div');
        alertElement.className = `dynamic-alert alert-${type}`; // e.g., alert-success or alert-danger
        alertElement.style.display = 'flex';
        alertElement.style.alignItems = 'center';
        alertElement.style.gap = '0.75rem';
        // Anda bisa menambahkan style lain yang diperlukan sesuai class .alert-success / .alert-danger Anda
        // Jika class CSS Anda sudah mengatur padding, border-radius, margin, dll. maka tidak perlu style inline lagi.
        // Pastikan class alert-success dan alert-danger punya style yang sesuai.
        // Untuk styling minimal:
        alertElement.style.padding = '1rem';
        alertElement.style.borderRadius = '8px';
        alertElement.style.marginBottom = '1.5rem';


        if (type === 'success') {
            alertElement.style.backgroundColor = '#d4edda';
            alertElement.style.color = '#155724';
            alertElement.style.borderLeft = '4px solid #28a745';
            alertElement.innerHTML = `<i class="fas fa-check-circle"></i> <span>${message}</span>`;
        } else { // 'danger'
            alertElement.style.backgroundColor = '#f8d7da';
            alertElement.style.color = '#721c24';
            alertElement.style.borderLeft = '4px solid #dc3545';
            alertElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> <span>${message}</span>`;
        }

        // Sisipkan di bagian atas content-area
        if (contentArea) {
            contentArea.insertBefore(alertElement, contentArea.firstChild);
        } else {
            // Fallback jika .content-area tidak ditemukan
            document.body.insertBefore(alertElement, document.body.firstChild);
        }

        // Efek fade out dan remove setelah 5 detik
        setTimeout(() => {
            alertElement.style.transition = 'opacity 0.5s ease';
            alertElement.style.opacity = '0';
            setTimeout(() => alertElement.remove(), 500); // Hapus dari DOM setelah transisi selesai
        }, 5000);
    }

    presensiToggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const mahasiswaId = this.dataset.mahasiswaId;
            const isPresentNewState = this.checked; // Keadaan BARU dari checkbox
            const statusTextSpan = document.getElementById(`presensi-status-text-${mahasiswaId}`);
            const row = document.getElementById(`mahasiswa-row-${mahasiswaId}`);

            // Simpan state SEBELUMNYA untuk revert jika error
            const previousCheckedState = !isPresentNewState;

            // Animasi loading
            if(row) row.style.opacity = '0.7';
            if(statusTextSpan) {
                statusTextSpan.textContent = 'Memproses...';
                statusTextSpan.classList.remove('badge-present', 'badge-absent'); // Hapus class lama segera
            }

            fetch("{{ route('dosen.togglePresensiHarian') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    mahasiswa_id: mahasiswaId,
                    is_present: isPresentNewState
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Jika status HTTP bukan 2xx, coba parse error JSON jika ada
                    return response.json().then(errData => {
                        throw { status: response.status, data: errData };
                    });
                }
                return response.json();
            })
            .then(data => {
                if(row) row.style.opacity = '1';
                if (data.success) {
                    if(statusTextSpan) {
                        statusTextSpan.textContent = data.status_kehadiran; // Dari response controller ('Hadir' / 'Tidak Hadir')
                        statusTextSpan.classList.remove('badge-present', 'badge-absent'); // Pastikan bersih
                        statusTextSpan.classList.add(data.status_kehadiran === 'Hadir' ? 'badge-present' : 'badge-absent');
                    }
                    showAlert('success', data.message);
                } else {
                    // Gagal dari sisi server (data.success === false)
                    this.checked = previousCheckedState; // Kembalikan checkbox
                    if(statusTextSpan) {
                        statusTextSpan.classList.remove('badge-present', 'badge-absent');
                        statusTextSpan.textContent = previousCheckedState ? 'Hadir' : 'Tidak Hadir';
                        statusTextSpan.classList.add(previousCheckedState ? 'badge-present' : 'badge-absent');
                    }
                    showAlert('danger', data.message || 'Gagal memperbarui presensi.');
                }
            })
            .catch(error => {
                if(row) row.style.opacity = '1';
                this.checked = previousCheckedState; // Kembalikan checkbox pada error network/parsing
                 if(statusTextSpan) {
                    statusTextSpan.classList.remove('badge-present', 'badge-absent');
                    statusTextSpan.textContent = previousCheckedState ? 'Hadir' : 'Tidak Hadir';
                    statusTextSpan.classList.add(previousCheckedState ? 'badge-present' : 'badge-absent');
                }

                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                if (error.data && error.data.message) {
                    errorMessage = error.data.message;
                    if(error.data.errors) { // Untuk error validasi
                        const firstErrorKey = Object.keys(error.data.errors)[0];
                        errorMessage += ` (${error.data.errors[firstErrorKey][0]})`;
                    }
                } else if (error.message) {
                    errorMessage = error.message;
                }
                showAlert('danger', errorMessage);
            });
        });
    });
});
</script>
@endpush
@endsection