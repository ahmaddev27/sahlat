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
        Schema::create('orders_attachments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('assurance_order_id')
                ->constrained('assurance_orders')
                ->onDelete('cascade');

            $table->foreignId('violation_id')
                ->constrained('violations')
                ->onDelete('cascade');

            $table->foreignId('house_keeper_order_id')
                ->constrained('house_keeper_orders')
                ->onDelete('cascade');

            $table->string('file');
            $table->string('type');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_attachments');
    }
};
