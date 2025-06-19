<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('status', ['active', 'completed'])->default('active')->change();
        });
    }
};