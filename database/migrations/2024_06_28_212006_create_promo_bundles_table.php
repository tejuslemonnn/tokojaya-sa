<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bundel');
            $table->longText('deskripsi')->nullable();
            $table->dateTime('mulai_berlaku');
            $table->dateTime('selesai_berlaku');
            $table->bigInteger('kode_barcode')->unique();
            $table->integer('harga_asli');
            $table->integer('harga_promo');
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
        Schema::dropIfExists('promo_bundles');
    }
}
