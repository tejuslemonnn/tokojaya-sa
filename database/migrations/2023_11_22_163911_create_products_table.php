<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('satuan_id');
            $table->foreign('satuan_id')->references('id')->on('satuans')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('foto')->nullable();
            $table->bigInteger('kode');
            $table->string('nama_produk');
            $table->integer('harga');
            $table->float('stok');
            $table->tinyInteger('diskon')->default(0);
            $table->float('terjual')->default(0);
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
        Schema::dropIfExists('products');
    }
}
