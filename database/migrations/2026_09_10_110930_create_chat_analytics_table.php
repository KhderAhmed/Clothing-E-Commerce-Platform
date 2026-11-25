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
        Schema::create('chat_analytics', function (Blueprint $table) {
            $table->id();
          $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->string('intent')->index();
            $table->text('user_message');
            $table->text('bot_reply');
            $table->boolean('understood')->default(true);
            $table->integer('response_time_ms')->nullable();
            $table->json('entities')->nullable();
            $table->timestamps();

            $table->index(['intent', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_analytics');
    }
};
