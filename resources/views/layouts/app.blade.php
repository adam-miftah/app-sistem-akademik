<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        :root {
            --primary-color: var(--bs-primary);
            --primary-hover: #3a56d4;
            --secondary-color: #3f37c9;
            --accent-color: var(--bs-info);
            --success-color: var(--bs-success);
            --danger-color: var(--bs-danger);
            --warning-color: var(--bs-warning);
            --text-color: #2b2d42;
            --text-light: #8d99ae;
            --light-bg: #f8f9fa;
            --white-bg: #ffffff;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --border-color: #e9ecef;
            --header-height: 75px;
            --footer-height: 10px;
            --sidebar-width-expanded: 250px;
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
            height: 100vh;
            display: flex;
            line-height: 1.6;
            transition: var(--transition);
            overflow: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width-expanded);
            background: var(--sidebar-bg);
            color: white;
            padding: 1.5rem 0;
            box-shadow: var(--shadow-medium);
            flex-shrink: 0;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            z-index: 1000;
            position: relative;
        }

        /* Collapsed sidebar state */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-width-collapsed);
        }

        body.sidebar-collapsed .sidebar-header h2 {
            display: none;
        }

        body.sidebar-collapsed .sidebar-header {
            padding-bottom: 0;
            margin-bottom: 1rem;
        }

        body.sidebar-collapsed .sidebar-nav ul li a span {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        body.sidebar-collapsed .sidebar-nav ul li a {
            justify-content: center;
            padding: 0.75rem 0;
        }

        body.sidebar-collapsed .sidebar-nav ul li a i {
            margin-right: 0;
            font-size: 1.3rem;
        }

        body.sidebar-collapsed .sidebar-nav ul li a:hover span {
            display: none;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 1rem;
            padding: 0 1rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .sidebar-header h2 {
            margin: 6px;
            color: var(--accent-color);
            font-size: 1.2rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .sidebar-nav {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 0.5rem;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .sidebar ul {
            list-style: none;
            padding: 0 0.5rem;
        }

        .sidebar ul li {
            margin-bottom: 0.5rem;
            position: relative;
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
        }

        .sidebar ul li a i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            transition: var(--transition);
        }

        .sidebar ul li a:hover {
            background: linear-gradient(90deg, rgba(67, 97, 238, 0.2) 0%, rgba(255, 255, 255, 0.05) 100%);
            color: white;
            transform: translateX(5px);
        }

        .sidebar ul li a.active {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        /* Tooltip for collapsed sidebar */
        body.sidebar-collapsed .sidebar ul li a:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--sidebar-bg);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            white-space: nowrap;
            margin-left: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 0;
            animation: fadeIn 0.3s forwards;
            pointer-events: none;
            z-index: 1000;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                margin-left: 15px;
            }
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            transition: var(--transition);
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white-bg);
            padding: 1.25rem 2rem;
            box-shadow: var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--header-height);
        }

        .header h1 {
            margin: 0;
            font-size: 1.75rem;
            color: var(--text-color);
            font-weight: 600;
        }

        /* Toggle button style */
        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        .sidebar-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }

        .header {
            flex-shrink: 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100px;
        }

        .header-left h3 {
            font-weight: 500;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            transition: var(--transition);
        }

        .user-info:hover .user-avatar {
            transform: scale(1.1);
        }

        .user-info .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-info .user-name {
            font-weight: 500;
            color: var(--text-color);
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        /* Content Area */
        .content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 2rem;
            background-color: var(--light-bg);
        }

        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title h2 {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Footer */
        .footer {
            height: var(--footer-height);
            text-align: center;
            padding: 1.5rem;
            background-color: transparent;
            color: var(--text-light);
            flex-shrink: 0;
            font-size: 0.875rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            body.sidebar-collapsed .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
                height: 60px;
            }

            .header-left h3 {
                margin-top: 15px;
                font-size: 1rem;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .user-info .user-details {
                display: none;
            }

            .sidebar-toggle {
                margin-right: 0.5rem;
                width: 36px;
                height: 36px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 80%;
            }

            .content-wrapper {
                padding: 0.75rem;
            }

            .page-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .page-title h2 {
                font-size: 1.25rem;
            }
        }
    </style>
    @stack('styles')
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

    <div class="main-content">
        <div class="header">
            <div class="header-left">
                @auth
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                @endauth
                <h3>@yield('header_title')</h3>
            </div>

            @auth
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->profile_name }}</span>
                        <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
            @endauth
        </div>

        <div class="content-wrapper">
            <div class="content-area">
                @yield('content')
            </div>

            <footer class="footer">
                <p>&copy; {{ date('Y') }} Sistem Akademik. All rights reserved.</p>
            </footer>
        </div>
    </div>

    {{-- @stack('scripts') --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');

            if (sidebarToggle) {
                // Toggle sidebar state
                sidebarToggle.addEventListener('click', function () {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.toggle('active');
                    } else {
                        body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebarState', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
                    }
                });

                // Load saved state
                const savedState = localStorage.getItem('sidebarState');
                if (savedState === 'collapsed' && window.innerWidth > 992) {
                    body.classList.add('sidebar-collapsed');
                }

                // Add data-tooltip attributes to sidebar links
                document.querySelectorAll('.sidebar-nav a').forEach(link => {
                    if (link.querySelector('span')) {
                        link.setAttribute('data-tooltip', link.querySelector('span').textContent);
                    }
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function (e) {
                    if (window.innerWidth <= 992 && !sidebar.contains(e.target) &&
                        e.target !== sidebarToggle && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            }

            // Handle window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>