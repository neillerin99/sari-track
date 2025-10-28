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
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('store_id')->nullable();
            $table->string('name');
            $table->string('lastname');
            $table->string('number');
            $table->text('notes')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
