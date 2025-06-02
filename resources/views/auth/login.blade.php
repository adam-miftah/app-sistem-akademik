<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --error-color: #ef4444;
            --success-color: #10b981;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --light-bg: #f9fafb;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius: 0.5rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            background-image: url('https://source.unsplash.com/random/1600x900/?university');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .login-container {
            background-color: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo i {
            font-size: 2.5rem;
            color: var(--primary-color);
            background: rgba(99, 102, 241, 0.1);
            width: 70px;
            height: 70px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            font-size: 1.75rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 0.9375rem;
            transition: var(--transition);
            background-color: var(--light-bg);
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            background-color: var(--white);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.8125rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error-message i {
            font-size: 0.875rem;
        }

        button {
            width: 100%;
            padding: 0.875rem;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        button i {
            font-size: 1.1rem;
        }

        /* Flash message styling */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--error-color);
        }

        .alert-danger i {
            font-size: 1.25rem;
        }

        .alert ul {
            list-style: none;
            margin-top: 0.5rem;
        }

        .alert ul li {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .forgot-password {
            text-align: right;
            margin-top: -0.75rem;
            margin-bottom: 1.5rem;
        }

        .forgot-password a {
            color: var(--text-light);
            font-size: 0.875rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .forgot-password a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
            font-size: 0.875rem;
        }

        .footer-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-container {
                padding: 1.75rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .logo i {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 1.5rem 1.25rem;
            }

            input {
                padding: 0.75rem 1rem 0.75rem 2.5rem;
            }

            .input-icon {
                font-size: 1rem;
                left: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
        </div>

        <h1>Masuk ke Sistem Akademik</h1>

        {{-- Error messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email Anda" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-sign-in-alt"></i>
                <span>Masuk</span>
            </button>
        </form>
    </div>
</body>

</html>