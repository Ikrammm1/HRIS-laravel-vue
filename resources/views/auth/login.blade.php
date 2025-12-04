<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .container { max-width: 420px; margin: 0 auto; }
        label { display:block; margin-top: .5rem; }
        input { width:100%; padding:.5rem; margin-top:.25rem; }
        button { margin-top:1rem; padding:.6rem 1rem; }
        .error { color: #b22; margin-top:.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <label style="margin-top:.5rem"><input type="checkbox" name="remember"> Remember me</label>

            <button type="submit">Sign in</button>
        </form>
    </div>
</body>
</html>
