<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use App\Models\Expense;
use App\Models\Fruit;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $fruits = Fruit::all();
        $sales = Sale::with('fruit')->where('user_id', Auth::id())->latest()->take(10)->get();
        $expenses = Expense::where('user_id', Auth::id())->latest()->take(10)->get();

        return view('kasir.dashboard', compact('fruits', 'sales', 'expenses'));
    }

    public function storeSale(Request $request)
    {
        $request->validate([
            'fruit_id' => ['required', 'exists:fruits,id'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:Cash,Transfer'],
        ]);

        $fruit = Fruit::findOrFail($request->fruit_id);

        if ($request->qty > $fruit->stock) {
            return back()->withErrors(['qty' => 'Stok buah tidak mencukupi. Stok saat ini: '.$fruit->stock.' '.$fruit->unit]);
        }

        $price = $fruit->price;
        $total_price = $request->qty * $price;

        // Decrease stock
        $fruit->stock -= $request->qty;
        $fruit->save();

        // Create sale
        Sale::create([
            'user_id' => Auth::id(),
            'fruit_id' => $fruit->id,
            'qty' => $request->qty,
            'unit' => $fruit->unit,
            'price' => $price,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
        ]);

        // Add to Cashflow
        $lastCash = Cashflow::latest()->first();
        $lastSaldo = $lastCash ? $lastCash->saldo : 0;
        $newSaldo = $lastSaldo + $total_price;

        $latestNomor = Cashflow::count() + 1;
        $nomor = 'CF-'.str_pad($latestNomor, 3, '0', STR_PAD_LEFT);

        Cashflow::create([
            'nomor' => $nomor,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Penjualan '.$fruit->name.' ('.$request->qty.' '.$fruit->unit.') - '.$request->payment_method,
            'debit' => $total_price,
            'kredit' => 0,
            'saldo' => $newSaldo,
        ]);

        return redirect()->route('kasir.dashboard')->with('success', 'Penjualan berhasil dicatat!');
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'nominal' => $request->nominal,
        ]);

        // Add to Cashflow as credit (pengeluaran)
        $lastCash = Cashflow::latest()->first();
        $lastSaldo = $lastCash ? $lastCash->saldo : 0;
        $newSaldo = $lastSaldo - $request->nominal;

        $latestNomor = Cashflow::count() + 1;
        $nomor = 'CF-'.str_pad($latestNomor, 3, '0', STR_PAD_LEFT);

        Cashflow::create([
            'nomor' => $nomor,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Pengeluaran Kasir: '.$request->description,
            'debit' => 0,
            'kredit' => $request->nominal,
            'saldo' => $newSaldo,
        ]);

        return redirect()->route('kasir.dashboard')->with('success', 'Pengeluaran berhasil dicatat!');
    }
}
