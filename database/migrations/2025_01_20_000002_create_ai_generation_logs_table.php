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
        Schema::create('ai_generation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_topic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider');
            $table->string('model');
            $table->string('status')->default('pending');
            $table->text('prompt')->nullable();
            $table->string('generated_title')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->decimal('cost', 10, 6)->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('generation_time_ms')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('provider');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_generation_logs');
    }
};
