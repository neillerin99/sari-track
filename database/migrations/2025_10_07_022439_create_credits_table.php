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
        Schema::create('credits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id')->nullable();
            $table->uuid('store_id')->nullable();
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->string('name');
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->dateTime('paid_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('SET NULL');
            $table->foreign('store_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
