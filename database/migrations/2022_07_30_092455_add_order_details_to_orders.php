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
            $table->string("billing_address");
            $table->string("shipping_address");
            $table->string("phone", 40);
            $table->string("email", 40);
            $table->string("city", 40);
            $table->string("country", 40);
            $table->string("zipcode", 10);
            $table->foreignId("user_id")->constrained("users")->nullable()->onDelete("cascade");
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
            $table->dropColumn("billing_address");
            $table->dropColumn("shipping_address");
            $table->dropColumn("phone", 40);
            $table->dropColumn("email", 40);
            $table->dropColumn("city", 40);
            $table->dropColumn("country", 40);
            $table->dropColumn("zipcode", 10);
            $table->dropConstrainedForeignId(["user_id"]);
        });
    }
};
