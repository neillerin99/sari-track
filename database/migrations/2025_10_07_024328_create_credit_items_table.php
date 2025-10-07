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
        Schema::create('credit_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('credit_id')->nullable();
            $table->uuid('item_id')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('credit_id')->references('id')->on('credits')->onDelete('SET NULL');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_items');
    }
};
