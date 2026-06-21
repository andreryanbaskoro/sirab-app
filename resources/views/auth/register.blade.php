<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - SIRAB</title>

    <link href="{{ asset('themes/assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #0f172a, #1d4ed8);
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .register-card {
            width: 100%;
            max-width: 1100px;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
            display: flex;
        }

        .register-left {
            width: 45%;
            background: linear-gradient(135deg, #1e40af, #2563eb);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-left h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .register-left p {
            line-height: 1.8;
            opacity: .9;
        }

        .register-right {
            width: 55%;
            padding: 50px;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-control {
            height: 46px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
        }

        select.form-control {
            cursor: pointer;
        }

        .btn-register {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-weight: 600;
            font-size: 15px;
            transition: .3s;
        }

        .btn-register:hover {
            background: #1d4ed8;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
        }

        .checkbox-area {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        @media(max-width:768px) {

            .register-card {
                width: 100%;
                max-width: 900px;
                min-height: 620px;
            }

            .register-left {
                width: 40%;
                padding: 40px;
            }

            .register-right {
                width: 60%;
                padding: 35px;
            }

            .register-left,
            .register-right {
                width: 100%;
            }

            .register-left h1 {
                font-size: 36px;
            }

            .page-title {
                font-size: 26px;
            }

        }
    </style>
</head>

<body>

    <div class="register-wrapper">

        <div class="register-card">

            <!-- LEFT SIDE -->
            <div class="register-left">
                <h1>SIRAB</h1>

                <p>
                    Buat akun untuk mengakses Sistem Informasi Rencana Anggaran Biaya.
                    Kelola proyek, material, pekerjaan, biaya tambahan, dan laporan
                    secara terintegrasi dalam satu platform.
                </p>
            </div>

            <!-- RIGHT SIDE -->
            <div class="register-right">

                <h2 class="page-title">Buat Akun</h2>
                <div class="page-subtitle">
                    Lengkapi data berikut untuk mendaftar.
                </div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-bottom:0;padding-left:20px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <br>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>

                            <option value="konsumen"
                                {{ old('role') == 'konsumen' ? 'selected' : '' }}>
                                Konsumen
                            </option>

                            <option value="kepala_tukang"
                                {{ old('role') == 'kepala_tukang' ? 'selected' : '' }}>
                                Kepala Tukang
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Masukkan ulang password"
                            required>
                    </div>

                    <div class="checkbox-area">
                        <label>
                            <input type="checkbox" name="agree" required>
                            Saya menyetujui syarat dan ketentuan yang berlaku.
                        </label>
                    </div>

                    <button type="submit" class="btn-register">
                        Daftar Sekarang
                    </button>

                    <div class="login-link">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">
                            Login di sini
                        </a>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <script src="{{ asset('themes/assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

</body>

</html>