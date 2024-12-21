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
        Schema::create('house_keeper_hourly_orders', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('location');
            $table->date('date');
            $table->foreignId('user_id')
                ->constrained('app_users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_keeper_hourly_orders');
    }
};
