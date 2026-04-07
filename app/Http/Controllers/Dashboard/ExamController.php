<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Product;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view exam');
        try {
            $exams = Exam::all();
            return view('dashboard.exams.index', compact('exams'));
        } catch (\Throwable $th) {
            Log::error('exams Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create exam');
        try {
            $products = Product::where('is_active', 'active')->get();
            return view('dashboard.exams.create', compact('products'));
        } catch (\Throwable $th) {
            Log::error('exams Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create exam');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:exams,slug|max:255',
            'product_id' => 'required|exists:products,id',
            'is_demo' => 'required|in:0,1',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Exam Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $exam = new Exam();
            $exam->name = $request->name;
            $exam->slug = $request->slug;
            $exam->product_id = $request->product_id;
            $exam->is_demo = $request->is_demo;
            $exam->description = $request->description;
            $exam->save();

            DB::commit();
            return redirect()->route('dashboard.exams.index')->with('success', 'Exam Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Exam Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update exam');
        try {
            $exam = Exam::findOrFail($id);
            $products = Product::where('is_active', 'active')->get();
            return view('dashboard.exams.edit', compact('exam', 'products'));
        } catch (\Throwable $th) {
            Log::error('exam Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update exam');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:exams,slug,' . $id,
            'product_id' => 'required|exists:products,id',
            'is_demo' => 'required|in:0,1',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $exam = Exam::findOrFail($id);
            $exam->name = $request->name;
            $exam->slug = $request->slug;
            $exam->product_id = $request->product_id;
            $exam->is_demo = $request->is_demo;
            $exam->description = $request->description;
            $exam->save();

            return redirect()->route('dashboard.exams.index')->with('success', 'Exam Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('exam Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete exam');
        try {
            $exam = Exam::findOrFail($id);
            $exam->delete();
            return redirect()->back()->with('success', 'Exam Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Exam Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update exam');
        try {
            $exam = Exam::findOrFail($id);
            $message = $exam->is_active == 'active' ? 'Exam Deactivated Successfully' : 'Exam Activated Successfully';
            if ($exam->is_active == 'active') {
                $exam->is_active = 'inactive';
                $exam->save();
            } else {
                $exam->is_active = 'active';
                $exam->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Exam Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function showQuestions(string $id)
    {
        $this->authorize('update exam');
        try {
            $exam = Exam::findOrFail($id);
            $examQuestions = ExamQuestion::where('exam_id', $id)->orderBy('question_order')->get();
            $questions = Question::where('is_active', 'active')->get();
            return view('dashboard.exams.questions', compact('exam', 'examQuestions', 'questions'));
        } catch (\Throwable $th) {
            Log::error('Exam Questions Fetch Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateQuestions(Request $request, string $id)
    {
        $this->authorize('update exam');
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|array',
            'question_id.*' => 'required|exists:questions,id',
            'question_order' => 'required|array',
            'question_order.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            Log::error('Exam Questions Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            ExamQuestion::where('exam_id', $id)->delete();
            foreach ($request->question_id as $index => $questionId) {
                $examQuestion = new ExamQuestion();
                $examQuestion->exam_id = $id;
                $examQuestion->question_id = $questionId;
                $examQuestion->question_order = $request->question_order[$index];
                $examQuestion->save();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Exam Questions Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Exam Questions Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function assignRandomQuestions(Request $request, string $id)
    {
        $request->validate([
            'limit' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $limit = $request->limit;

            $types = ['single_choice','multi_choice','fill_blank','matching','hotspot'];

            $perType = intval($limit / count($types));
            $remaining = $limit % count($types);

            $questions = collect();

            foreach ($types as $type) {

                $take = $perType + ($remaining > 0 ? 1 : 0);
                if ($remaining > 0) $remaining--;

                $typeQuestions = Question::where('is_active', 'active')
                    ->where('type', $type)
                    ->inRandomOrder()
                    ->limit($take)
                    ->get();

                $questions = $questions->merge($typeQuestions);
            }

            ExamQuestion::where('exam_id', $id)->delete();

            foreach ($questions as $index => $q) {
                ExamQuestion::create([
                    'exam_id' => $id,
                    'question_id' => $q->id,
                    'question_order' => $index + 1,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Random Questions Assigned Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Random Question Assignment Failed', [
                'error' => $th->getMessage()
            ]);
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
