<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration to store URLs
     */
    public function up(): void
    {
        Schema::create('url_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('original_url');
            $table->string('short_code', 8)->unique();
            $table->timestamps();
        });
    }
};
