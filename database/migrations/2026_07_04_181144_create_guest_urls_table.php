<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guest_urls', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->text('original_url');
            $table->string('short_code', 8)->unique();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_urls');
    }
};
