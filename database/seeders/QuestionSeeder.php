<?php

namespace Database\Seeders;

use App\Models\FillBlank;
use App\Models\Hotspot;
use App\Models\MatchPair;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Questions seeding
        $questions = [
            [
                'product_id' => 1,
                'domain_id' => 1,
                'process_group_id' => 1,
                'topic_id' => 1,
                'approach_id' => 1,
                'type' => 'single_choice',
                'question_text' => 'The customer contract specifies a 2,000-call capacity for the new call center project. However, one of the technical experts on the development team believes a 3,000-call capacity can be reached. Another team member who works as a tester thinks that based on the technical needs of the customer, the capacity needs to be only 1,500 calls. What is the best thing for the product owner to do?',
                'ans_explanation' => 'The fact that such a discussion is occurring indicates a lack of clarity as to why the customer requested the 2,000-call capacity. Generally, a difference in requirements is resolved in favor of the customer. However, it is the product owner\'s responsibility to inform the customer of other options.',
            ],
            [
                'product_id' => 1,
                'domain_id' => 2,
                'process_group_id' => 2,
                'topic_id' => 2,
                'approach_id' => 2,
                'type' => 'multi_choice',
                'question_text' => 'Team members are highly skilled, but an inexperienced project manager is having difficulty motivating them to put forth the effort needed to achieve project requirements within tight schedule constraints. The project manager would benefit from understanding each team member and their individual needs and desires. Which three of the following needs would provide the project manager with insights on how to motivate the team? (Pick 3 choices)',
                'ans_explanation' => 'People are motivated by different factors and the project manager will be effective when they learn about each team member’s needs. The needs that will help the project manager motivate team members are the need for mastery of a skill (the project manager could offer training), need to direct their own work (the project manager could empower them to do this), and need for recognition of achievement (the project manager could provide recognition and rewards). The need for the latest technology and tools is nice to have but not a core need of a human being. The need for close relationships and friendships is not necessarily satisfied in a work environment and the project manager has no control over this. The need for fulfillment of basic physiological needs like shelter and food should already be addressed by the worker’s compensation, again outside the project manager’s direct control.',
            ],
            [
                'product_id' => 1,
                'domain_id' => 3,
                'process_group_id' => 3,
                'topic_id' => 3,
                'approach_id' => 3,
                'type' => 'matching',
                'question_text' => 'A project manager is coaching a team that is just switching to agile. In introducing them to key agile concepts they explain that agile values some things more than others',
                'ans_explanation' => 'The agile manifesto values individuals and interactions over process and tools. It values working software more than comprehensive documentation. It values customer collaboration over contract negotiations. It values responding to changes more than following a plan. Although all these items are important, the ones on the left are favored over the ones on the right.',
            ],
            [
                'product_id' => 1,
                'domain_id' => 2,
                'process_group_id' => 4,
                'topic_id' => 4,
                'approach_id' => 3,
                'type' => 'fill_blank',
                'question_text' => 'The project manager and team have identified and assessed several risks for their project. Review the risk grid. Which of the following risks would the project manager likely not move into quantitative risk analysis? Enter its ID in the box.',
                'ans_explanation' => 'Because the probability of Risk D is so high, it should be treated as a fact, not a risk. It should be addressed in another part of the project management plan.',
            ],
            [
                'product_id' => 1,
                'domain_id' => 1,
                'process_group_id' => 5,
                'topic_id' => 5,
                'approach_id' => 2,
                'type' => 'hotspot',
                'question_text' => 'The project manager is reviewing the Pareto chart for errors reported by an insurance company during the testing of the new system. Which of the problems should the project manager prioritize first for corrective action? Click on the bar of the highest priority error',
                'ans_explanation' => 'A pareto chart arranges results from the most frequent to least frequent (left to right) to help identify root causes which are resulting in the most problems. 80/20 rule says that 80 percent of problems are due to 20 percent of the root causes. In this question, the wrong charge amount is causing the most problems.',
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }

        // Question Options seeding
        $options = [
                // Question 1 options
            [
                'question_id' => 1,
                'option_text' => 'Meet with the technical experts, and help them to agree on an objective.',
            ],
            [
                'question_id' => 1,
                'option_text' => 'Set the objective at 3,000 calls.',
            ],
            [
                'question_id' => 1,
                'option_text' => 'Set the objective at 2,000 calls',
            ],
            [
                'question_id' => 1,
                'option_text' => 'Meet with the customer to better understand the reasons behind the 2,000-call capacity.',
                'is_correct' => '1',
            ],
                // Question 2 options
            [
                'question_id' => 2,
                'option_text' => 'Need to direct their own work',
                'is_correct' => '1',
            ],
            [
                'question_id' => 2,
                'option_text' => 'Need for recognition of achievement',
                'is_correct' => '1',
            ],
            [
                'question_id' => 2,
                'option_text' => 'Need for close relationships and friendships',
            ],
            [
                'question_id' => 2,
                'option_text' => 'Need for mastery of a skill',
                'is_correct' => '1',
            ],
            [
                'question_id' => 2,
                'option_text' => 'Need for latest technology and toois',
            ],
            [
                'question_id' => 2,
                'option_text' => 'Need for fulfillment of basic physiological needs',
            ],
        ];

        foreach ($options as $option) {
            Option::create($option);
        }

        // Question Matching Pair seeding
        $pairs = [
            [
                'question_id' => 3,
                'left_item' => 'Responding to change',
                'right_item' => 'Comprehensive documentation',
            ],
            [
                'question_id' => 3,
                'left_item' => 'Working software',
                'right_item' => 'Process and tools',
            ],
            [
                'question_id' => 3,
                'left_item' => 'Customer collaboration',
                'right_item' => 'Following a plan',
            ],
            [
                'question_id' => 3,
                'left_item' => 'Individual and interactions',
                'right_item' => 'Contract negotiation',
            ],
        ];

        foreach ($pairs as $pair) {
            MatchPair::create($pair);
        }

        // Question Fill Blank seeding
        $blanks = [
            [
                'question_id' => 4,
                'image' => 'uploads/questions/blanks/FIB_12.png',
                'correct_answer' => 'D',
            ],
        ];

        foreach ($blanks as $blank) {
            FillBlank::create($blank);
        }

        // Question Hotspot seeding
        $hotspots = [
            [
                'question_id' => 4,
                'image' => 'uploads/questions/hotspot/HS_15.png',
                'x' => 100,
                'y' => 150,
                'radius' => 50,
            ],
        ];

        foreach ($hotspots as $hotspot) {
            Hotspot::create($hotspot);
        }
    }
}
