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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cart_id")->nullable()->constrained("carts")->onDelete("cascade");
            $table->foreignId("product_id")->nullable()->constrained("products")->onDelete("cascade");
            $table->foreignId("order_id")->nullable()->constrained("orders")->onDelete("cascade");
            $table->integer("quantity");
            $table->decimal("price", 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
