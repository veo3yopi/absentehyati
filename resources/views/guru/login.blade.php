<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Guru</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f7fb; margin: 0; }
        .wrap { max-width: 420px; margin: 80px auto; background: #fff; padding: 24px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,.08); }
        h1 { font-size: 20px; margin: 0 0 16px; }
        label { display: block; margin: 10px 0 6px; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #d7dbe6; border-radius: 6px; }
        button { width: 100%; margin-top: 16px; padding: 10px; border: 0; border-radius: 6px; background: #1f6feb; color: #fff; font-weight: 600; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 6px; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Login Guru</h1>
    <form method="post" action="{{ route('guru.login.submit') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')<div class="error">{{ $message }}</div>@enderror

        <label>Password</label>
        <input type="password" name="password" required>
        @error('password')<div class="error">{{ $message }}</div>@enderror

        <label style="display:flex; gap:8px; align-items:center; margin-top:10px;">
            <input type="checkbox" name="remember" value="1" style="width:auto;"> Ingat saya
        </label>

        <button type="submit">Masuk</button>
    </form>
</div>
</body>
</html>
