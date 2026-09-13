<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fruit_id')->constrained()->cascadeOnDelete();
            $table->decimal('qty', 10, 2);
            $table->string('unit')->default('Kg');
            $table->decimal('price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['Cash', 'Transfer'])->default('Cash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
