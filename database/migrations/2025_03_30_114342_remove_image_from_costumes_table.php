<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('costumes', function (Blueprint $table) {
            if (Schema::hasColumn('costumes', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    public function down()
    {
        Schema::table('costumes', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
        });
    }
};