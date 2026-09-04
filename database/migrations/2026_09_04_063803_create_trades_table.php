<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->enum('direction', ['long', 'short']);
            $table->decimal('entry_price', 15, 5);
            $table->decimal('exit_price', 15, 5)->nullable();
            $table->decimal('stop_loss', 15, 5)->nullable();
            $table->decimal('take_profit', 15, 5)->nullable();
            $table->decimal('position_size', 15, 5);
            $table->decimal('pnl', 15, 2)->nullable();
            $table->decimal('risk_reward', 8, 2)->nullable();
            $table->enum('close_status', ['sl_hit', 'tp_hit', 'manual', 'breakeven', 'open'])->default('open');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};