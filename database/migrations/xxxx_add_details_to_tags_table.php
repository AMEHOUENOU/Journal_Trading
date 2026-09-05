<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->text('entry_rules')->nullable();
            $table->text('exit_rules')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['description', 'entry_rules', 'exit_rules']);
        });
    }
};
