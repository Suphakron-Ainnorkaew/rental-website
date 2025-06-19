<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('costume_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costume_id')->constrained()->onDelete('cascade');
            $table->string('color');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('costume_colors');
    }
};