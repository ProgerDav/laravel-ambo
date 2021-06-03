<?php

use App\Models\Link;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(Link::class);

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_product');
    }
}
