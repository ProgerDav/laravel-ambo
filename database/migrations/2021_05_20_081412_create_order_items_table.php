<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class);
            $table->foreign('order_id')->references('id')->on('orders');

            $table->string('product_title');
            $table->decimal('price');
            $table->unsignedTinyInteger('quantity');
            $table->decimal('admin_revenue');
            $table->decimal('ambassador_revenue');

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
        Schema::dropIfExists('order_items');
    }
}
