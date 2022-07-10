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
        Schema::table('products', function (Blueprint $table) {
            $table->string("title", 50);
            $table->string("description", 150);
            $table->string("media_condition", 20);
            $table->string("sleeve_condition", 50);
            $table->string("sku", 50);
            $table->integer("price");
            $table->decimal("rating", 6, 2);
            $table->integer("number_of_ratings");
            $table->foreignId("product_type")->constrained()->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
