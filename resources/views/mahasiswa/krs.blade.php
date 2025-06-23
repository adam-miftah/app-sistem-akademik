@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS)')
@section('header_title', 'Kartu Rencana Studi (KRS)')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-tasks me-2"></i>Kartu Rencana Studi (KRS)
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari kode atau nama mata kuliah...">
      </div>
      <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="fas fa-print me-1"></i> Cetak KRS
      </button>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($krsDetails->isEmpty()) is-empty @endif" id="krs-table"
        style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Kode MK</th>
          <th>Mata Kuliah</th>
          <th>Dosen Pengampu</th>
          <th class="text-center">Kelas</th>
          <th class="text-center">SKS</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($krsDetails as $item)
      <tr>
        <td class="text-center"></td>
        <td data-label="Kode MK">{{ optional($item->mataKuliah)->kode_mk ?? '-' }}</td>
        <td data-label="Mata Kuliah">
        <span class="fw-semibold">{{ optional($item->mataKuliah)->nama_mk ?? 'N/A' }}</span>
        </td>
        <td data-label="Dosen">{{ optional($item->dosen)->nama ?? 'N/A' }}</td>
        <td data-label="Kelas" class="text-center">{{ $item->kelas ?? '-' }}</td>
        <td data-label="SKS" class="text-center">
        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-2">
        {{ optional($item->mataKuliah)->sks ?? '0' }} SKS
        </span>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada mata kuliah yang direncanakan.</p>
        </td>
      </tr>
      @endforelse
        </tbody>
        {{-- Bagian Footer Tabel untuk Total SKS --}}
        @if(!$krsDetails->isEmpty())
      <tfoot class="table-light fw-bold">
      <tr>
        <td colspan="4" class="text-center pe-4">Total SKS</td>
        <td colspan="2" class="text-center">{{ $totalSKS }}</td>
      </tr>
      </tfoot>
      @endif
      </table>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Menggunakan style yang sama dengan halaman lain untuk konsistensi */
    .text-gradient {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .table th {
    font-weight: 600;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    }

    .table td {
    vertical-align: middle;
    font-size: .875rem;
    }

    .table tfoot td {
    font-size: .9rem;
    }

    @media (max-width: 991.98px) {

    .table thead,
    .table tfoot {
      display: none;
    }

    .table tr {
      display: block;
      margin-bottom: 1rem;
      border: 1px solid #dee2e6;
      border-radius: .5rem;
    }

    .table td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f0f0f0;
      padding: .75rem 1rem;
    }

    .table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #6c757d;
      margin-right: 1rem;
    }

    .table td:last-child {
      border-bottom: 0;
    }
    }

    @media print {
    body * {
      visibility: hidden;
    }

    .card,
    .card * {
      visibility: visible;
    }

    .card {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      box-shadow: none;
      border: none;
    }

    .card-header .d-flex {
      display: none !important;
    }

    hr,
    .btn {
      display: none !important;
    }

    .dataTables_paginate,
    .dataTables_info {
      display: none !important;
    }

    .table-responsive {
      overflow: visible !important;
    }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    var table = $('#krs-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false,
      order: [[2, 'asc']], // Urutkan berdasarkan Nama Mata Kuliah
      language: {
      search: "",
      zeroRecords: "Data tidak ditemukan.",
      info: "Menampilkan _START_ - _END_ dari _TOTAL_ mata kuliah",
      infoEmpty: "Menampilkan 0 mata kuliah",
      paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
      // Penomoran otomatis
      { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
      // SKS tidak perlu diurutkan
      { orderable: false, targets: 5 },
      ],
      // Fungsi untuk menggambar footer (total SKS) agar tetap terlihat saat paginasi
      "footerCallback": function (row, data, start, end, display) {
      var api = this.api(), data;

      // Jika Anda ingin kalkulasi SKS dinamis di sisi klien (opsional)
      // var totalSks = api.column(5, { page: 'current'} ).data().reduce( function (a, b) {
      //     return parseInt(a) + parseInt(String(b).match(/\d+/));
      // }, 0 );

      // $(api.column(5).footer()).html(totalSks);
      }
    });
    $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
    });
    });
  </script>
@endpush