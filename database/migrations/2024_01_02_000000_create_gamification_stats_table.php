<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamification_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->default(1);
            $table->integer('current_xp')->default(0);
            $table->integer('next_level_xp')->default(100);
            $table->timestamps();
        });

        // Insert default row
        DB::table('gamification_stats')->insert([
            'level' => 1,
            'current_xp' => 0,
            'next_level_xp' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('gamification_stats');
    }
};
