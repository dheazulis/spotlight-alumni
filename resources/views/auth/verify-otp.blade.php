
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email | Spotlight Alumni FST</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f5f7f3;
            color: #24324a;
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(44, 72, 120, .10);
        }

        h1 {
            margin: 0 0 12px;
            text-align: center;
            font-size: 25px;
        }

        p {
            color: #758198;
            font-size: 14px;
            line-height: 1.6;
        }

        .description {
            margin-bottom: 25px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 16px;
            border: 1px solid #dce9df;
            border-radius: 9px;
            font-size: 20px;
            letter-spacing: 6px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 9px;
            background: #1d6045;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1d6045;
        }

        .error {
            color: #c0392b;
            font-size: 13px;
        }

        .status {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 8px;
            background: #e8f5e9;
            color: #276738;
            font-size: 14px;
        }

        .resend {
            margin-top: 18px;
        }

        .resend button {
            background: #e4f0e8;
            color: #1d6045;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Verifikasi Email</h1>

        <p class="description">
            Masukkan kode OTP 6 digit yang dikirim ke email
            terdaftar untuk mengaktifkan akses profil alumni.
        </p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @error('otp')
            <p class="error">{{ $message }}</p>
        @enderror

        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf

            <label for="otp">Kode OTP</label>

            <input
                type="text"
                id="otp"
                name="otp"
                inputmode="numeric"
                pattern="[0-9]{6}"
                minlength="6"
                maxlength="6"
                autocomplete="one-time-code"
                placeholder="000000"
                required
            >

            <button type="submit">Verifikasi OTP</button>
        </form>

        <form class="resend" action="{{ route('otp.send') }}" method="POST">
            @csrf
            <button type="submit">Kirim OTP</button>
        </form>
    </main>
</body>
</html>
