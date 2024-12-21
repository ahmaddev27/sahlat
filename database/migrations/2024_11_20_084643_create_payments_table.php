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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->double('value');
            $table->integer('status')->default(0);
            $table->string('type');

            $table->foreignId('user_id')
                ->constrained('app_users')
                ->onDelete('cascade');


            $table->foreignId('assurance_order_id')->nullable()
                ->constrained('assurance_orders')
                ->onDelete('cascade');

            $table->foreignId('violation_id')->nullable()->constrained('violations')
                ->onDelete('cascade');

            $table->foreignId('house_keeper_order_id')->nullable()->constrained('house_keeper_orders')
                ->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
