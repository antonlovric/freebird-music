<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn("city", "billing_city");
            $table->renameColumn("country", "billing_country");
            $table->renameColumn("zipcode", "billing_zipcode");
            $table->string("shipping_city", 40);
            $table->string("shipping_country", 40);
            $table->string("shipping_zipcode", 40);
            $table->renameColumn("order_status", "order_status_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn("billing_city", "city");
            $table->renameColumn("billing_country", "country");
            $table->renameColumn("billing_zipcode", "zipcode");
            $table->dropColumn("shipping_city");
            $table->dropColumn("shipping_country");
            $table->dropColumn("shipping_zipcode");
            $table->renameColumn("order_status_id", "order_status");
        });
    }
};
