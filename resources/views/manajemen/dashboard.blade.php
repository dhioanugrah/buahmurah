@extends('layouts.app')

@section('title', 'Dashboard Manajemen')

@section('content')
@php
    $activeTab = request()->get('tab', 'cashflow');
@endphp

<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-brand-blue to-blue-900 text-white p-6 rounded-2xl shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="bg-brand-yellow text-brand-blueDark text-xs font-black uppercase px-2.5 py-1 rounded-md tracking-wider">Manajemen Portal</span>
            <h1 class="text-2xl md:text-3xl font-black mt-2">Pusat Laporan & Manajemen Toko</h1>
            <p class="text-xs md:text-sm text-blue-100 mt-1">Kelola arus kas, kulakan, gaji karyawan, stock opname, dan inventaris buah sesuai format Excel.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/20 text-right">
            <span class="block text-xs text-blue-200">Total Saldo Arus Kas</span>
            <span class="text-lg font-black text-brand-yellow">
                Rp {{ number_format($cashflows->last()?->saldo ?? 0, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
        <a href="{{ route('manajemen.dashboard', ['tab' => 'cashflow']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm {{ $activeTab == 'cashflow' ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
            📊 Cashflow (Arus Kas)
        </a>
        <a href="{{ route('manajemen.dashboard', ['tab' => 'kulakan']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm {{ $activeTab == 'kulakan' ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
            📦 Kulakan
        </a>
        <a href="{{ route('manajemen.dashboard', ['tab' => 'gaji']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm {{ $activeTab == 'gaji' ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
            👥 Gaji Karyawan
        </a>
        <a href="{{ route('manajemen.dashboard', ['tab' => 'opname']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm {{ $activeTab == 'opname' ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
            📋 Stock Opname
        </a>
        <a href="{{ route('manajemen.dashboard', ['tab' => 'buah']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-bold transition shadow-sm {{ $activeTab == 'buah' ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
            🍏 Stock Buah & Harga
        </a>
    </div>

    <!-- Tab Contents -->

    <!-- 1. Cashflow Tab -->
    @if($activeTab == 'cashflow')
    <div class="space-y-6">
        <!-- Add Cashflow Form -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-brand-blue text-white px-6 py-4">
                <h3 class="font-bold text-sm">➕ Tambah Arus Kas</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('manajemen.cashflow.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Keterangan</label>
                        <input type="text" name="keterangan" required placeholder="Keterangan transaksi" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Debit (Masuk)</label>
                        <input type="number" step="100" min="0" name="debit" value="0" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kredit (Keluar)</label>
                        <input type="number" step="100" min="0" name="kredit" value="0" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-5">
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Bukti Transaksi (Opsional)</label>
                        <input type="text" name="bukti_transaksi" placeholder="No. Nota / Keterangan Bukti" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-5">
                        <button type="submit" class="w-full bg-brand-yellow hover:bg-brand-yellowDark text-brand-blueDark font-extrabold py-2.5 rounded-lg text-sm shadow">
                            Simpan Arus Kas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cashflow Table -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b font-bold text-gray-800 text-sm">Tabel Arus Kas (Cashflow)</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nomor</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Debit</th>
                            <th class="px-4 py-3">Kredit</th>
                            <th class="px-4 py-3">Saldo</th>
                            <th class="px-4 py-3">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($cashflows as $item)
                        <tr>
                            <td class="px-4 py-3 font-bold text-brand-blue">{{ $item->nomor }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->keterangan }}</td>
                            <td class="px-4 py-3 text-emerald-600 font-bold">Rp {{ number_format($item->debit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-red-600 font-bold">Rp {{ number_format($item->kredit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-extrabold text-gray-900">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $item->bukti_transaksi ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-4 text-center text-gray-400">Belum ada data cashflow.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- 2. Kulakan Tab -->
    @if($activeTab == 'kulakan')
    <div class="space-y-6">
        <!-- Add Kulakan Form -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-brand-blue text-white px-6 py-4">
                <h3 class="font-bold text-sm">➕ Tambah Kulakan (Pembelian Stok)</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('manajemen.kulakan.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Keterangan</label>
                        <input type="text" name="keterangan" required placeholder="Contoh: Kulakan Jeruk 50Kg" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Debit</label>
                        <input type="number" step="100" min="0" name="debit" value="0" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kredit (Pembayaran Kulakan)</label>
                        <input type="number" step="100" min="0" name="kredit" value="0" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-5">
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Bukti Transaksi</label>
                        <input type="text" name="bukti_transaksi" placeholder="No. Nota Supplier" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-5">
                        <button type="submit" class="w-full bg-brand-yellow hover:bg-brand-yellowDark text-brand-blueDark font-extrabold py-2.5 rounded-lg text-sm shadow">
                            Simpan Data Kulakan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kulakan Table -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b font-bold text-gray-800 text-sm">Tabel Kulakan</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nomor</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Debit</th>
                            <th class="px-4 py-3">Kredit</th>
                            <th class="px-4 py-3">Saldo</th>
                            <th class="px-4 py-3">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kulakans as $item)
                        <tr>
                            <td class="px-4 py-3 font-bold text-brand-blue">{{ $item->nomor }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->keterangan }}</td>
                            <td class="px-4 py-3 text-emerald-600 font-bold">Rp {{ number_format($item->debit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-red-600 font-bold">Rp {{ number_format($item->kredit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-extrabold text-gray-900">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $item->bukti_transaksi ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-4 text-center text-gray-400">Belum ada data kulakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. Gaji Tab -->
    @if($activeTab == 'gaji')
    <div class="space-y-6">
        <!-- Add Salary Form -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-brand-blue text-white px-6 py-4">
                <h3 class="font-bold text-sm">➕ Catat Gaji Karyawan</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('manajemen.salary.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Bulan</label>
                        <input type="text" name="bulan" value="September 2026" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nama Pegawai</label>
                        <input type="text" name="employee_name" required placeholder="Nama Karyawan" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Absensi (Hari)</label>
                        <input type="number" min="0" name="absensi_total_hari" value="26" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kasbon (Rp)</label>
                        <input type="number" step="100" min="0" name="kasbon" value="0" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Total Gaji (Rp)</label>
                        <input type="number" step="100" min="0" name="total" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-5">
                        <button type="submit" class="w-full bg-brand-yellow hover:bg-brand-yellowDark text-brand-blueDark font-extrabold py-2.5 rounded-lg text-sm shadow">
                            Simpan Data Gaji
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Salary Table -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b font-bold text-gray-800 text-sm">Tabel Gaji Karyawan</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nomor</th>
                            <th class="px-4 py-3">Bulan</th>
                            <th class="px-4 py-3">Nama Pegawai</th>
                            <th class="px-4 py-3">Absensi (Hari)</th>
                            <th class="px-4 py-3">Kasbon</th>
                            <th class="px-4 py-3">Total Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($salaries as $item)
                        <tr>
                            <td class="px-4 py-3 font-bold text-brand-blue">{{ $item->nomor }}</td>
                            <td class="px-4 py-3">{{ $item->bulan }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->employee_name }}</td>
                            <td class="px-4 py-3">{{ $item->absensi_total_hari }} Hari</td>
                            <td class="px-4 py-3 text-red-600">Rp {{ number_format($item->kasbon, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-extrabold text-emerald-600">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400">Belum ada data gaji.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- 4. Stock Opname Tab -->
    @if($activeTab == 'opname')
    <div class="space-y-6">
        <!-- Add Stock Opname Form -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-brand-blue text-white px-6 py-4">
                <h3 class="font-bold text-sm">➕ Tambah Stock Opname</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('manajemen.opname.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kondisi</label>
                        <select name="kondisi" required class="w-full px-3 py-2 border rounded-lg text-sm bg-white">
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Keterangan / Catatan Opname</label>
                        <input type="text" name="keterangan" required placeholder="Contoh: Pemeriksaan stok mingguan" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-3">
                        <button type="submit" class="w-full bg-brand-yellow hover:bg-brand-yellowDark text-brand-blueDark font-extrabold py-2.5 rounded-lg text-sm shadow">
                            Simpan Stock Opname
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stock Opname Table -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b font-bold text-gray-800 text-sm">Tabel Stock Opname</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nomor</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($stockOpnames as $item)
                        <tr>
                            <td class="px-4 py-3 font-bold text-brand-blue">{{ $item->nomor }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->keterangan }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $item->kondisi == 'Baik' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->kondisi }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">Belum ada data stock opname.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- 5. Stock Buah & Harga Tab -->
    @if($activeTab == 'buah')
    <div class="space-y-6">
        <!-- Add Fruit Form -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-brand-blue text-white px-6 py-4">
                <h3 class="font-bold text-sm">🍏 Tambah Buah Baru</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('manajemen.fruit.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nama Buah</label>
                        <input type="text" name="name" required placeholder="Contoh: Durian" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Stok</label>
                        <input type="number" step="0.01" min="0" name="stock" required placeholder="50" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Satuan</label>
                        <input type="text" name="unit" value="Kg" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Harga Jual (Rp)</label>
                        <input type="number" step="100" min="0" name="price" required placeholder="45000" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4">
                        <button type="submit" class="w-full bg-brand-yellow hover:bg-brand-yellowDark text-brand-blueDark font-extrabold py-2.5 rounded-lg text-sm shadow">
                            Tambah Buah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Fruits List & Update Table -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b font-bold text-gray-800 text-sm">Daftar Stok Buah & Update Harga/Stok</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nama Buah</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3">Satuan</th>
                            <th class="px-4 py-3">Harga Jual</th>
                            <th class="px-4 py-3 text-right">Aksi Update</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($fruits as $fruit)
                        <tr>
                            <form action="{{ route('manajemen.fruit.update', $fruit->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td class="px-4 py-3">
                                    <input type="text" name="name" value="{{ $fruit->name }}" required class="w-full px-2 py-1 border rounded text-xs font-medium">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" name="stock" value="{{ $fruit->stock }}" required class="w-24 px-2 py-1 border rounded text-xs">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="unit" value="{{ $fruit->unit }}" required class="w-20 px-2 py-1 border rounded text-xs">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" step="100" name="price" value="{{ $fruit->price }}" required class="w-32 px-2 py-1 border rounded text-xs">
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="submit" class="bg-brand-blue hover:bg-brand-blueDark text-white px-3 py-1.5 rounded text-xs font-bold transition shadow">
                                        Perbarui
                                    </button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
