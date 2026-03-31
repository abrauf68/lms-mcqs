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
        Schema::create('user_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->integer('question_order')->default(0);
            $table->text('answer_text')->nullable(); // fill blank
            $table->integer('selected_option_id')->nullable(); // for single correct option
            $table->json('selected_options')->nullable(); // for multiple correct options
            $table->json('matched_pairs')->nullable(); // matching
            $table->json('hotspot')->nullable(); // x,y for hotspot
            $table->enum('is_marked', ['1', '0'])->default('0');
            $table->enum('is_answered', ['1', '0'])->default('0');
            $table->enum('is_correct', ['1', '0'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_exam_answers');
    }
};
