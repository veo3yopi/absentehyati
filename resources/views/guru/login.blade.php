<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Guru</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap');

        :root {
            --bg-0: #f1f5f9;
            --bg-1: #ffffff;
            --ink-1: #0f172a;
            --ink-2: #334155;
            --muted: #64748b;
            --line: #e2e8f0;
            --brand-1: #16a34a;
            --brand-2: #22c55e;
            --shadow-2: 0 16px 30px rgba(15, 23, 42, 0.12);
            --radius-1: 16px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(1200px 400px at 10% -10%, rgba(34, 197, 94, 0.18), transparent 60%),
                radial-gradient(900px 360px at 90% 0%, rgba(16, 185, 129, 0.18), transparent 60%),
                var(--bg-0);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-1);
            padding: 24px 16px;
        }

        .wrap {
            width: min(420px, 100%);
            background: var(--bg-1);
            padding: 26px 26px 24px;
            border-radius: var(--radius-1);
            box-shadow: var(--shadow-2);
            border: 1px solid #edf2f7;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px 8px 18px 8px;
            background: linear-gradient(135deg, #22c55e, #10b981);
            transform: rotate(8deg);
        }
        .brand-text {
            font-weight: 800;
            font-size: 18px;
            letter-spacing: -0.2px;
        }
        .subtitle {
            font-size: 13px;
            color: var(--muted);
            margin: 0 0 18px;
        }

        h1 { font-size: 20px; margin: 0 0 4px; }
        label { display: block; margin: 12px 0 6px; font-size: 13px; color: var(--ink-2); }
        input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
        }
        input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }
        button {
            width: 100%;
            margin-top: 18px;
            padding: 11px 12px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-1), var(--brand-2));
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 12px 18px rgba(34, 197, 94, 0.25);
        }
        .hint {
            margin-top: 12px;
            font-size: 12px;
            color: var(--muted);
            text-align: center;
        }
        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            font-size: 13px;
            color: var(--ink-2);
        }
        .remember input { width: auto; }
    </style>
</head>
<body>
<div class="wrap">
    @php
        $logoUrl = $schoolSetting?->getFirstMediaUrl('hero_logo');
    @endphp
    <div class="brand">
        @if (!empty($logoUrl))
            <img src="{{ $logoUrl }}" alt="Logo sekolah" class="brand-logo">
        @else
            <span class="brand-mark" aria-hidden="true"></span>
        @endif
        <div class="brand-text">{{ $schoolSetting?->school_name ?? 'Absensi Guru' }}</div>
    </div>
    <h1>Login Guru</h1>
    <p class="subtitle">Silakan masuk untuk absen dan melihat riwayat kehadiran.</p>
    <form method="post" action="{{ route('guru.login.submit') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
        @error('email')<div class="error">{{ $message }}</div>@enderror

        <label>Password</label>
        <input type="password" name="password" required placeholder="Masukkan kata sandi">
        @error('password')<div class="error">{{ $message }}</div>@enderror

        <label class="remember">
            <input type="checkbox" name="remember" value="1"> Ingat saya
        </label>

        <button type="submit">Masuk</button>
    </form>
    <div class="hint">Butuh bantuan? Hubungi admin sekolah.</div>
</div>
</body>
</html>
