<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">

    @stack('styles')
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #3f37c9;
            --accent-color: #17a2b8;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --text-color: #2b2d42;
            --text-light: #8d99ae;
            --light-bg: #f8f9fa;
            --white-bg: #ffffff;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --border-color: #e9ecef;
            --header-height: 75px;
            --footer-height: 60px;
            --sidebar-width-expanded: 260px;
            --sidebar-width-collapsed: 80px;
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 4px 15px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            min-height: 100vh;
            /* Menggunakan min-height agar footer tidak tumpang tindih */
            display: flex;
            line-height: 1.6;
            transition: var(--transition);
            /* overflow: hidden; Dihapus agar content bisa scroll jika lebih panjang dari viewport */
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width-expanded);
            background: var(--sidebar-bg);
            color: white;
            padding: 1.5rem 0;
            box-shadow: var(--shadow-medium);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            z-index: 1030;
            /* Di atas header jika diperlukan */
            position: fixed;
            /* Ubah ke fixed untuk sidebar yang tetap */
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            /* Scroll jika konten sidebar panjang */
        }

        /* Hide scrollbar for Webkit browsers */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 10px;
            border: 2px solid var(--sidebar-bg);
        }

        /* Hide scrollbar for Firefox */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) var(--sidebar-bg);
        }


        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-width-collapsed);
        }

        body.sidebar-collapsed .sidebar .sidebar-header h2 {
            display: none;
        }

        body.sidebar-collapsed .sidebar .sidebar-header {
            padding-bottom: 0;
            margin-bottom: 1rem;
            /* Tetap ada margin */
        }

        body.sidebar-collapsed .sidebar .sidebar-nav ul li a span {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
            /* Agar tidak memakan ruang */
            visibility: hidden;
            /* Sembunyikan sepenuhnya */
        }

        body.sidebar-collapsed .sidebar .sidebar-nav ul li a {
            justify-content: center;
            padding: 0.75rem 0;
            /* Sesuaikan padding untuk ikon saja */
        }

        body.sidebar-collapsed .sidebar .sidebar-nav ul li a i {
            margin-right: 0;
            font-size: 1.3rem;
            /* Ukuran ikon saat collapsed */
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 1rem;
            padding: 0 1rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            margin: 0;
            /* Dihapus margin atas bawah */
            color: #ffffff;
            /* Warna putih untuk kontras */
            font-size: 1.25rem;
            /* Sedikit lebih besar */
            font-weight: 600;
            padding-top: 0.5rem;
            /* Padding agar tidak terlalu mepet */
        }

        .sidebar-header h2 i {
            color: var(--accent-color);
            /* Warna aksen untuk ikon */
        }

        .sidebar-nav {
            flex-grow: 1;
            /* overflow-y: auto; sudah di .sidebar */
            padding: 0 0.5rem;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            /* Dihapus padding kiri agar konsisten */
        }

        .sidebar ul li {
            margin-bottom: 0.5rem;
        }

        .sidebar ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: var(--transition);
            font-weight: 400;
            position: relative;
            overflow: hidden;
            /* Untuk efek hover yang lebih baik */
        }

        .sidebar ul li a i {
            margin-right: 0.85rem;
            /* Sedikit lebih jauh */
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active:hover {
            /* Hover state untuk link aktif juga */
            background: var(--sidebar-hover);
            color: white;
            transform: translateX(3px);
            /* Efek geser halus */
        }

        .sidebar ul li a.active {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
            /* Shadow lebih lembut */
        }

        /* Tooltip for collapsed sidebar */
        body.sidebar-collapsed .sidebar ul li a::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-width-collapsed) + 5px);
            /* Muncul di kanan sidebar collapsed */
            top: 50%;
            transform: translateY(-50%);
            background: #333;
            /* Warna tooltip */
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            pointer-events: none;
            /* Agar tidak mengganggu hover di ikon */
            z-index: 1050;
            /* Di atas elemen lain */
        }

        body.sidebar-collapsed .sidebar ul li a:hover::after {
            opacity: 1;
            visibility: visible;
        }


        /* Main Content Area */
        .main-wrapper {
            /* Wrapper baru untuk main-content dan footer */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            /* Full viewport height */
            margin-left: var(--sidebar-width-expanded);
            /* Space untuk sidebar expanded */
            transition: margin-left var(--transition);
            overflow: hidden;
            /* Mencegah double scrollbar dari body */
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: var(--sidebar-width-collapsed);
            /* Space untuk sidebar collapsed */
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white-bg);
            padding: 0 2rem;
            /* Disesuaikan paddingnya */
            box-shadow: var(--shadow-light);
            height: var(--header-height);
            flex-shrink: 0;
            /* Agar header tidak mengecil */
            z-index: 1020;
            /* Di bawah sidebar jika sidebar fixed */
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 1rem;
            display: flex;
            /* Defaultnya selalu tampil, diatur di media query */
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        .sidebar-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }

        .header-title-static {
            /* Class baru untuk judul statis */
            font-weight: 600;
            /* Dibuat lebih tebal */
            margin: 0;
            font-size: 1.25rem;
            /* Disesuaikan ukurannya */
            color: var(--primary-color);
            /* Warna primer */
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            /* Jarak antar elemen user info */
        }

        .user-info .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            /* Ukuran font avatar */
        }

        .user-info .user-details {
            display: flex;
            flex-direction: column;
            text-align: left;
            /* Agar teks rata kanan */
        }

        .user-info .user-name {
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        /* Content Area */
        .content-wrapper {
            /* Ini akan menjadi area scroll utama */
            flex-grow: 1;
            overflow-y: auto;
            /* Hanya content-wrapper yang scroll */
            padding: 1.5rem;
            /* Padding konsisten */
            background-color: var(--light-bg);
        }

        /* .content-area tidak perlu style khusus jika .content-wrapper sudah menangani padding */

        /* Footer */
        .footer {
            height: var(--footer-height);
            text-align: center;
            padding: 1rem;
            /* Padding yang cukup */
            background-color: var(--white-bg);
            /* Warna background footer */
            color: var(--text-light);
            flex-shrink: 0;
            /* Agar footer tidak mengecil */
            font-size: 0.875rem;
            border-top: 1px solid var(--border-color);
            /* Garis pemisah */
        }

        .footer p {
            margin: 0;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                /* Sembunyikan di luar layar */
                /* position: fixed; sudah di atas */
            }

            .sidebar.active {
                /* Saat mobile dan aktif */
                transform: translateX(0);
                width: var(--sidebar-width-expanded);
                /* Lebar tetap saat aktif di mobile */
            }

            body.sidebar-collapsed .sidebar {
                /* Saat mobile, collapsed berarti tersembunyi */
                transform: translateX(-100%);
            }

            .main-wrapper {
                margin-left: 0;
                /* Konten utama full width saat sidebar mobile tersembunyi */
            }

            /* .sidebar-toggle selalu tampil di mobile, tidak perlu diubah */
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 1rem;
                /* Kurangi padding header */
                height: 65px;
                /* Sesuaikan tinggi header */
            }

            .header-title-static {
                font-size: 1.1rem;
                /* Perkecil judul di mobile */
            }

            .content-wrapper {
                padding: 1rem;
            }

            .user-info .user-details {
                display: none;
                /* Sembunyikan detail user, hanya avatar */
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 220px;
                /* Lebar sidebar mobile bisa disesuaikan */
            }

            .sidebar.active {
                width: 220px;
            }

            .header-title-static {
                display: none;
                /* Sembunyikan judul di layar sangat kecil jika perlu */
            }
        }
    </style>
</head>

<body>
    @auth
        @if (Auth::user()->role === 'admin')
            @include('layouts.sidebar-admin')
        @elseif (Auth::user()->role === 'dosen')
            @include('layouts.sidebar-dosen')
        @elseif (Auth::user()->role === 'mahasiswa')
            @include('layouts.sidebar-mahasiswa')
        @endif
    @endauth

    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                @auth
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                @endauth
                <h3 class="header-title-static">Sistem Informasi Akademik Mahasiswa</h3>
            </div>

            @auth
                <div class="user-info">
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->profile_name ?? Auth::user()->name }}</span>
                        <span class="user-role text-end">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
            @endauth
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Sistem Akademik - KELOMPOK 6. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;
            const sidebar = document.querySelector('.sidebar'); // Pastikan hanya ada satu .sidebar

            if (sidebarToggle && sidebar) {
                // Fungsi untuk toggle sidebar
                function toggleSidebar() {
                    if (window.innerWidth <= 992) { // Mode mobile/tablet
                        sidebar.classList.toggle('active'); // Untuk menampilkan/menyembunyikan
                        // Tidak perlu mengubah body.sidebar-collapsed di mobile
                    } else { // Mode desktop
                        body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebarState', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
                    }
                }
                sidebarToggle.addEventListener('click', toggleSidebar);

                // Load saved state untuk desktop
                const savedState = localStorage.getItem('sidebarState');
                if (window.innerWidth > 992) { // Hanya terapkan state tersimpan di desktop
                    if (savedState === 'collapsed') {
                        body.classList.add('sidebar-collapsed');
                    } else {
                        body.classList.remove('sidebar-collapsed'); // Pastikan defaultnya expanded jika tidak ada state
                    }
                } else {
                    // Di mobile, pastikan sidebar tidak collapsed secara default dari body class
                    body.classList.remove('sidebar-collapsed');
                    sidebar.classList.remove('active'); // Pastikan tersembunyi awal
                }


                // Tambahkan data-tooltip untuk link sidebar
                document.querySelectorAll('.sidebar-nav a').forEach(link => {
                    const spanText = link.querySelector('span');
                    if (spanText) {
                        link.setAttribute('data-tooltip', spanText.textContent.trim());
                    }
                });

                // Menutup sidebar saat klik di luar area sidebar (mode mobile)
                document.addEventListener('click', function (e) {
                    if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                        if (!sidebar.contains(e.target) && e.target !== sidebarToggle && !sidebarToggle.contains(e.target)) {
                            sidebar.classList.remove('active');
                        }
                    }
                });

                // Menyesuaikan state sidebar saat ukuran window berubah
                window.addEventListener('resize', function () {
                    if (window.innerWidth > 992) {
                        sidebar.classList.remove('active'); // Sembunyikan overlay mobile
                        // Kembalikan state desktop dari localStorage jika ada
                        if (localStorage.getItem('sidebarState') === 'collapsed') {
                            body.classList.add('sidebar-collapsed');
                        } else {
                            body.classList.remove('sidebar-collapsed');
                        }
                    } else {
                        // Di mobile, hapus class collapsed dari body agar tidak mempengaruhi layout utama
                        body.classList.remove('sidebar-collapsed');
                        sidebar.classList.remove('active'); // Pastikan tersembunyi saat resize ke mobile
                    }
                });
            } else {
                if (!sidebarToggle) console.error('Sidebar toggle button not found');
                if (!sidebar) console.error('Sidebar element not found');
            }
            $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
                let message = 'Terjadi kesalahan di server. Silakan coba lagi.';
                if (jqxhr.status === 419) {
                    message = 'Sesi Anda telah berakhir. Halaman akan dimuat ulang.';
                } else if (jqxhr.responseJSON && jqxhr.responseJSON.message) {
                    message = jqxhr.responseJSON.message;
                }

                Swal.fire({
                    title: `Error ${jqxhr.status}`,
                    text: message,
                    icon: 'error'
                }).then(() => {
                    if (jqxhr.status === 419) window.location.reload();
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>