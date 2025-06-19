<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // เพิ่มคอลัมน์หรือแก้ไขโครงสร้างตารางที่มีอยู่
        Schema::table('costume_images', function (Blueprint $table) {
            if (!Schema::hasColumn('costume_images', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('image_path');
            }
        });
    }

    public function down()
    {
        Schema::table('costume_images', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};