<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bundlings', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->after('id')->index();
            $table->json('features')->nullable()->after('description');
            $table->decimal('extra_fee', 12, 2)->default(0)->after('base_price');
        });
    }

    public function down(): void
    {
        Schema::table('bundlings', function (Blueprint $table) {
            $table->dropColumn(['code', 'features', 'extra_fee']);
        });
    }
};
