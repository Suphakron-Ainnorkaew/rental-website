<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->text('cancel_reason')->nullable()->after('status');
            $table->softDeletes(); // เพิ่ม Soft Deletes ถ้ายังไม่มี
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('cancel_reason');
            $table->dropSoftDeletes();
        });
    }
};