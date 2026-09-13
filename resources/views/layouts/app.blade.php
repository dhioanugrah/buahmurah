<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>buahmurah.id - @yield('title', 'Sistem Manajemen Toko Buah')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#1e3a8a',
                            blueDark: '#172554',
                            yellow: '#facc15',
                            yellowDark: '#eab308',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans">

    <!-- Navbar -->
    <nav class="bg-brand-blue text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <a href="#" class="flex items-center space-x-2">
                        <span class="bg-brand-yellow text-brand-blueDark font-black text-xl px-3 py-1 rounded-lg shadow">BM</span>
                        <span class="font-bold text-xl tracking-wide">buahmurah<span class="text-brand-yellow">.id</span></span>
                    </a>
                </div>

                @auth
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                        <span class="text-xs uppercase bg-brand-yellow text-brand-blueDark font-bold px-2 py-0.5 rounded inline-block w-max ml-auto">
                            {{ Auth::user()->role }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition shadow">
                            Keluar
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <div class="text-emerald-700 font-medium text-sm">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="text-red-700 font-medium text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-brand-blueDark text-gray-300 py-4 text-center text-xs border-t border-blue-900">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} <span class="font-bold text-white">buahmurah.id</span>. Hak Cipta Dilindungi. Tema Biru & Kuning.
        </div>
    </footer>

</body>
</html>
