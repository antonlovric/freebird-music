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
        Schema::table('order_details', function (Blueprint $table) {
            $table->drop();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            Schema::create('order_details', function (Blueprint $table) {
                $table->id();
                $table->string("billing_address", 100);
                $table->string("shipping_address", 100);
                $table->string("phone_number", 20);
                $table->string("email", 20);
                $table->string("city", 20);
                $table->string("country", 20);
                $table->string("postal_code", 20);
                $table->foreignId("user_id")->nullable()->constrained("users")->onDelete("cascade");
                $table->timestamps();
            });
        });
    }
};
