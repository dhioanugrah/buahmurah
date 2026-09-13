@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-[75vh]">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-brand-blue p-6 text-center text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-yellow/10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
            <span class="bg-brand-yellow text-brand-blueDark font-black text-2xl px-3 py-1 rounded-xl shadow inline-block mb-2">BM</span>
            <h2 class="text-2xl font-bold tracking-tight">buahmurah<span class="text-brand-yellow">.id</span></h2>
            <p class="text-xs text-blue-200 mt-1">Silakan masuk sesuai peran Anda (Kasir / Manajemen)</p>
        </div>

        <div class="p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-brand-blue outline-none transition text-sm">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-brand-blue outline-none transition text-sm">
                </div>

                <button type="submit" class="w-full bg-brand-blue hover:bg-brand-blueDark text-white font-bold py-3 rounded-lg transition shadow-md flex items-center justify-center space-x-2 text-sm">
                    <span>Masuk Aplikasi</span>
                </button>
            </form>

            <div class="mt-6 border-t pt-4 text-xs text-gray-500 space-y-1">
                <p class="font-bold text-gray-700">Akun Default Demo:</p>
                <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                    <span>Kasir: kasir@buahmurah.id</span>
                    <button type="button" onclick="document.getElementById('email').value='kasir@buahmurah.id';document.getElementById('password').value='password';" class="text-brand-blue font-semibold hover:underline">Gunakan</button>
                </div>
                <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                    <span>Manajemen: manajemen@buahmurah.id</span>
                    <button type="button" onclick="document.getElementById('email').value='manajemen@buahmurah.id';document.getElementById('password').value='password';" class="text-brand-blue font-semibold hover:underline">Gunakan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
