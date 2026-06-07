<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('url_history', function (Blueprint $table) {
            $table->integer('timeout')->default(0)->after('short_code');
        });   
    }
};
