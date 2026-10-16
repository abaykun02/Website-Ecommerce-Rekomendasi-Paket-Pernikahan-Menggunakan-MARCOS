<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                if (!Schema::hasColumn('cart_items', 'event_date')) {
                    $table->date('event_date')->nullable()->after('quantity');
                }
                if (!Schema::hasColumn('cart_items', 'event_location')) {
                    $table->string('event_location')->nullable()->after('event_date');
                }
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'event_date')) {
                    $table->date('event_date')->nullable()->after('subtotal');
                }
                if (!Schema::hasColumn('order_items', 'event_location')) {
                    $table->string('event_location')->nullable()->after('event_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropColumn(['event_date', 'event_location']);
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn(['event_date', 'event_location']);
            });
        }
    }
};
