<?php

namespace Database\Seeders;

use App\Models\Cashflow;
use App\Models\Expense;
use App\Models\Fruit;
use App\Models\Kulakan;
use App\Models\Salary;
use App\Models\StockOpname;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        $kasir = User::create([
            'name' => 'Kasir Buah Murah',
            'email' => 'kasir@buahmurah.id',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $manajemen = User::create([
            'name' => 'Manajemen Buah Murah',
            'email' => 'manajemen@buahmurah.id',
            'password' => Hash::make('password'),
            'role' => 'manajemen',
        ]);

        // Seed Fruits
        $fruits = [
            ['name' => 'Jeruk', 'stock' => 100, 'unit' => 'Kg', 'price' => 15000],
            ['name' => 'Apel', 'stock' => 80, 'unit' => 'Kg', 'price' => 25000],
            ['name' => 'Mangga', 'stock' => 75, 'unit' => 'Kg', 'price' => 20000],
            ['name' => 'Pisang', 'stock' => 120, 'unit' => 'Kg', 'price' => 12000],
            ['name' => 'Semangka', 'stock' => 50, 'unit' => 'Kg', 'price' => 10000],
            ['name' => 'Alpukat', 'stock' => 60, 'unit' => 'Kg', 'price' => 30000],
        ];

        foreach ($fruits as $fruit) {
            Fruit::create($fruit);
        }

        // Seed Cashflow
        Cashflow::create([
            'nomor' => 'CF-001',
            'tanggal' => now()->subDays(2)->toDateString(),
            'keterangan' => 'Modal awal toko buah',
            'debit' => 10000000,
            'kredit' => 0,
            'saldo' => 10000000,
            'bukti_transaksi' => null,
        ]);
        Cashflow::create([
            'nomor' => 'CF-002',
            'tanggal' => now()->subDay()->toDateString(),
            'keterangan' => 'Penjualan harian kasir',
            'debit' => 1500000,
            'kredit' => 0,
            'saldo' => 11500000,
            'bukti_transaksi' => null,
        ]);

        // Seed Kulakan
        Kulakan::create([
            'nomor' => 'KLK-001',
            'tanggal' => now()->subDays(3)->toDateString(),
            'keterangan' => 'Pembelian stok buah dari petani lokal',
            'debit' => 0,
            'kredit' => 4500000,
            'saldo' => 5500000,
            'bukti_transaksi' => null,
        ]);

        // Seed Salaries
        Salary::create([
            'nomor' => 'SAL-001',
            'bulan' => 'Agustus 2026',
            'employee_name' => 'Budi Santoso',
            'absensi_total_hari' => 26,
            'kasbon' => 200000,
            'total' => 3800000,
        ]);
        Salary::create([
            'nomor' => 'SAL-002',
            'bulan' => 'Agustus 2026',
            'employee_name' => 'Siti Aminah',
            'absensi_total_hari' => 25,
            'kasbon' => 100000,
            'total' => 3900000,
        ]);

        // Seed Stock Opnames
        StockOpname::create([
            'nomor' => 'SO-001',
            'tanggal' => now()->subDays(1)->toDateString(),
            'keterangan' => 'Pemeriksaan rutin buah segar',
            'kondisi' => 'Baik',
        ]);
        StockOpname::create([
            'nomor' => 'SO-002',
            'tanggal' => now()->subDays(1)->toDateString(),
            'keterangan' => 'Jeruk ada yang busuk sedikit',
            'kondisi' => 'Rusak',
        ]);

        // Seed Expenses
        Expense::create([
            'user_id' => $kasir->id,
            'description' => 'Beli plastik dan karet gelang',
            'nominal' => 50000,
        ]);
    }
}
