<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bottle_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bottle_id')->nullable();
            $table->uuid('item_id')->nullable();
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bottle_id')->references('id')->on('bottles')->onDelete('SET NULL');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bottle_items');
    }
};
