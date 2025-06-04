{{-- File: resources/views/mahasiswa/presensi.blade.php --}}
@extends('layouts.app')

@section('title', 'Presensi Mahasiswa')
@section('header_title', 'Presensi Kuliah')

@section('content')
    <style>
    /* Notifikasi Global (jika ada dari redirect atau alert AJAX) */
    .alert-success {
      background-color: #d1e7dd;
      color: #0f5132;
      padding: 1rem;
      border-radius: 0.375rem; /* rounded-md */
      margin-bottom: 1.5rem; /* mb-6 */
      border-left: 4px solid #0f5132; /* Warna border lebih gelap */
      display: flex;
      align-items: center;
      gap: 0.75rem; /* space-x-3 */
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #842029;
      padding: 1rem;
      border-radius: 0.375rem;
      margin-bottom: 1.5rem;
      border-left: 4px solid #842029;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    /* Header Halaman */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem; /* mb-6 */
      flex-wrap: wrap;
      gap: 1rem; /* space-x-4 atau space-y-4 tergantung flex-direction */
    }

    .page-title {
      margin: 0;
      /* color: var(--text-color); Sesuaikan dengan variabel CSS Anda */
      color: #333; /* Contoh warna default */
      font-size: 1.5rem; /* text-2xl */
      font-weight: 600; /* font-semibold */
    }

    /* Kontainer Tabel Data */
    .data-table-container {
      overflow-x: auto;
      background: white;
      border-radius: 0.75rem; /* rounded-xl */
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); /* shadow-lg */
      margin-bottom: 2rem; /* mb-8 */
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 800px; /* Untuk memastikan tabel tidak terlalu sempit di layar kecil sebelum scroll */
    }

    .data-table thead {
      background-color: #4361ee; /* Warna tema Anda */
      color: white;
    }

    .data-table th,
    .data-table td {
      padding: 1rem; /* p-4 */
      text-align: left;
      border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
    }
    .data-table th {
      font-weight: 500; /* font-medium */
    }

    .data-table tr:last-child td {
      border-bottom: none;
    }

    .data-table tr:hover {
      background-color: rgba(67, 97, 238, 0.05); /* Efek hover lembut */
    }

    /* Badge untuk Waktu dan Ruangan */
    .time-badge, .ruangan-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem; /* space-x-1.5 */
      padding: 0.375rem 0.75rem; /* py-1.5 px-3 */
      border-radius: 9999px; /* rounded-full */
      font-weight: 500; /* font-medium */
      font-size: 0.875rem; /* text-sm */
    }
    .time-badge {
      background-color: #ecfdf5; /* bg-emerald-50 */
      color: #059669; /* text-emerald-700 */
    }
    .ruangan-badge {
      background-color: #eff6ff; /* bg-blue-50 */
      color: #1d4ed8; /* text-blue-700 */
    }

    /* Tampilan Kosong (Empty State) */
    .empty-state {
      text-align: center;
      padding: 2rem; /* p-8 */
      color: #6b7280; /* text-gray-500 */
    }
    .empty-state i {
      font-size: 2.5rem; /* text-4xl */
      margin-bottom: 1rem; /* mb-4 */
      color: #9ca3af; /* text-gray-400 */
    }
    .empty-state h3 {
      font-size: 1.25rem; /* text-xl */
      font-weight: 600;
      color: #374151; /* text-gray-700 */
    }

    /* --- STYLING BARU UNTUK TOGGLE DAN BADGE PRESENSI MAHASISWA --- */
    .presensi-controls-mahasiswa { /* Class unik untuk mahasiswa */
      display: flex;
      align-items: center;
      gap: 0.75rem; /* Jarak antara toggle dan badge */
      justify-content: center; /* Agar di tengah jika kolomnya text-center */
    }

    /* Menggunakan style toggle switch dari halaman dosen */
    .form-switch .form-check-input { /* Ini adalah class Bootstrap standar, jadi style akan berlaku global jika tidak di-scope */
      width: 3.5em; 
      height: 1.75em;
      cursor: pointer;
      background-color: #e74c3c; /* Warna default (Tidak Hadir / Alpha) */
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
      border-color: #c0392b; 
      transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, background-position 0.2s ease-in-out;
      box-shadow: none; 
    }
    .form-switch .form-check-input:focus {
      border-color: #c0392b; 
      box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25); 
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }
    .form-switch .form-check-input:checked {
      background-color: #2ecc71; /* Warna saat checked (Hadir) */
      border-color: #27ae60;
    }
    .form-switch .form-check-input:checked:focus {
      border-color: #27ae60; 
      box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25); 
    }

    /* Menggunakan style attendance badge dari halaman dosen */
    .attendance-badge { /* Ini adalah class yang sama, jadi style akan berlaku global */
      display: inline-block;
      padding: 5px 12px; 
      border-radius: 15px; 
      font-size: 0.8rem; 
      font-weight: 600;  
      letter-spacing: 0.5px;
      text-transform: uppercase; 
      min-width: 100px; 
      text-align: center;
      transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
    .badge-present { /* Untuk status Hadir */
      background-color: #2ecc71; 
      color: white;
    }
    .badge-absent { /* Untuk status Tidak Hadir / Alpha */
      background-color: #e74c3c; 
      color: white;
    }
    .badge-processing { /* Badge saat loading */
      background-color: #bdc3c7; /* Abu-abu netral */
      color: #2c3e50;
      cursor: default;
    }
     .presensi-info-mahasiswa { /* Class unik untuk info presensi mahasiswa */
      font-size: 0.75rem; /* text-xs */
      color: #6b7280; /* text-gray-500 */
      display: block; 
      margin-top: 0.25rem; /* mt-1 */
    }
    /* --- AKHIR STYLING BARU --- */

    /* Responsif (sudah ada sebelumnya, pastikan tidak konflik) */
    @media (max-width: 992px) { 
      .data-table td:nth-child(3), .data-table th:nth-child(3) { display: none; }
    }
    @media (max-width: 768px) { 
      .page-header { flex-direction: column; align-items: flex-start; }
      .data-table th, .data-table td { padding: 0.75rem; }
      .presensi-controls-mahasiswa { flex-direction: column; gap: 0.5rem; } 
      .data-table td:nth-child(4), .data-table th:nth-child(4) { display: none; }
    }
    @media (max-width: 576px) { 
      .time-badge i { display: none; }
      .time-badge { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
      .page-title { font-size: 1.25rem; }
    }
    </style>

    <div class="content-area">
    <div id="global-alert-container-mahasiswa"></div> {{-- Container alert unik --}}

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
    <h3 class="page-title">Daftar Jadwal Hari Ini ({{ $hariIni }})</h3>
    </div>

    @if($jadwalHariIni->isEmpty())
    <div class="data-table-container">
    <div class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <h3 class="mt-3">Tidak ada jadwal hari ini</h3>
      <p>Tidak ada jadwal kuliah yang terdaftar untuk kelas Anda hari ini.</p>
    </div>
    </div>
    @else
      <div class="data-table-container">
      <table class="data-table">
      <thead>
      <tr>
      <th>Mata Kuliah</th>
      <th class="text-nowrap">Jam Kuliah</th>
      <th>Ruangan</th>
      <th>Dosen</th>
      <th class="text-center" style="min-width: 200px;">Presensi Anda</th>
      </tr>
      </thead>
      <tbody>
      @foreach($jadwalHariIni as $jadwal)
      <tr id="jadwal-row-mahasiswa-{{ $jadwal->id }}"> {{-- ID unik --}}
      <td>
      <div class="fw-bold">{{ $jadwal->mataKuliah->nama_mk ?? 'N/A' }}</div>
      <small class="text-muted">{{ $jadwal->mataKuliah->kode_mk ?? 'N/A' }}</small>
      </td>
      <td class="text-nowrap">
      <span class="time-badge">
      <i class="far fa-clock"></i>
      {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
      {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
      </span>
      </td>
      <td>
      <span class="ruangan-badge">{{ $jadwal->ruangan }}</span>
      </td>
      <td>{{ $jadwal->dosen->nama ?? 'N/A' }}</td>
      <td class="text-center">
      @php
      $presensiDetail = $presensiSudahDilakukan->get($jadwal->id);
      $statusPresensi = $presensiDetail ? $presensiDetail->status_kehadiran : null;
      $isHadir = $statusPresensi === 'Hadir'; // Dianggap Hadir jika statusnya 'Hadir'
      @endphp
      <div class="presensi-controls-mahasiswa" data-jadwal-id="{{ $jadwal->id }}">
        <div class="form-check form-switch">
        <input class="form-check-input presensi-mahasiswa-toggle" 
         type="checkbox" 
         role="switch"
         id="presensiToggleMahasiswa{{ $jadwal->id }}" 
         data-pengampu-id="{{ $jadwal->id }}"
         {{ $isHadir ? 'checked' : '' }}>
        </div>
        <span id="presensi-mahasiswa-status-text-{{ $jadwal->id }}"
        class="attendance-badge {{ $isHadir ? 'badge-present' : 'badge-absent' }}">
        {{ $statusPresensi ? $statusPresensi : 'Belum Presensi' }}
        </span>
      </div>
      <small class="presensi-info-mahasiswa" id="presensi-info-mahasiswa-{{ $jadwal->id }}">
        @if($statusPresensi)
      Status terakhir: {{ $statusPresensi }}
      @else
      Silakan lakukan presensi.
      @endif
      </small>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>
      </div>
    @endif
    </div>
@endsection

@push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const globalAlertContainer = document.getElementById('global-alert-container-mahasiswa'); // Container alert unik

    function showAlert(type, message, container = globalAlertContainer) {
      if (!container) {
        console.warn('Alert container not found for mahasiswa presensi.');
        return;
      }
      const existingAlert = container.querySelector('.dynamic-alert');
      if (existingAlert) existingAlert.remove();

      const alertElement = document.createElement('div');
      alertElement.className = `dynamic-alert alert alert-${type} alert-dismissible fade show`; 
      alertElement.setAttribute('role', 'alert');
      alertElement.style.display = 'flex';
      alertElement.style.alignItems = 'center';
      alertElement.style.gap = '0.75rem';
      alertElement.style.padding = '1rem'; 
      alertElement.style.borderRadius = '0.375rem'; 
      alertElement.style.marginBottom = '1rem';

      let iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
      alertElement.innerHTML = `<i class="fas ${iconClass} me-2"></i> <span>${message}</span> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

      if (type === 'success') {
        alertElement.style.backgroundColor = '#d1e7dd'; 
        alertElement.style.color = '#0f5132'; 
      } else { 
        alertElement.style.backgroundColor = '#f8d7da'; 
        alertElement.style.color = '#842029'; 
      }
      container.insertBefore(alertElement, container.firstChild);
      setTimeout(() => {
        if (alertElement.parentElement) {
          if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
            const bsAlertInstance = bootstrap.Alert.getInstance(alertElement);
            if (bsAlertInstance) bsAlertInstance.close();
            else alertElement.remove();
          } else {
            alertElement.remove();
          }
        }
      }, 7000);
    }

    document.querySelectorAll('.presensi-mahasiswa-toggle').forEach(toggle => {
      toggle.addEventListener('change', function () {
        const pengampuId = this.dataset.pengampuId;
        const isChecked = this.checked; // Status BARU dari toggle
        const statusTextSpan = document.getElementById(`presensi-mahasiswa-status-text-${pengampuId}`);
        const presensiInfoSpan = document.getElementById(`presensi-info-mahasiswa-${pengampuId}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!statusTextSpan || !presensiInfoSpan) {
          console.error('Element status atau info tidak ditemukan untuk pengampu ID (mahasiswa):', pengampuId);
          return;
        }
        if (!csrfToken) {
          showAlert('danger', 'Kesalahan konfigurasi: CSRF token tidak ditemukan.');
          console.error('CSRF token not found.');
          return;
        }

        const previousCheckedState = !isChecked; // Status SEBELUM diubah, untuk revert

        // Optimistic UI Update
        statusTextSpan.textContent = 'Memproses...';
        statusTextSpan.classList.remove('badge-present', 'badge-absent', 'badge-processing');
        statusTextSpan.classList.add('badge-processing');
        this.disabled = true; 

        const statusYangDikirim = isChecked ? 'Hadir' : 'Tidak Hadir';

        fetch('{{ route("mahasiswa.presensi.submit") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            pengampu_mata_kuliah_id: pengampuId,
            status_kehadiran: statusYangDikirim,
          })
        })
        .then(response => {
          if (!response.ok) {
            return response.json().then(errData => { 
              errData.statusCode = response.status; 
              throw errData; 
            });
          }
          return response.json();
        })
        .then(data => {
          this.disabled = false; 
          if (data.success && data.status_kehadiran_display) {
            showAlert('success', data.message);
            statusTextSpan.textContent = data.status_kehadiran_display;
            statusTextSpan.classList.remove('badge-processing', 'badge-present', 'badge-absent');
            statusTextSpan.classList.add(data.status_kehadiran_display === 'Hadir' ? 'badge-present' : 'badge-absent');
            presensiInfoSpan.textContent = 'Status presensi: ' + data.status_kehadiran_display;
          } else {
            // Revert UI on failure from server (data.success === false)
            this.checked = previousCheckedState;
            statusTextSpan.textContent = previousCheckedState ? 'Hadir' : 'Tidak Hadir';
            statusTextSpan.classList.remove('badge-processing', 'badge-present', 'badge-absent');
            statusTextSpan.classList.add(previousCheckedState ? 'badge-present' : 'badge-absent');
            presensiInfoSpan.textContent = 'Gagal memperbarui. Status kembali ke sebelumnya.';
            showAlert('danger', data.message || 'Gagal memperbarui presensi.');
          }
        })
        .catch(error => {
          this.disabled = false; 
          // Revert UI on network error or other exceptions
          this.checked = previousCheckedState;
          statusTextSpan.textContent = previousCheckedState ? 'Hadir' : 'Tidak Hadir';
          statusTextSpan.classList.remove('badge-processing', 'badge-present', 'badge-absent');
          statusTextSpan.classList.add(previousCheckedState ? 'badge-present' : 'badge-absent');
          presensiInfoSpan.textContent = 'Error. Status kembali ke sebelumnya.';

          console.error('Fetch Error/Exception Mahasiswa Presensi:', error);
          let msg = 'Tidak dapat terhubung ke server atau terjadi kesalahan.';
          if (error && error.message) { 
            msg = error.message; 
          }
          showAlert('danger', msg);
        });
      });
    });
  });
  </script>
@endpush

