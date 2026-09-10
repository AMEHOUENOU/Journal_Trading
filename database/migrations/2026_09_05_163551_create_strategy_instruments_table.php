<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategy_instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');                    // ex: GBP/JPY
            $table->string('timeframe');                  // ex: M5, M15, M10
            $table->enum('bias', ['achat', 'vente', 'neutre']);
            $table->decimal('target_rr', 5, 2);            // ex: 5.0
            $table->string('breakeven_type')->nullable();  // ex: BE(2), BE(sans), BE(1RR)
            $table->string('active_days')->nullable();     // ex: Lun-Mar-Jeu
            $table->string('inactive_months')->nullable(); // ex: Mai, Juin
            $table->decimal('backtest_rr_percent', 8, 2)->nullable(); // RR 20 ans, ex: 499.99
            $table->decimal('win_rate', 5, 2)->nullable();  // WR, ex: 33.01
            $table->decimal('drawdown', 5, 2)->nullable();  // DD, ex: 13.15
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategy_instruments');
    }
};