<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Product;
use App\Models\Profile;
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
            $contact->name = $request->first_name.' '.$request->last_name;
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
                try {
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
                } catch (\Throwable $th) {
                    Log::error('Error creating new user with email: ' . $th->getMessage());
                }
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
                $userExamquestion = UserExamAnswer::where('user_id', $user->id)->where('exam_id', $exam->id)->where('question_id', $question->question_id)->first();
                if (!$userExamquestion) {
                    $userExamquestion = new UserExamAnswer();
                    $userExamquestion->user_id = $user->id;
                    $userExamquestion->exam_id = $exam->id;
                    $userExamquestion->question_id = $question->question_id;
                    $userExamquestion->question_order = $question->question_order;
                    $userExamquestion->save();
                }
            }

            $firstQuestion = $questions->first();

            return redirect()->route('frontend.exam', ['exam_slug' => $exam->slug, 'question_id' => $firstQuestion->id]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error creating exam: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the exam. Please try again later.');
        }
    }

    public function exam($exam_slug, $question_id)
    {
        try {
            $exam = Exam::where('slug', $exam_slug)->firstOrFail();
            $questions = ExamQuestion::where('exam_id', $exam->id)
                ->orderByRaw('question_order IS NULL')
                ->orderBy('question_order', 'asc')
                ->get();

            $userExamQuestions = UserExamAnswer::with('question')->where('user_id', Auth::id())->where('exam_id', $exam->id)->get();

            $currentQuestion = UserExamAnswer::with('question.options', 'question.fillBlanks', 'question.matchPairs', 'question.hotspot')->where('user_id', Auth::id())->where('exam_id', $exam->id)->where('question_id', $question_id)->first();

            // dd($userExamQuestions);
            return view('frontend.pages.exams.index', compact('exam', 'currentQuestion', 'userExamQuestions'));
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

            return redirect()->route('frontend.exam', ['exam_slug' => $exam->slug, 'question_id' => $currentQuestion->question_id]);
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

            return redirect()->route('frontend.exam', ['exam_slug' => $exam->slug, 'question_id' => $currentQuestion->question_id]);
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
                'question_id' => $currentQuestion->question_id
            ]);

        } catch (\Throwable $th) {
            Log::error('Multi Choice Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }



















    public function generateUsername($name)
    {
        $name = strtolower(str_replace(' ', '', $name));
        $username = $name . rand(1000, 9999);
        return $username;
    }
}
