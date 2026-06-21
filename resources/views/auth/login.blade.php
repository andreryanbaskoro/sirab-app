<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SIRAB</title>

    <!-- Bootstrap -->
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

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .login-card {
            width: 100%;
            max-width: 1050px;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
            display: flex;
        }

        .login-left {
            width: 45%;
            background: linear-gradient(135deg, #1e40af, #2563eb);
            color: #fff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-left h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .login-left p {
            font-size: 16px;
            line-height: 1.8;
            opacity: .9;
        }

        .login-right {
            width: 55%;
            padding: 60px;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .welcome-subtitle {
            color: #6b7280;
            margin-bottom: 35px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .input-group-custom input {
            width: 100%;
            height: 52px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding-left: 45px;
            padding-right: 15px;
            font-size: 14px;
            transition: .3s;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-row a {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
        }

        .remember-row a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            transition: .3s;
        }

        .btn-login:hover {
            background: #1d4ed8;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #6b7280;
        }

        .register-link a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
        }

        @media (max-width: 768px) {

            .login-card {
                flex-direction: column;
            }

            .login-left,
            .login-right {
                width: 100%;
            }

            .login-left {
                padding: 35px;
                text-align: center;
            }

            .login-left h1 {
                font-size: 36px;
            }

            .login-right {
                padding: 35px 25px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">

        <div class="login-card">

            <!-- LEFT -->
            <div class="login-left">
                <h1>SIRAB</h1>

                <p>
                    Sistem Informasi Rencana Anggaran Biaya yang membantu
                    pengelolaan proyek, material, pekerjaan, biaya tambahan,
                    laporan, dan monitoring data secara terintegrasi.
                </p>
            </div>

            <!-- RIGHT -->
            <div class="login-right">

                <h2 class="welcome-title">Selamat Datang</h2>
                <div class="welcome-subtitle">
                    Silakan login untuk melanjutkan ke sistem.
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

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <label class="form-label">Email</label>
                    <div class="input-group-custom">
                        <i class="fa fa-envelope"></i>
                        <input
                            type="email"
                            name="email"
                            placeholder="Masukkan email"
                            value="{{ old('email') }}"
                            required>
                    </div>

                    <label class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="fa fa-lock"></i>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required>
                    </div>

                    <button type="submit" class="btn-login">
                        Login
                    </button>

                    @if(Route::has('register'))
                    <div class="register-link">
                        Belum punya akun?
                        <a href="{{ route('register') }}">
                            Daftar Sekarang
                        </a>
                    </div>
                    @endif

                </form>

            </div>

        </div>

    </div>

    <script src="{{ asset('themes/assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('themes/assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

</body>

</html>