<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Portal Karang Taruna</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #A50104;
            --dark-red: #7A0103;
            --white: #ffffff;
            --gold: #FCBA04;
        }

        body {
            background: linear-gradient(135deg, #e17b06 0%, #ff0d0d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }

        .login-container {
            display: flex;
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 600px;
            position: relative;
        }

        /* === BAGIAN KIRI (BRANDING) === */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-red) 100%);
            padding: 60px;
            color: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start; /* Rata Kiri biar rapi */
            position: relative;
            overflow: hidden;
        }

        /* Hiasan Background Circle */
        .login-left::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(252, 186, 4, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
        }

        .logo-section {
            position: relative;
            z-index: 10;
        }

        .logo-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            width: 90px;
            height: 90px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .logo-box img {
            width: 60px;
            height: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .brand-title {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .brand-title span {
            color: var(--gold); /* Warna Emas buat aksen */
            display: block; /* Baris baru */
            font-size: 24px;
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 0;
            opacity: 0.9;
        }

        .brand-desc {
            font-size: 16px;
            opacity: 0.8;
            line-height: 1.6;
            max-width: 85%;
            font-weight: 300;
        }

        /* === BAGIAN KANAN (FORM) === */
        .login-right {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: var(--white);
        }

        .login-header h2 { font-size: 26px; color: #1f2937; font-weight: 700; margin-bottom: 8px; }
        .login-header p { color: #6b7280; font-size: 14px; }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #f3f4f6;
            background-color: #f9fafb;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s;
            font-size: 15px;
        }
        .form-input:focus {
            border-color: var(--primary-color);
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(165, 1, 4, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(165, 1, 4, 0.25);
        }

        .alert-danger {
            color: #991b1b;
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Responsive HP */
        @media (max-width: 768px) {
            .login-container { flex-direction: column; max-width: 400px; }
            .logo-section .logo-box {margin: auto;  }
            .logo-section .brand-title {margin-top: 1.5rem;}
            .login-left { padding: 40px 30px; min-height: auto; align-items: center; text-align: center; }
            .brand-desc { max-width: 100%; display: none; /* Sembunyiin deskripsi di HP biar ringkas */ }
            .login-right { padding: 40px 30px; }
        }
    </style></head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo-section">
                <div class="logo-box">
                    <img src="{{ asset('assets/img/logo-karang-taruna.svg') }}" alt="Logo Karang Taruna" class="login-logo" style="width: 80px; height: 80px;">
                </div>
                <h1 class="brand-title">Portal Admin
                    <span>Karang Taruna 006/013</span>
                </h1>
                <p class="brand-desc">Sistem manajemen terpadu untuk pengaduan, proposal, dan informasi kegiatan warga secara real-time.</p>
            </div>
        </div>

        <div class="login-right">
            <div style="margin-bottom: 30px;">
                <h2 style="color:#333; margin-bottom:5px;">Selamat Datang</h2>
                <p style="color:#666; font-size:14px;">Silakan login untuk masuk ke dashboard.</p>
            </div>

            @if($errors->any())
                <div class="alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/admin/login') }}" method="POST">
                @csrf

                <label style="color: #333; font-weight:600; font-size:14px; display:block; margin-bottom:8px;">Username</label>
                <input type="text" name="username" class="form-input" placeholder="Masukkan username" required value="{{ old('username') }}">

                <label style="color: #333; font-weight:600; font-size:14px; display:block; margin-bottom:8px;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>

                <div style="display:flex; align-items:center; margin-bottom:20px;">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" style="margin-left:8px; font-size:14px; color:#666;">Ingat Saya</label>
                </div>

                <button type="submit" class="btn-login">Masuk Dashboard</button>
            </form>
        </div>
    </div>
</body>
</html>
