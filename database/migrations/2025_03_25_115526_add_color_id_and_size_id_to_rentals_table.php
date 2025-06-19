<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorIdAndSizeIdToRentalsTable extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable()->after('costume_id');
            $table->unsignedBigInteger('size_id')->nullable()->after('color_id');
            
            // เพิ่ม foreign key constraints (ถ้าต้องการ)
            $table->foreign('color_id')->references('id')->on('costume_variants')->onDelete('set null');
            $table->foreign('size_id')->references('id')->on('costume_variants')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);
            $table->dropColumn('color_id');
            $table->dropColumn('size_id');
        });
    }
}