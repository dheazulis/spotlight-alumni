
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Spotlight Alumni FST</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 35px;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, #dff3e8, transparent 35%),
                radial-gradient(circle at bottom right, #c9ead8, transparent 35%),
                #f3f8f4;
            color: #24324a;
        }

        .login-container {
            width: 100%;
            max-width: 1120px;
            min-height: 680px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 18px 50px rgba(28, 74, 52, 0.13);
        }

        /* PANEL KIRI - FOTO GEDUNG */
        .login-visual {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 55px;
            color: white;
            background:
                linear-gradient(
                    180deg,
                    rgba(8, 48, 37, 0.08) 0%,
                    rgba(8, 48, 37, 0.35) 40%,
                    rgba(8, 65, 47, 0.94) 100%
                ),
                url('/images/gedung-fst.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-visual::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(22, 105, 73, 0.12),
                transparent 65%
            );
        }

        .visual-content {
            position: relative;
            z-index: 1;
            max-width: 440px;
        }

        .login-visual h2 {
            font-size: 34px;
            line-height: 1.35;
            margin: 0 0 18px;
            color: white;
        }

        .visual-divider {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 22px 0;
        }

        .visual-divider span {
            height: 2px;
            flex: 1;
            background: rgba(255, 255, 255, 0.75);
        }

        .visual-divider i {
            font-size: 24px;
            font-style: normal;
        }

        .login-visual p {
            font-size: 15px;
            line-height: 1.8;
            margin: 0;
            color: #f1f8f4;
        }

        /* PANEL KANAN - FORM LOGIN */
        .login-form-area {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 55px;
            background: #fff;
        }

        .login-card {
            width: 100%;
            max-width: 390px;
        }

        /* IDENTITAS FST */
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
            display: block;
            margin: 0 auto 15px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            color: #176346;
            text-align: center;
        }

        .subtitle {
            margin: 10px 0 0;
            color: #77839a;
            font-size: 15px;
            line-height: 1.7;
            text-align: center;
        }

        /* INPUT FORM */
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #24324a;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 19px;
            border: 1px solid #dce9df;
            border-radius: 9px;
            font-size: 14px;
            background: #fbfdfb;
            transition: 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #1d7654;
            box-shadow: 0 0 0 3px rgba(29, 118, 84, 0.10);
            background: white;
        }

        input::placeholder {
            color: #8b96a5;
        }

        /* CAPTCHA */
        .captcha-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .captcha-question {
            flex: 1;
            padding: 14px 10px;
            border-radius: 9px;
            background: #e4f2e9;
            color: #176346;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 5px;
            user-select: none;
        }

        .captcha-refresh {
            width: auto;
            padding: 14px 13px;
            margin: 0;
            background: #e4f2e9;
            color: #176346;
            white-space: nowrap;
            font-size: 13px;
        }

        .captcha-refresh:hover {
            background: #cce6d7;
        }

        /* TOMBOL MASUK */
        .login-button {
            width: 100%;
            padding: 15px;
            margin-top: 1px;
            border: none;
            border-radius: 9px;
            background: #176346;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .login-button:hover {
            background: #104c35;
        }

        .error {
            margin: -12px 0 16px;
            color: #c0392b;
            font-size: 13px;
        }

        .footer {
            margin-top: 27px;
            text-align: center;
            color: #8b95a7;
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 800px) {
            body {
                padding: 18px;
            }

            .login-container {
                grid-template-columns: 1fr;
                max-width: 480px;
            }

            .login-visual {
                min-height: 300px;
                padding: 35px 25px;
            }

            .login-visual h2 {
                font-size: 27px;
            }

            .login-form-area {
                padding: 35px 25px;
            }
        }

        @media (max-width: 400px) {
            .captcha-box {
                gap: 7px;
            }

            .captcha-question {
                font-size: 17px;
                letter-spacing: 3px;
            }

            .captcha-refresh {
                padding: 14px 9px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <main class="login-container">

        <!-- PANEL KIRI -->
        <section class="login-visual">
            <div class="visual-content">
                <h2>Selamat Datang di<br>Spotlight Alumni FST</h2>

                <div class="visual-divider">
                    <span></span>
                    <i>◆</i>
                    <span></span>
                </div>

                <p> Sistem Informasi Alumni Fakultas Sains dan Teknologi </p>
                <p> Universitas Islam Negeri Imam Bonjol Padang. </p>
                <p> Terhubung, berbagi informasi, dan membangun relasi bersama alumni.</p>
            </div>
        </section>

        <!-- PANEL KANAN -->
        <section class="login-form-area">
            <div class="login-card">

                <div class="brand-header">
                    <img
                        src="{{ asset('images/logo-fst.png') }}"
                        alt="Logo Fakultas Sains dan Teknologi"
                        class="logo"
                    >

                    <h1>Spotlight Alumni</h1>

                    <p class="subtitle">
                        Sistem Informasi Alumni<br>
                        Fakultas Sains dan Teknologi
                    </p>
                </div>

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf

                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >

                    @error('username')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    <label for="captcha_answer">Verifikasi CAPTCHA</label>

                    <div class="captcha-box">
                        <div class="captcha-question">
                            {{ $captchaQuestion }}
                        </div>

                        <button
                            type="button"
                            class="captcha-refresh"
                            onclick="window.location.href='{{ route('login') }}'"
                        >
                            Ganti Kode
                        </button>
                    </div>

                    <input
                        type="text"
                        id="captcha_answer"
                        name="captcha_answer"
                        placeholder="Ketik kode di atas"
                        maxlength="5"
                        minlength="5"
                        autocomplete="off"
                        autocapitalize="characters"
                        required
                    >

                    @error('captcha_answer')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="login-button">
                        Masuk
                    </button>
                </form>

                <div class="footer">
                    © {{ date('Y') }} Spotlight Alumni FST
                </div>

            </div>
        </section>

    </main>

</body>
</html>
