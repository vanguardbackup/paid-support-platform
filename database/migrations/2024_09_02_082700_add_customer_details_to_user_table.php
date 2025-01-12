<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->integer('purchased_support_time')->default(0);
            $table->timestamp('last_support_time_purchase')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'billing_address',
                'billing_city',
                'billing_state',
                'billing_country',
                'billing_zip_code',
                'credit_card_expiration',
                'purchased_support_time',
                'last_support_time_purchase',
            ]);
        });
    }
};
