<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('selected_option_id')->nullable()->constrained('options')->onDelete('set null');
            $table->timestamps();

            $table->index('attempt_id');
            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
