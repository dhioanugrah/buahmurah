<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use App\Models\Fruit;
use App\Models\Kulakan;
use App\Models\Salary;
use App\Models\StockOpname;
use Illuminate\Http\Request;

class ManajemenController extends Controller
{
    public function index(Request $request)
    {
        $cashflows = Cashflow::latest()->get();
        $kulakans = Kulakan::latest()->get();
        $salaries = Salary::latest()->get();
        $stockOpnames = StockOpname::latest()->get();
        $fruits = Fruit::all();

        return view('manajemen.dashboard', compact('cashflows', 'kulakans', 'salaries', 'stockOpnames', 'fruits'));
    }

    public function storeCashflow(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'debit' => ['required', 'numeric', 'min:0'],
            'kredit' => ['required', 'numeric', 'min:0'],
            'bukti_transaksi' => ['nullable', 'string', 'max:255'],
        ]);

        $lastCash = Cashflow::latest()->first();
        $lastSaldo = $lastCash ? $lastCash->saldo : 0;
        $saldo = $lastSaldo + $request->debit - $request->kredit;

        $latestNomor = Cashflow::count() + 1;
        $nomor = 'CF-'.str_pad($latestNomor, 3, '0', STR_PAD_LEFT);

        Cashflow::create([
            'nomor' => $nomor,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'debit' => $request->debit,
            'kredit' => $request->kredit,
            'saldo' => $saldo,
            'bukti_transaksi' => $request->bukti_transaksi,
        ]);

        return redirect()->route('manajemen.dashboard', ['tab' => 'cashflow'])->with('success', 'Arus kas berhasil ditambahkan!');
    }

    public function storeKulakan(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'debit' => ['required', 'numeric', 'min:0'],
            'kredit' => ['required', 'numeric', 'min:0'],
            'bukti_transaksi' => ['nullable', 'string', 'max:255'],
        ]);

        $lastKulakan = Kulakan::latest()->first();
        $lastSaldo = $lastKulakan ? $lastKulakan->saldo : 0;
        $saldo = $lastSaldo + $request->debit - $request->kredit;

        $latestNomor = Kulakan::count() + 1;
        $nomor = 'KLK-'.str_pad($latestNomor, 3, '0', STR_PAD_LEFT);

        Kulakan::create([
            'nomor' => $nomor,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'debit' => $request->debit,
            'kredit' => $request->kredit,
            'saldo' => $saldo,
            'bukti_transaksi' => $request->bukti_transaksi,
        ]);

        // If there is kredit in kulakan (purchase), also record in Cashflow as pengeluaran
        if ($request->kredit > 0) {
            $lastCash = Cashflow::latest()->first();
            $lastCashSaldo = $lastCash ? $lastCash->saldo : 0;
            $newCashSaldo = $lastCashSaldo - $request->kredit;
            $latestCfNomor = Cashflow::count() + 1;
            Cashflow::create([
                'nomor' => 'CF-'.str_pad($latestCfNomor, 3, '0', STR_PAD_LEFT),
                'tanggal' => $request->tanggal,
                'keterangan' => 'Kulakan: '.$request->keterangan,
                'debit' => 0,
                'kredit' => $request->kredit,
                'saldo' => $newCashSaldo,
                'bukti_transaksi' => $request->bukti_transaksi,
            ]);
        }

        return redirect()->route('manajemen.dashboard', ['tab' => 'kulakan'])->with('success', 'Data kulakan berhasil ditambahkan!');
    }

    public function storeSalary(Request $request)
    {
        $request->validate([
            'bulan' => ['required', 'string'],
            'employee_name' => ['required', 'string'],
            'absensi_total_hari' => ['required', 'integer', 'min:0'],
            'kasbon' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $latestNomor = Salary::count() + 1;
        $nomor = 'SAL-'.str_pad($latestNomor, 3, '0', STR_PAD_LEFT);

        Salary::create([
            'nomor' => $nomor,
            'bulan' => $request->bulan,
            'employee_name' => $request->employee_name,
            'absensi_total_hari' => $request->absensi_total_hari,
            'kasbon' => $request->kasbon,
            'total' => $request->total,
        ]);

        // Also record salary payment as cashflow kredit if needed, or keep in salary tab
        $lastCash = Cashflow::latest()->first();
        $lastCashSaldo = $lastCash ? $lastCash->saldo : 0;
        $newCashSaldo = $lastCashSaldo - $request->total;
        $latestCfNomor = Cashflow::count() + 1;
        Cashflow::create([
            'nomor' => 'CF-'.str_pad($latestCfNomor, 3, '0', STR_PAD_LEFT),
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Pembayaran Gaji: '.$request->employee_name.' ('.$request->bulan.')',
            'debit' => 0,
            'kredit' => $request->total,
            'saldo' => $newCashSaldo,
        ]);

        return redirect()->route('manajemen.dashboard', ['tab' => 'gaji'])->with('success', 'Data gaji berhasil ditambahkan!');
    }

    public function storeStockOpname(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'kondisi' => ['required', 'in:Baik,Rusak'],
        ]);

        $latestNomor = StockOpname::count() + 1;
        $nomor = 'SO-'.str_pad($latestNomor, 3, '0', STR_PAD_LEFT);

        StockOpname::create([
            'nomor' => $nomor,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'kondisi' => $request->kondisi,
        ]);

        return redirect()->route('manajemen.dashboard', ['tab' => 'opname'])->with('success', 'Stock opname berhasil ditambahkan!');
    }

    public function storeFruit(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        Fruit::create($request->all());

        return redirect()->route('manajemen.dashboard', ['tab' => 'buah'])->with('success', 'Buah baru berhasil ditambahkan!');
    }

    public function updateFruit(Request $request, Fruit $fruit)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $fruit->update($request->all());

        return redirect()->route('manajemen.dashboard', ['tab' => 'buah'])->with('success', 'Data buah berhasil diperbarui!');
    }
}
