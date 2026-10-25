<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Simple POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
    <div class="w-full max-w-sm bg-white rounded-md shadow p-6">
        <h1 class="text-lg font-semibold mb-4">
            Masuk ke Simple POS
        </h1>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium mb-1">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border rounded-md px-3 py-2 text-sm"
                >

                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">
                    Kata Sandi
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="w-full border rounded-md px-3 py-2 text-sm"
                >

                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-slate-900 text-white rounded-md py-2 text-sm hover:bg-slate-700"
            >
                Masuk
            </button>
        </form>

        <p class="mt-6 text-xs text-slate-500">
            Akun demo: admin@pos.test atau kasir@pos.test,
            kata sandi <code>password</code>.
        </p>
    </div>
</body>
</html>