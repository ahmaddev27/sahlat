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
        Schema::create('assurance_orders', function (Blueprint $table) {
            $table->id();

            $table->string('n_id');


            $table->foreignId('assurance_id')->constrained('assurances')
                ->onDelete('cascade');

            $table->foreignId('user_id')->constrained('app_users')
                ->onDelete('cascade');

            $table->string('details');


            $table->integer('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assurance_orders');
    }
};