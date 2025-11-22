<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Portal Karang Taruna</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --primary-color: #A50104; --dark-red: #7A0103; --white: #ffffff; }
        body { background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-red) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .login-container { display: flex; background: var(--white); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; max-width: 900px; width: 90%; min-height: 550px; }
        .login-left { flex: 1; background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-red) 100%); padding: 40px; color: var(--white); display: flex; flex-direction: column; justify-content: center; position: relative; }
        .login-right { flex: 1; padding: 50px; display: flex; flex-direction: column; justify-content: center; }
        .form-input { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px; margin-bottom: 20px; transition: 0.3s; }
        .form-input:focus { border-color: var(--primary-color); outline: none; }
        .btn-login { width: 100%; padding: 14px; background: var(--primary-color); color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-login:hover { background: var(--dark-red); }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c2c7; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h1>Portal Admin<br>Karang Taruna</h1>
            <p>Kelola layanan mediasi, pengaduan, dan proposal dengan sistem yang terintegrasi.</p>
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

                <label style="font-weight:600; font-size:14px; display:block; margin-bottom:8px;">Username</label>
                <input type="text" name="username" class="form-input" placeholder="Masukkan username" required value="{{ old('username') }}">

                <label style="font-weight:600; font-size:14px; display:block; margin-bottom:8px;">Password</label>
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
