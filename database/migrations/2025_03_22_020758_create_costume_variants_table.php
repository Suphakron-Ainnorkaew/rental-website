<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostumeVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('costume_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costume_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'color' หรือ 'size'
            $table->string('value'); // ค่า เช่น 'แดง', 'S'
            $table->integer('stock');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('costume_variants');
    }
}