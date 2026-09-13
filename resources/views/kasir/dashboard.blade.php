@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-brand-blue to-blue-800 text-white p-6 rounded-2xl shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="bg-brand-yellow text-brand-blueDark text-xs font-black uppercase px-2.5 py-1 rounded-md tracking-wider">Kasir Terminal</span>
            <h1 class="text-2xl md:text-3xl font-black mt-2">Pencatatan Penjualan & Operasional</h1>
            <p class="text-xs md:text-sm text-blue-100 mt-1">Input transaksi penjualan buah dengan cepat dan pantau stok secara real-time.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/20 text-right">
            <span class="block text-xs text-blue-200">Tanggal Hari Ini</span>
            <span class="text-base font-bold text-brand-yellow">{{ date('d F Y') }}</span>
        </div>
    </div>

    <!-- Main Grid: Forms and Real-time Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Forms Column (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Form Penjualan -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-brand-blue text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="font-bold text-base flex items-center space-x-2">
                        <span>🛒 Form Penjualan Buah</span>
                    </h2>
                    <span class="text-xs bg-brand-yellow text-brand-blueDark font-bold px-2 py-0.5 rounded">Kasir</span>
                </div>
                <div class="p-6">
                    <form action="{{ route('kasir.sale.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Pilih Buah</label>
                                <select name="fruit_id" id="fruit_select" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-brand-blue text-sm bg-white">
                                    <option value="" disabled selected>-- Pilih Buah --</option>
                                    @foreach($fruits as $fruit)
                                        <option value="{{ $fruit->id }}" data-price="{{ $fruit->price }}" data-stock="{{ $fruit->stock }}" data-unit="{{ $fruit->unit }}">
                                            {{ $fruit->name }} (Stok: {{ $fruit->stock }} {{ $fruit->unit }} - Rp {{ number_format($fruit->price, 0, ',', '.') }}/{{ $fruit->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Jumlah (Kg/Unit)</label>
                                <input type="number" step="0.01" min="0.01" name="qty" id="qty_input" required placeholder="Contoh: 2.5"
                                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-brand-blue text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Metode Pembayaran</label>
                                <select name="payment_method" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-brand-blue text-sm bg-white">
                                    <option value="Cash">Cash (Tunai)</option>
                                    <option value="Transfer">Transfer Bank</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Total Harga Estimasi</label>
                                <div id="total_price_display" class="w-full px-3 py-2 bg-gray-100 border rounded-lg text-sm font-bold text-brand-blue flex items-center">
                                    Rp 0
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-brand-yellow hover:bg-brand-yellowDark text-brand-blueDark font-extrabold py-2.5 rounded-lg transition shadow text-sm">
                            Simpan & Cetak Penjualan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form Pengeluaran Operasional Kasir -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-gray-800 text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="font-bold text-base flex items-center space-x-2">
                        <span>💸 Catat Pengeluaran Kasir</span>
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('kasir.expense.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Keterangan Pengeluaran</label>
                                <input type="text" name="description" required placeholder="Contoh: Beli kantong plastik / parkir"
                                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-gray-700 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nominal (Rp)</label>
                                <input type="number" step="100" min="0" name="nominal" required placeholder="Contoh: 25000"
                                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-gray-700 text-sm">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2.5 rounded-lg transition shadow text-sm">
                            Simpan Pengeluaran
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Real-time Stock Display Column (1 col) -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
            <div class="bg-brand-blue text-white px-6 py-4">
                <h2 class="font-bold text-base">📦 Stok Buah Real-Time</h2>
            </div>
            <div class="p-4 flex-grow overflow-y-auto max-h-[500px] space-y-3">
                @foreach($fruits as $fruit)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $fruit->name }}</h4>
                            <span class="text-xs text-gray-500">Rp {{ number_format($fruit->price, 0, ',', '.') }} / {{ $fruit->unit }}</span>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $fruit->stock > 10 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $fruit->stock }} {{ $fruit->unit }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Recent Sales & Expenses Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Sales -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-sm">Penjualan Terakhir Anda</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Buah</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Metode</th>
                            <th class="px-4 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sales as $sale)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $sale->fruit->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $sale->qty }} {{ $sale->unit }}</td>
                                <td class="px-4 py-3 font-bold text-emerald-600">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $sale->payment_method == 'Cash' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $sale->payment_method }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $sale->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-400">Belum ada penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-sm">Pengeluaran Kasir Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-100 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Nominal</th>
                            <th class="px-4 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $expense->description }}</td>
                                <td class="px-4 py-3 font-bold text-red-600">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $expense->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-400">Belum ada pengeluaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    const fruitSelect = document.getElementById('fruit_select');
    const qtyInput = document.getElementById('qty_input');
    const totalPriceDisplay = document.getElementById('total_price_display');

    function calculateTotal() {
        const selectedOption = fruitSelect.options[fruitSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const qty = parseFloat(qtyInput.value) || 0;
        const total = price * qty;
        totalPriceDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    fruitSelect.addEventListener('change', calculateTotal);
    qtyInput.addEventListener('input', calculateTotal);
</script>
@endsection
