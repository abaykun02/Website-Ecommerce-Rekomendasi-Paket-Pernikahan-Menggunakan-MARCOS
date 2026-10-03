<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('customer_phone', 30)->nullable()->after('customer_name');
            $table->string('event_time', 50)->nullable()->after('event_date');
            $table->enum('payment_type', ['dp', 'full_payment'])->default('full_payment')->after('total_price');
            $table->decimal('dp_amount', 12, 2)->nullable()->after('payment_type');
            $table->integer('dp_percentage')->default(30)->nullable()->after('dp_amount');
            $table->text('notes')->nullable()->after('event_location');
            $table->string('snap_token')->nullable()->after('status');
            $table->string('midtrans_transaction_id')->nullable()->after('snap_token');
            $table->string('midtrans_status')->nullable()->after('midtrans_transaction_id');
            $table->foreignId('service_item_id')->nullable()->after('bundling_id')->constrained('services_items')->nullOnDelete();
        });

        // Make bundling_id nullable in orders if not already
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('bundling_id')->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('bank_name', 50)->nullable()->change();
            $table->string('account_name', 100)->nullable()->change();
            $table->string('proof_image')->nullable()->change();
            $table->decimal('amount', 12, 2)->nullable()->after('order_id');
            $table->string('payment_type', 30)->default('manual')->after('amount'); // 'dp', 'full_payment', 'midtrans'
            $table->string('snap_token')->nullable()->after('payment_type');
            $table->string('transaction_id')->nullable()->after('snap_token');
            $table->text('midtrans_response')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount', 'payment_type', 'snap_token', 'transaction_id', 'midtrans_response']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['service_item_id']);
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'event_time',
                'payment_type',
                'dp_amount',
                'dp_percentage',
                'notes',
                'snap_token',
                'midtrans_transaction_id',
                'midtrans_status',
                'service_item_id'
            ]);
        });
    }
};
