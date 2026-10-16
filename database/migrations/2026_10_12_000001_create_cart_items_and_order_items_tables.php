<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table Cart Items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('bundling_id')->nullable()->constrained('bundlings')->nullOnDelete();
            $table->foreignId('service_item_id')->nullable()->constrained('services_items')->nullOnDelete();
            $table->string('item_type', 30)->default('bundling'); // 'bundling' or 'service'
            $table->string('item_name')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Table Order Items for multi-item checkout
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('bundling_id')->nullable()->constrained('bundlings')->nullOnDelete();
            $table->foreignId('service_item_id')->nullable()->constrained('services_items')->nullOnDelete();
            $table->string('item_type', 30)->default('bundling'); // 'bundling' or 'service'
            $table->string('item_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Make vendor_id and bundling_id nullable in orders table for multi-item / multi-vendor support
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->change();
            $table->foreignId('bundling_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('cart_items');
    }
};
