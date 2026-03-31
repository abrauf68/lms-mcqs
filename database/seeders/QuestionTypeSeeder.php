<?php

namespace Database\Seeders;

use App\Models\Approach;
use App\Models\Domain;
use App\Models\ProcessGroup;
use App\Models\Product;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Products seeding
        $products = [
            [
                'name' => 'PM Fast Track Exam Simulator',
                'slug' => Str::slug('PM Fast Track Exam Simulator'),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }


        // Domains seeding
        $domains = [
            [
                'name' => 'People',
                'slug' => Str::slug('People'),
            ],
            [
                'name' => 'Process',
                'slug' => Str::slug('Process'),
            ],
            [
                'name' => 'Business Environment',
                'slug' => Str::slug('Business Environment'),
            ],
        ];

        foreach ($domains as $domain) {
            Domain::create($domain);
        }

        // Process Group seeding
        $processGroups = [
            [
                'name' => 'Initiating',
                'slug' => Str::slug('Initiating'),
            ],
            [
                'name' => 'Planning',
                'slug' => Str::slug('Planning'),
            ],
            [
                'name' => 'Executing',
                'slug' => Str::slug('Executing'),
            ],
            [
                'name' => 'Monitoring & Controlling',
                'slug' => Str::slug('Monitoring & Controlling'),
            ],
            [
                'name' => 'Closing',
                'slug' => Str::slug('Closing'),
            ],
        ];

        foreach ($processGroups as $processGroup) {
            ProcessGroup::create($processGroup);
        }

        // Topics seeding
        $topics = [
            [
                'name' => 'Foundations',
                'slug' => Str::slug('Foundations'),
            ],
            [
                'name' => 'Integration',
                'slug' => Str::slug('Integration'),
            ],
            [
                'name' => 'Scope',
                'slug' => Str::slug('Scope'),
            ],
            [
                'name' => 'Schedule',
                'slug' => Str::slug('Schedule'),
            ],
            [
                'name' => 'Cost',
                'slug' => Str::slug('Cost'),
            ],
            [
                'name' => 'Quality',
                'slug' => Str::slug('Quality'),
            ],
            [
                'name' => 'Resource',
                'slug' => Str::slug('Resource'),
            ],
            [
                'name' => 'Communications',
                'slug' => Str::slug('Communications'),
            ],
            [
                'name' => 'Risk',
                'slug' => Str::slug('Risk'),
            ],
            [
                'name' => 'Procurement',
                'slug' => Str::slug('Procurement'),
            ],
            [
                'name' => 'Stakeholder',
                'slug' => Str::slug('Stakeholder'),
            ],
        ];

        foreach ($topics as $topic) {
            Topic::create($topic);
        }


        // Approach seeding
        $approaches = [
            [
                'name' => 'Predictive',
                'slug' => Str::slug('Predictive'),
            ],
            [
                'name' => 'Agile',
                'slug' => Str::slug('Agile'),
            ],
            [
                'name' => 'Hybrid',
                'slug' => Str::slug('Hybrid'),
            ],
        ];

        foreach ($approaches as $approach) {
            Approach::create($approach);
        }
    }
}
