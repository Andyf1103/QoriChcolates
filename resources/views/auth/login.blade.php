<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Qori Chocolates</title>
    <link rel="icon" href="{{ asset('tailadmin/assets/favicon.ico') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Arial, sans-serif; color: #2a2019; background: #f5f1ea; }
        .login-page { min-height: 100vh; display: grid; place-items: center; padding: 24px; background: radial-gradient(circle at top, #fff 0, #f5f1ea 65%); }
        .login-card { width: min(440px, 100%); border: 1px solid #ded4c6; border-radius: 18px; background: #fff; box-shadow: 0 18px 50px rgba(69,40,22,.12); padding: 36px; }
        .login-brand { display: flex; justify-content: center; margin-bottom: 18px; }
        .login-brand img { width: 92px; height: 92px; object-fit: contain; }
        .login-card h1 { margin: 0 0 8px; text-align: center; font-size: 28px; color: #35170d; }
        .login-card .subtitle { margin: 0 0 28px; text-align: center; color: #75675c; font-size: 14px; }
        .field { display: block; margin-top: 18px; font-size: 14px; font-weight: 700; color: #35170d; }
        .field input { width: 100%; height: 52px; margin-top: 8px; border: 1px solid #cfc3b4; border-radius: 10px; background: #fff; padding: 0 14px; color: #24180f; font-size: 16px; outline: none; }
        .field input:focus { border-color: #7a4727; box-shadow: 0 0 0 4px rgba(122,71,39,.12); }
        .login-errors { margin: 0 0 18px; border: 1px solid #e5a6a6; border-radius: 10px; background: #fff1f1; color: #9f1d1d; padding: 12px 14px; font-size: 14px; }
        .login-submit { width: 100%; min-height: 58px; margin-top: 24px; border: 2px solid #35170d; border-radius: 12px; background: #5c2600; color: #fff; font-size: 18px; font-weight: 800; cursor: pointer; box-shadow: 0 12px 24px rgba(92,38,0,.24); }
        .login-submit:hover { background: #451c00; }
        .login-submit:focus-visible { outline: 4px solid rgba(168,90,24,.35); outline-offset: 3px; }
        .remember { display: flex; align-items: center; gap: 9px; margin-top: 18px; color: #5f554c; font-size: 14px; }
        .remember input { width: 18px; height: 18px; accent-color: #5c2600; }
        .back-link { display: block; margin-top: 22px; text-align: center; color: #7a4727; font-size: 14px; font-weight: 700; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        @media (max-width: 520px) { .login-page { padding: 16px; } .login-card { padding: 28px 20px; } .login-brand img { width: 76px; height: 76px; } }
    </style>
</head>
<body>
    <main class="login-page">
        <form method="POST" action="{{ route('login.store') }}" class="login-card">
            @csrf
            <div class="login-brand"><img src="{{ asset('images/logoqori.png') }}" alt="Qori Chocolates"></div>
            <h1>Acceso administrativo</h1>
            <p class="subtitle">Ingresa tus datos para administrar la tienda</p>

            @if ($errors->any())
                <div class="login-errors" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <label class="field">
                Correo electrónico
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </label>

            <label class="field">
                Contraseña
                <input type="password" name="password" required autocomplete="current-password">
            </label>

            <button type="submit" class="login-submit">Iniciar sesión</button>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Mantener sesión iniciada
            </label>

            <a class="back-link" href="{{ route('public.home') }}">Volver al sitio principal</a>
        </form>
    </main>
</body>
</html>
