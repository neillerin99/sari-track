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
        Schema::create('restock_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('restock_id')->nullable();
            $table->uuid('item_id')->nullable();
            $table->string('name');
            $table->integer('quantity');
            $table->boolean('checked')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('restock_id')->references('id')->on('restocks')->onDelete('SET NULL');
            $table->foreign('item_id')->references('id')->on('stores')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_items');
    }
};
