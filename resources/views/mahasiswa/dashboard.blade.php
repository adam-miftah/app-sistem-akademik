@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('header_title', 'Dashboard Mahasiswa')

@section('content')
    <div class="container-fluid px-0 px-md-3">
            <h5 class="h3 mb-4 text-gray-800">Selamat Datang, <span class="text-primary">{{ $mahasiswa->nama }}</span>!</h4>
                {{-- Alert for success/error messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3 mx-md-0" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-3 mx-md-0" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Quick Stats Cards --}}
                {{-- <div class="row mb-4 mx-0 mx-md-2">
                    <div class="col-md-4 mb-3 px-2">
                        <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-book fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-subtitle mb-1">Total Mata Kuliah</h6>
                                        <h3 class="mb-0">12</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-primary-dark border-0 py-2">
                                <a href="#" class="text-white small d-flex align-items-center justify-content-between">
                                    <span>Lihat Detail</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 px-2">
                        <div class="card bg-success text-white h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-subtitle mb-1">IPK Terakhir</h6>
                                        <h3 class="mb-0">3.75</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-success-dark border-0 py-2">
                                <a href="#" class="text-white small d-flex align-items-center justify-content-between">
                                    <span>Riwayat Nilai</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 px-2">
                        <div class="card bg-info text-white h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-subtitle mb-1">Semester Aktif</h6>
                                        <h3 class="mb-0">6</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-info-dark border-0 py-2">
                                <a href="#" class="text-white small d-flex align-items-center justify-content-between">
                                    <span>Jadwal Kuliah</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- Main Content --}}
                <div class="row mx-0 mx-md-2">
                    {{-- Left Column --}}
                    <div class="col-lg-8 mb-4 px-2">
                        {{-- Performance Chart --}}
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Perkembangan Akademik</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="chartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartDropdown">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Unduh
                                                    Data</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Cetak</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if(empty($chartLabels))
                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                        style="height: 300px;">
                                        <i class="fas fa-chart-line text-muted fa-3x mb-3"></i>
                                        <p class="text-muted">Belum ada data nilai untuk menampilkan grafik</p>
                                        <a href="#" class="btn btn-sm btn-primary">Lihat Nilai</a>
                                    </div>
                                @else
                                    <div class="chart-container" style="position: relative; height: 300px;">
                                        <canvas id="ipkIpsChart"></canvas>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</small>
                                    <div>
                                        <span class="badge bg-primary-light text-primary me-2">
                                            <i class="fas fa-circle me-1"></i> IPS
                                        </span>
                                        <span class="badge bg-purple-light text-purple">
                                            <i class="fas fa-circle me-1"></i> IPK
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-4 mb-4 px-2">
                        {{-- Announcements --}}
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Pengumuman Terkini</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Pendaftaran Wisuda Periode Juni</h6>
                                            <small class="text-muted">2 hari lalu</small>
                                        </div>
                                        <p class="mb-1 small text-muted">Batas pendaftaran wisuda untuk periode Juni 2023 adalah
                                            tanggal 15 Mei 2023.</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Perubahan Jadwal Ujian</h6>
                                            <small class="text-muted">1 minggu lalu</small>
                                        </div>
                                        <p class="mb-1 small text-muted">Mata kuliah Basis Data akan diujikan pada tanggal 20
                                            Mei
                                            bukan 18 Mei.</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Beasiswa Prestasi</h6>
                                            <small class="text-muted">2 minggu lalu</small>
                                        </div>
                                        <p class="mb-1 small text-muted">Pendaftaran beasiswa prestasi dibuka hingga 30 Mei
                                            2023.
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 py-2">
                                <a href="#" class="btn btn-sm btn-outline-primary w-100">Lihat Semua Pengumuman</a>
                            </div>
                        </div>

                        {{-- Quick Links --}}
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Akses Cepat</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="row g-2 p-3">
                                    <div class="col-6">
                                        <a href="#"
                                            class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 border rounded">
                                            <i class="fas fa-calendar-alt text-primary mb-2 fa-lg"></i>
                                            <span class="small">Jadwal Kuliah</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#"
                                            class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 border rounded">
                                            <i class="fas fa-book text-success mb-2 fa-lg"></i>
                                            <span class="small">KRS Online</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#"
                                            class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 border rounded">
                                            <i class="fas fa-file-alt text-info mb-2 fa-lg"></i>
                                            <span class="small">Nilai & Transkrip</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vision & Mission --}}
                <div class="row mx-0 mx-md-2">
                    <div class="col-12 px-2">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Visi dan Misi Universitas</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4 mb-md-0">
                                        <div class="vision-mission-card bg-primary-light p-4 h-100 rounded">
                                            <h5 class="text-center text-primary mb-4">
                                                <i class="fas fa-eye me-2"></i>Visi
                                            </h5>
                                            <p class="text-center lead">"Menjadi pusat pendidikan tinggi yang unggul dan
                                                inovatif,
                                                menghasilkan lulusan berdaya saing global, serta berkontribusi nyata bagi
                                                kemajuan
                                                ilmu pengetahuan dan kesejahteraan masyarakat."</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vision-mission-card bg-secondary-light p-4 h-100 rounded">
                                            <h5 class="text-center text-secondary mb-4">
                                                <i class="fas fa-bullseye me-2"></i>Misi
                                            </h5>
                                            <ol class="mission-list">
                                                <li>Menyelenggarakan pendidikan berkualitas yang relevan dengan kebutuhan
                                                    industri
                                                    dan masyarakat.</li>
                                                <li>Melaksanakan penelitian dan pengembangan ilmu pengetahuan yang inovatif dan
                                                    berdampak positif.</li>
                                                <li>Mengembangkan potensi mahasiswa menjadi individu yang berintegritas,
                                                    kreatif,
                                                    dan berjiwa kepemimpinan.</li>
                                                <li>Membangun kemitraan strategis dengan berbagai pihak untuk mendukung Tri
                                                    Dharma
                                                    Perguruan Tinggi.</li>
                                                <li>Mewujudkan tata kelola universitas yang transparan, akuntabel, dan
                                                    berkelanjutan.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const chartLabels = @json($chartLabels);
                    const chartIPSValues = @json($chartIPSValues);
                    const chartIPKValues = @json($chartIPKValues);

                    if (chartLabels.length > 0) {
                        const ctx = document.getElementById('ipkIpsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartLabels,
                                datasets: [
                                    {
                                        label: 'IPS (Indeks Prestasi Semester)',
                                        data: chartIPSValues,
                                        borderColor: '#4e73df',
                                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                                        fill: true,
                                        tension: 0.4,
                                        borderWidth: 2,
                                        pointBackgroundColor: '#4e73df',
                                        pointRadius: 4,
                                        pointHoverRadius: 6
                                    },
                                    {
                                        label: 'IPK (Indeks Prestasi Kumulatif)',
                                        data: chartIPKValues,
                                        borderColor: '#6f42c1',
                                        backgroundColor: 'rgba(111, 66, 193, 0.05)',
                                        fill: true,
                                        tension: 0.4,
                                        borderWidth: 2,
                                        pointBackgroundColor: '#6f42c1',
                                        pointRadius: 4,
                                        pointHoverRadius: 6,
                                        borderDash: [5, 5]
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: '#fff',
                                        titleColor: '#333',
                                        bodyColor: '#666',
                                        borderColor: 'rgba(0,0,0,0.1)',
                                        borderWidth: 1,
                                        padding: 12,
                                        usePointStyle: true,
                                        callbacks: {
                                            label: function (context) {
                                                return context.dataset.label + ': ' + context.raw.toFixed(2);
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 4.0,
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.05)'
                                        },
                                        ticks: {
                                            stepSize: 0.5,
                                            color: '#858796'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#858796'
                                        }
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                }
                            }
                        });
                    }
                });
            </script>
        @endpush

        @push('styles')
            <style>
                .bg-primary-light {
                    background-color: rgba(78, 115, 223, 0.1);
                }

                .bg-secondary-light {
                    background-color: rgba(108, 117, 125, 0.1);
                }

                .bg-success-dark {
                    background-color: rgba(28, 126, 76, 0.9);
                }

                .bg-info-dark {
                    background-color: rgba(25, 135, 153, 0.9);
                }

                .bg-primary-dark {
                    background-color: rgba(41, 98, 223, 0.9);
                }

                .vision-mission-card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .vision-mission-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                }

                .mission-list {
                    padding-left: 20px;
                }

                .mission-list li {
                    margin-bottom: 10px;
                    position: relative;
                }

                .mission-list li:before {
                    content: '';
                    position: absolute;
                    left: -20px;
                    top: 8px;
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background-color: #6c757d;
                }

                .chart-container {
                    min-height: 300px;
                }

                @media (max-width: 768px) {
                    .card-body {
                        padding: 1rem;
                    }

                    .vision-mission-card {
                        margin-bottom: 1rem;
                    }
                }
            </style>
        @endpush
@endsection