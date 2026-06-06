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
        Schema::table('carts', function (Blueprint $table) {
            $table->string('destination_province')->nullable()->after('user_id');
            $table->string('destination_city')->nullable()->after('destination_province');
            $table->string('destination_district')->nullable()->after('destination_city');
            $table->string('destination_subdistrict')->nullable()->after('destination_district');
            $table->string('destination_subdistrict_id')->nullable()->after('destination_subdistrict');
            $table->integer('shipping_cost')->default(0)->after('destination_subdistrict_id');
            $table->string('courier')->default('jne')->after('shipping_cost');
            $table->string('courier_service')->nullable()->after('courier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn([
                'destination_province',
                'destination_city',
                'destination_district',
                'destination_subdistrict',
                'destination_subdistrict_id',
                'shipping_cost',
                'courier',
                'courier_service',
            ]);
        });
    }
};
