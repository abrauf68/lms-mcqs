<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Approach;
use App\Models\Contact;
use App\Models\Domain;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\FillBlank;
use App\Models\Hotspot;
use App\Models\MatchPair;
use App\Models\Option;
use App\Models\ProcessGroup;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use App\Models\UserExam;
use App\Models\UserExamAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home()
    {
        try {
            return view('frontend.pages.home');
        } catch (\Throwable $th) {
            Log::error('Home Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function contact()
    {
        try {
            return view('frontend.pages.contact');
        } catch (\Throwable $th) {
            Log::error('Contact Page Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function contactSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'interest' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Contact Form Validation Failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {

            $contact = new Contact();
            $contact->name = $request->first_name . ' ' . $request->last_name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->interest = $request->interest;
            $contact->source = $request->source;
            $contact->message = $request->message;
            $contact->save();

            return redirect()->route('frontend.thank.you')->with('success', 'Your message has been sent successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error submitting contact form: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while submitting the contact form. Please try again later.');
        }
    }

    public function thankYou()
    {
        try {
            return view('frontend.pages.thank-you');
        } catch (\Throwable $th) {
            Log::error('Thank You Page Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function tryDemo()
    {
        try {
            $products = Product::where('is_active', 'active')->get();
            return view('frontend.pages.try-demo', compact('products'));
        } catch (\Throwable $th) {
            Log::error('Try Demo Page Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function submitDemo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {

            $exam = Exam::where('product_id', $request->product_id)->where('is_demo', '1')->where('is_active', 'active')->first();

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make('password');
                $username = $this->generateUsername($request->name);

                while (User::where('username', $username)->exists()) {
                    $username = $this->generateUsername($request->name);
                }
                $user->username = $username;
                $user->save();

                $user->syncRoles('user');

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->first_name = $request->name;
                $profile->save();
            }

            Auth::login($user);


            $userExam = UserExam::where('user_id', $user->id)->where('exam_id', $exam->id)->where('status', 'not_started')->first();
            if (!$userExam) {
                $userExam = new UserExam();
                $userExam->user_id = $user->id;
                $userExam->exam_id = $exam->id;
                $userExam->save();
            }

            $questions = ExamQuestion::where('exam_id', $exam->id)
                ->orderByRaw('question_order IS NULL')
                ->orderBy('question_order', 'asc')
                ->get();

            foreach ($questions as $question) {
                $userExamquestion = UserExamAnswer::where('user_id', $user->id)->where('user_exam_id', $userExam->id)->where('exam_id', $exam->id)->where('question_id', $question->question_id)->first();
                if (!$userExamquestion) {
                    $userExamquestion = new UserExamAnswer();
                    $userExamquestion->user_id = $user->id;
                    $userExamquestion->exam_id = $exam->id;
                    $userExamquestion->user_exam_id = $userExam->id;
                    $userExamquestion->question_id = $question->question_id;
                    $userExamquestion->question_order = $question->question_order;
                    $userExamquestion->save();
                }
            }

            $firstQuestion = $questions->first();

            return redirect()->route('frontend.exam', ['exam_slug' => $exam->slug, 'user_exam_id' => $userExam->id, 'question_id' => $firstQuestion->id]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error creating exam: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the exam. Please try again later.');
        }
    }

    public function exam($exam_slug, $user_exam_id, $question_id)
    {
        try {
            $exam = Exam::where('slug', $exam_slug)->firstOrFail();
            $questions = ExamQuestion::where('exam_id', $exam->id)
                ->orderByRaw('question_order IS NULL')
                ->orderBy('question_order', 'asc')
                ->get();

            $userExam = UserExam::findOrFail($user_exam_id);

            $userExamQuestions = UserExamAnswer::with('question')->where('user_id', Auth::id())->where('exam_id', $exam->id)->where('user_exam_id', $userExam->id)->get();

            $currentQuestion = UserExamAnswer::with('question.options', 'question.fillBlank', 'question.matchPairs', 'question.hotspot')->where('user_id', Auth::id())->where('exam_id', $exam->id)->where('user_exam_id', $userExam->id)->where('question_id', $question_id)->first();

            // dd($currentQuestion);
            return view('frontend.pages.exams.index', compact('exam', 'currentQuestion', 'userExamQuestions', 'userExam'));
        } catch (\Throwable $th) {
            Log::error('Exam Page Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function markForReview($id)
    {
        try {
            $currentQuestion = UserExamAnswer::where('id', $id)->first();

            if ($currentQuestion->is_marked == '1') {
                $currentQuestion->is_marked = '0';
            } else {
                $currentQuestion->is_marked = '1';
            }
            $currentQuestion->save();

            $exam = Exam::find($currentQuestion->exam_id);

            return redirect()->route('frontend.exam', ['exam_slug' => $exam->slug, 'user_exam_id' => $currentQuestion->user_exam_id, 'question_id' => $currentQuestion->question_id]);
        } catch (\Throwable $th) {
            Log::error('Exam Page Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function submitSingleChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_exam_answer_id' => 'required|exists:user_exam_answers,id',
            'option_id' => 'required|exists:options,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {

            $currentQuestion = UserExamAnswer::where('id', $request->user_exam_answer_id)->first();
            $currentQuestion->selected_option_id = $request->option_id;
            $currentQuestion->is_answered = '1';
            $currentQuestion->save();

            $exam = Exam::find($currentQuestion->exam_id);

            return redirect()->route('frontend.exam', ['exam_slug' => $exam->slug, 'user_exam_id' => $currentQuestion->user_exam_id, 'question_id' => $currentQuestion->question_id]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error submitting answer: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while submitting the answer. Please try again later.');
        }
    }

    public function submitMultiChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_exam_answer_id' => 'required|exists:user_exam_answers,id',
            'option_id' => 'required|array',
            'option_id.*' => 'exists:options,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            $currentQuestion = UserExamAnswer::findOrFail($request->user_exam_answer_id);

            // ✅ Save as JSON
            $currentQuestion->selected_options = json_encode($request->option_id);
            $currentQuestion->is_answered = 1;
            $currentQuestion->save();

            $exam = Exam::find($currentQuestion->exam_id);

            return redirect()->route('frontend.exam', [
                'exam_slug' => $exam->slug,
                'user_exam_id' => $currentQuestion->user_exam_id,
                'question_id' => $currentQuestion->question_id
            ]);
        } catch (\Throwable $th) {
            Log::error('Multi Choice Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function submitMatching(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_exam_answer_id' => 'required|exists:user_exam_answers,id',
            'matches' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            $currentQuestion = UserExamAnswer::findOrFail($request->user_exam_answer_id);

            // ✅ decode JSON
            $matches = json_decode($request->matches, true);

            $currentQuestion->matched_pairs = json_encode($matches);
            $currentQuestion->is_answered = 1;
            $currentQuestion->save();

            $exam = Exam::find($currentQuestion->exam_id);

            return redirect()->route('frontend.exam', [
                'exam_slug' => $exam->slug,
                'user_exam_id' => $currentQuestion->user_exam_id,
                'question_id' => $currentQuestion->question_id
            ]);
        } catch (\Throwable $th) {
            Log::error('Matching Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function submitFillBlank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_exam_answer_id' => 'required|exists:user_exam_answers,id',
            'answer_text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            $currentQuestion = UserExamAnswer::findOrFail($request->user_exam_answer_id);

            $currentQuestion->answer_text = $request->answer_text;
            $currentQuestion->is_answered = 1;
            $currentQuestion->save();

            $exam = Exam::find($currentQuestion->exam_id);

            return redirect()->route('frontend.exam', [
                'exam_slug' => $exam->slug,
                'user_exam_id' => $currentQuestion->user_exam_id,
                'question_id' => $currentQuestion->question_id
            ]);
        } catch (\Throwable $th) {
            Log::error('Fill Blank Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function submitHotspot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_exam_answer_id' => 'required|exists:user_exam_answers,id',
            'hotspot' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            $currentQuestion = UserExamAnswer::findOrFail($request->user_exam_answer_id);

            $currentQuestion->hotspot = $request->hotspot;
            $currentQuestion->is_answered = 1;
            $currentQuestion->save();

            $exam = Exam::find($currentQuestion->exam_id);

            return redirect()->route('frontend.exam', [
                'exam_slug' => $exam->slug,
                'user_exam_id' => $currentQuestion->user_exam_id,
                'question_id' => $currentQuestion->question_id
            ]);
        } catch (\Throwable $th) {
            Log::error('Hotspot Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function submitAnnotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_exam_answer_id' => 'required|exists:user_exam_answers,id',
            'annotations' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            $answer = UserExamAnswer::findOrFail($request->user_exam_answer_id);

            $answer->annotations = json_encode($request->annotations);
            $answer->save();

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            Log::error('Annotation Error: ' . $th->getMessage());
            return response()->json(['error' => true], 500);
        }
    }

    public function scoreSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_exam_id' => 'required|exists:user_exams,id',
            'time_taken' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            $userExam = UserExam::findOrFail($request->user_exam_id);

            $answers = UserExamAnswer::with('question')->where('user_exam_id', $userExam->id)->get();

            $correctCount = 0;

            foreach ($answers as $answer) {

                $question = $answer->question;
                $isCorrect = 0;

                // ✅ SINGLE CHOICE
                if ($question->type === 'single_choice') {

                    $correctOption = Option::where('question_id', $question->id)
                        ->where('is_correct', '1')
                        ->value('id');

                    if ($answer->selected_option_id == $correctOption) {
                        $isCorrect = 1;
                    }
                }

                // ✅ MULTI CHOICE
                elseif ($question->type === 'multi_choice') {

                    $correctOptions = Option::where('question_id', $question->id)
                        ->where('is_correct', '1')
                        ->pluck('id')
                        ->toArray();

                    $userOptions = json_decode($answer->selected_options ?? '[]', true);

                    sort($correctOptions);
                    sort($userOptions);

                    if ($correctOptions == $userOptions) {
                        $isCorrect = 1;
                    }
                }

                // ✅ FILL IN BLANK
                elseif ($question->type === 'fill_blank') {

                    $correctAnswer = FillBlank::where('question_id', $question->id)
                        ->value('correct_answer');

                    if (strtolower(trim($answer->answer_text)) == strtolower(trim($correctAnswer))) {
                        $isCorrect = 1;
                    }
                }

                // ✅ MATCHING
                elseif ($question->type === 'matching') {

                    $correctPairs = MatchPair::where('question_id', $question->id)
                        ->pluck('right_item', 'left_item') // left => right
                        ->toArray();

                    $userPairs = json_decode($answer->matched_pairs ?? '[]', true);

                    if ($correctPairs == $userPairs) {
                        $isCorrect = 1;
                    }
                }

                // ✅ HOTSPOT
                elseif ($question->type === 'hotspot') {

                    $userPoint = json_decode($answer->hotspot ?? '{}', true);

                    if ($userPoint && isset($userPoint['x'], $userPoint['y'])) {

                        $hotspot = Hotspot::where('question_id', $question->id)->first();

                        if ($hotspot) {
                            $dx = $userPoint['x'] - $hotspot->x;
                            $dy = $userPoint['y'] - $hotspot->y;

                            $distance = sqrt($dx * $dx + $dy * $dy);

                            if ($distance <= $hotspot->radius) {
                                $isCorrect = 1;
                            }
                        }
                    }
                }

                // 🔥 SAVE RESULT PER QUESTION
                $answer->is_correct = $isCorrect;
                $answer->is_answered = '1';
                $answer->save();

                if ($isCorrect) {
                    $correctCount++;
                }
            }

            // 🔥 FINAL CALCULATIONS
            $totalQuestions = $answers->count();
            $wrongCount = $totalQuestions - $correctCount;

            $percentage = $totalQuestions > 0
                ? round(($correctCount / $totalQuestions) * 100)
                : 0;

            $result = $percentage >= 70 ? 'pass' : 'fail';

            $avgTime = $totalQuestions > 0
                ? round($request->time_taken / $totalQuestions)
                : 0;

            // 🔥 SAVE EXAM
            $userExam->total_time = $request->time_taken;
            $userExam->avg_time = $avgTime;
            $userExam->score_percentage = $percentage;
            $userExam->total_questions = $totalQuestions;
            $userExam->correct_answers = $correctCount;
            $userExam->wrong_answers = $wrongCount;
            $userExam->result = $result;
            $userExam->status = 'completed';
            $userExam->save();

            return redirect()->route('frontend.exam.stat', $userExam->id)->with('success', 'Exam scored successfully!');
        } catch (\Throwable $th) {
            Log::error('Score Submit Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while calculating the score!');
        }
    }

    // public function examStat($exam_id)
    // {
    //     try {
    //         $userExam = UserExam::findOrFail($exam_id);
    //         $userExamQuestions = UserExamAnswer::with('question')->where('user_exam_id', $userExam->id)->get();
    //         return view('frontend.pages.exams.exam-stat', compact('userExam', 'userExamQuestions'));
    //     } catch (\Throwable $th) {
    //         Log::error('Exam Stat Page Failed', ['error' => $th->getMessage()]);
    //         return redirect()->back()->with('error', "Something went wrong! Please try again later");
    //         throw $th;
    //     }
    // }

    public function examStat($user_exam_id)
    {
        try {
            $userExam = UserExam::with('exam')->findOrFail($user_exam_id);

            // ✅ User answers with question relations
            $answers = UserExamAnswer::with('question')
            ->where('user_exam_id', $userExam->id)
            ->get();

            $firstAns = $answers->first();
            /*
        |--------------------------------------------------------------------------
        | DOMAIN STATS
        |--------------------------------------------------------------------------
        */
            $domains = Domain::where('is_active', 'active')->get();

            $domainItems = $domains->map(function ($domain) use ($answers, $userExam) {

                // ✅ TOTAL questions (questions table)
                $total = Question::where('domain_id', $domain->id)
                    ->where('product_id', $userExam->exam_id) // adjust if needed
                    ->count();

                // ✅ CORRECT answers (user answers)
                $correct = $answers
                    ->where('question.domain_id', $domain->id)
                    ->where('is_correct', '1')
                    ->count();

                $wrong = $total - $correct;

                $percent = $total > 0
                    ? round(($correct / $total) * 100, 2)
                    : 0;

                return [
                    'name' => $domain->name,
                    'total' => $total,
                    'correct' => $correct,
                    'wrong' => $wrong,
                    'percent' => $percent,
                ];
            });

            $domainStats = collect([
                'total' => $domainItems->sum('total'),
                'correct' => $domainItems->sum('correct'),
                'wrong' => $domainItems->sum('total') - $domainItems->sum('correct'),
                'percent' => $domainItems->sum('total') > 0
                    ? round(($domainItems->sum('correct') / $domainItems->sum('total')) * 100, 2)
                    : 0,
                'items' => $domainItems->values(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | PROCESS GROUP STATS
        |--------------------------------------------------------------------------
        */
            $processItems = ProcessGroup::where('is_active', 'active')->get()
                ->map(function ($process) use ($answers, $userExam) {

                    $total = Question::where('process_group_id', $process->id)
                        ->where('product_id', $userExam->exam_id)
                        ->count();

                    $correct = $answers
                        ->where('question.process_group_id', $process->id)
                        ->where('is_correct', '1')
                        ->count();

                    $wrong = $total - $correct;

                    $percent = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

                    return [
                        'name' => $process->name,
                        'total' => $total,
                        'correct' => $correct,
                        'wrong' => $wrong,
                        'percent' => $percent,
                    ];
                });

            $processStats = collect([
                'total' => $processItems->sum('total'),
                'correct' => $processItems->sum('correct'),
                'wrong' => $processItems->sum('total') - $processItems->sum('correct'),
                'percent' => $processItems->sum('total') > 0
                    ? round(($processItems->sum('correct') / $processItems->sum('total')) * 100, 2)
                    : 0,
                'items' => $processItems->values(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | TOPIC STATS
        |--------------------------------------------------------------------------
        */
            $topicItems = Topic::where('is_active', 'active')->get()
                ->map(function ($topic) use ($answers, $userExam) {

                    $total = Question::where('topic_id', $topic->id)
                        ->where('product_id', $userExam->exam_id)
                        ->count();

                    $correct = $answers
                        ->where('question.topic_id', $topic->id)
                        ->where('is_correct', '1')
                        ->count();

                    $wrong = $total - $correct;

                    $percent = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

                    return [
                        'name' => $topic->name,
                        'total' => $total,
                        'correct' => $correct,
                        'wrong' => $wrong,
                        'percent' => $percent,
                    ];
                });

            $topicStats = collect([
                'total' => $topicItems->sum('total'),
                'correct' => $topicItems->sum('correct'),
                'wrong' => $topicItems->sum('total') - $topicItems->sum('correct'),
                'percent' => $topicItems->sum('total') > 0
                    ? round(($topicItems->sum('correct') / $topicItems->sum('total')) * 100, 2)
                    : 0,
                'items' => $topicItems->values(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | APPROACH STATS
        |--------------------------------------------------------------------------
        */
            $approachItems = Approach::where('is_active', 'active')->get()
                ->map(function ($approach) use ($answers, $userExam) {

                    $total = Question::where('approach_id', $approach->id)
                        ->where('product_id', $userExam->exam_id)
                        ->count();

                    $correct = $answers
                        ->where('question.approach_id', $approach->id)
                        ->where('is_correct', '1')
                        ->count();

                    $wrong = $total - $correct;

                    $percent = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

                    return [
                        'name' => $approach->name,
                        'total' => $total,
                        'correct' => $correct,
                        'wrong' => $wrong,
                        'percent' => $percent,
                    ];
                });

            $approachStats = collect([
                'total' => $approachItems->sum('total'),
                'correct' => $approachItems->sum('correct'),
                'wrong' => $approachItems->sum('total') - $approachItems->sum('correct'),
                'percent' => $approachItems->sum('total') > 0
                    ? round(($approachItems->sum('correct') / $approachItems->sum('total')) * 100, 2)
                    : 0,
                'items' => $approachItems->values(),
            ]);

            // dd($domainStats);

            return view('frontend.pages.exams.exam-stat', compact(
                'userExam',
                'domainStats',
                'processStats',
                'topicStats',
                'approachStats',
                'firstAns',
            ));
        } catch (\Throwable $th) {
            Log::error('Exam Stat Page Failed', ['error' => $th->getMessage()]);
            return back()->with('error', "Something went wrong!");
        }
    }
    public function examReview($exam_slug, $user_exam_id, $question_id)
    {
        try {
            $exam = Exam::where('slug', $exam_slug)->firstOrFail();
            $questions = ExamQuestion::where('exam_id', $exam->id)
                ->orderByRaw('question_order IS NULL')
                ->orderBy('question_order', 'asc')
                ->get();

            $userExam = UserExam::findOrFail($user_exam_id);

            $userExamQuestions = UserExamAnswer::with('question')->where('user_id', Auth::id())->where('exam_id', $exam->id)->where('user_exam_id', $userExam->id)->get();

            $currentQuestion = UserExamAnswer::with('question.options', 'question.fillBlank', 'question.matchPairs', 'question.hotspot')->where('user_exam_id', $userExam->id)->where('user_id', Auth::id())->where('exam_id', $exam->id)->where('question_id', $question_id)->first();

            // dd($currentQuestion);
            return view('frontend.pages.exams.review', compact('exam', 'currentQuestion', 'userExamQuestions', 'userExam'));
        } catch (\Throwable $th) {
            Log::error('Exam Page Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }



















    public function generateUsername($name)
    {
        $name = strtolower(str_replace(' ', '', $name));
        $username = $name . rand(1000, 9999);
        return $username;
    }
}
