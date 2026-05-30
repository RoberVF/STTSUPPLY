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
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('league');
                $table->string('category');
                $table->string('team')->nullable();
                $table->text('description')->nullable();
                $table->string('provider_url')->unique();
                $table->decimal('provider_price', 8, 2);
                $table->decimal('selling_price', 8, 2);
                $table->json('images')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
