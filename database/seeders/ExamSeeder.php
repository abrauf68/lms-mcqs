<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exam seeding
        $exams = [
            [
                'product_id' => 1,
                'name' => 'Demo Exam',
                'slug' => 'demo-exam',
                'description' => 'A demo exam for testing purposes.',
                'is_demo' => '1',
                'is_active' => 'active',
            ],
        ];

        foreach ($exams as $exam) {
            Exam::create($exam);
        }

        // Exam Question seeding
        $examQuestions = [
            [
                'exam_id' => 1,
                'question_id' => 1,
                'question_order' => 1,
            ],
            [
                'exam_id' => 1,
                'question_id' => 2,
                'question_order' => 2,
            ],
            [
                'exam_id' => 1,
                'question_id' => 3,
                'question_order' => 3,
            ],
            [
                'exam_id' => 1,
                'question_id' => 4,
                'question_order' => 4,
            ],
            [
                'exam_id' => 1,
                'question_id' => 5,
                'question_order' => 5,
            ],
        ];

        foreach ($examQuestions as $examQuestion) {
            ExamQuestion::create($examQuestion);
        }
    }
}
