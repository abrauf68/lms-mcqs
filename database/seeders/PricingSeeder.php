<?php

namespace Database\Seeders;

use App\Models\Pricing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pricing seeding
        $pricings = [
            [
                'name' => 'Essentials',
                'slug' => Str::slug('Essentials'),
                'price' => 89.00,
                'description' => 'Everything you need for self-guided study and practice.',
                'type' => 'monthly',
                'duration' => '1',
                'features' => json_encode(['Rita Mulcahy’s PMP® Exam Prep Book', 'PM FASTrack® Exam Simulator', 'Free shipping in the US (except Alaska and Hawaii)']),
                'tag' => 'Popular',
                'is_active' => 'active',
            ],
            [
                'name' => 'Plus',
                'slug' => Str::slug('Plus'),
                'price' => 237.00,
                'description' => 'Comprehensive exam prep resources for the self-guided learner.',
                'type' => 'monthly',
                'duration' => '3',
                'features' => json_encode(['Includes Essentials Package', 'Step-by-step Exam Prep Study Plan', 'Hot Topics PMP® Flashcards', 'Project Manager Upskilling Resources', 'Free shipping in the US (except Alaska and Hawaii)']),
                'tag' => 'Recommended',
                'is_active' => 'active',
            ],
            [
                'name' => 'Advanced',
                'slug' => Str::slug('Advanced'),
                'price' => 414.00,
                'description' => 'Get personalized coaching with an expert instructor to guide you through your exam prep journey.',
                'type' => 'monthly',
                'duration' => '6',
                'features' => json_encode(['Includes Essentials and Plus Package', 'Exam Prep eLearning Course:Earn 35 Contact Hours Requirement', 'Dedicated Monthly 1-on-1 Sessions', 'Free shipping in the US (except Alaska and Hawaii)']),
                'tag' => 'Best Value',
                'is_active' => 'active',
            ],
        ];

        foreach ($pricings as $pricing) {
            Pricing::create($pricing);
        }
    }
}
