<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Approach;
use App\Models\Domain;
use App\Models\FillBlank;
use App\Models\Hotspot;
use App\Models\MatchPair;
use App\Models\Option;
use App\Models\ProcessGroup;
use App\Models\Product;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view question');
        try {
            // $questions = Question::all();
            return view('dashboard.questions.index');
        } catch (\Throwable $th) {
            Log::error('questions Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    // public function json(Request $request)
    // {
    //     try {

    //         $data = \App\Models\Question::query();

    //         return DataTables::of($data)->make(true);

    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'error' => $e->getMessage(),
    //             'line' => $e->getLine(),
    //             'file' => $e->getFile()
    //         ]);
    //     }
    // }

    public function json(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $data = Question::select(['id', 'question_text', 'type', 'is_active', 'created_at'])
                ->orderBy('id', 'desc');

            return DataTables::eloquent($data)
                ->addIndexColumn()

                ->editColumn('question_text', function ($row) {
                    return '<span title="' . e($row->question_text) . '">' .
                        \Illuminate\Support\Str::limit($row->question_text, 25, '...') .
                        '</span>';
                })

                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('F d, Y');
                })

                ->addColumn('q_type', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->type));
                })

                ->addColumn('status', function ($row) {
                    return $row->is_active == 'active'
                        ? '<span class="badge bg-label-success">Active</span>'
                        : '<span class="badge bg-label-danger">Inactive</span>';
                })

                ->addColumn('action', function ($row) use ($user) {

                    $btn = '';

                    if ($user && $user->can('update question')) {

                        $btn .= '<a href="' . route('dashboard.questions.edit', $row->id) . '" 
                            class="btn btn-icon btn-text-primary rounded-pill me-1"
                            title="Edit">
                            <i class="ti ti-edit"></i>
                         </a>';

                        $icon = $row->is_active == 'active'
                            ? '<i class="ti ti-toggle-right text-success"></i>'
                            : '<i class="ti ti-toggle-left text-danger"></i>';

                        $title = $row->is_active == 'active' ? 'Deactivate' : 'Activate';

                        $btn .= '<a href="' . route('dashboard.questions.status.update', $row->id) . '" 
                            class="btn btn-icon btn-text-primary rounded-pill me-1"
                            title="' . $title . '">
                            ' . $icon . '
                         </a>';
                    }

                    if ($user && $user->can('delete question')) {

                        $btn .= '<form method="POST" action="' . route('dashboard.questions.destroy', $row->id) . '" style="display:inline-block;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-icon btn-text-danger rounded-pill delete_confirmation"
                                title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                         </form>';
                    }

                    return $btn;
                })

                ->rawColumns(['question_text', 'status', 'action'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create question');
        try {
            $products = Product::where('is_active', 'active')->get();
            $domains = Domain::where('is_active', 'active')->get();
            $processGroups = ProcessGroup::where('is_active', 'active')->get();
            $approaches = Approach::where('is_active', 'active')->get();
            $topics = Topic::where('is_active', 'active')->get();
            return view('dashboard.questions.create', compact('products', 'domains', 'processGroups', 'approaches', 'topics'));
        } catch (\Throwable $th) {
            Log::error('Questions Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->authorize('create question');
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'domain_id' => 'required|exists:domains,id',
            'process_group_id' => 'required|exists:process_groups,id',
            'approach_id' => 'required|exists:approaches,id',
            'topic_id' => 'required|exists:topics,id',
            'type' => 'required|in:single_choice,multi_choice,fill_blank,matching,hotspot',
            'question_text' => 'nullable|string',
            'ans_explanation' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'is_correct' => 'nullable|array',
            'is_correct.*' => 'nullable|string',
            'blank_image' => 'nullable|image|max_size',
            'correct_answer' => 'nullable|string',
            'left_item' => 'nullable|array',
            'left_item.*' => 'nullable|string',
            'right_item' => 'nullable|array',
            'right_item.*' => 'nullable|string',
            'hotspot_image' => 'nullable|image|max_size',
            'x' => 'nullable|string',
            'y' => 'nullable|string',
            'width' => 'nullable|string',
            'height' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $question = Question::create([
                'product_id' => $request->product_id,
                'domain_id' => $request->domain_id,
                'process_group_id' => $request->process_group_id,
                'topic_id' => $request->topic_id,
                'approach_id' => $request->approach_id,
                'type' => $request->type,
                'question_text' => $request->question_text,
                'ans_explanation' => $request->ans_explanation,
            ]);

            // ================= OPTIONS =================
            if (in_array($request->type, ['single_choice', 'multi_choice'])) {

                foreach ($request->options as $key => $option) {

                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $option,
                        'is_correct' => in_array($key, $request->is_correct ?? []) ? '1' : '0',
                    ]);
                }
            }

            // ================= FILL BLANK =================
            if ($request->type == 'fill_blank') {

                if ($request->hasFile('blank_image')) {

                    $image = $request->file('blank_image');
                    $image_ext = $image->getClientOriginalExtension();
                    $image_name = time() . '_blank_image.' . $image_ext;

                    $image_path = 'uploads/questions/blanks';
                    $image->move(public_path($image_path), $image_name);
                    $imagePath = $image_path . "/" . $image_name;
                }

                FillBlank::create([
                    'question_id' => $question->id,
                    'image' => $imagePath ?? null,
                    'correct_answer' => $request->correct_answer,
                ]);
            }

            // ================= MATCHING =================
            if ($request->type == 'matching') {

                foreach ($request->left_item as $key => $left) {

                    MatchPair::create([
                        'question_id' => $question->id,
                        'left_item' => $left,
                        'right_item' => $request->right_item[$key],
                    ]);
                }
            }

            // ================= HOTSPOT =================
            if ($request->type == 'hotspot') {

                if ($request->hasFile('hotspot_image')) {

                    $image = $request->file('hotspot_image');
                    $image_ext = $image->getClientOriginalExtension();
                    $image_name = time() . '_hotspot_image.' . $image_ext;

                    $image_path = 'uploads/questions/hotspot';
                    $image->move(public_path($image_path), $image_name);
                    $imagePath = $image_path . "/" . $image_name;
                }

                Hotspot::create([
                    'question_id' => $question->id,
                    'image' => $imagePath ?? null, // uploaded image path
                    'x' => $request->x,
                    'y' => $request->y,
                    'width' => $request->width,
                    'height' => $request->height,
                    'radius' => '0',
                ]);
            }

            return redirect()->route('dashboard.questions.index')->with('success', 'Question Created Successfully');
        } catch (\Throwable $th) {
            Log::error('Question Create Failed', ['error' => $th->getMessage()]);
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
        $this->authorize('update question');
        try {
            $products = Product::where('is_active', 'active')->get();
            $domains = Domain::where('is_active', 'active')->get();
            $processGroups = ProcessGroup::where('is_active', 'active')->get();
            $approaches = Approach::where('is_active', 'active')->get();
            $topics = Topic::where('is_active', 'active')->get();
            $question = Question::with('options', 'fillBlank', 'matchPairs', 'hotspot')->findOrFail($id);
            return view('dashboard.questions.edit', compact('products', 'domains', 'processGroups', 'approaches', 'topics', 'question'));
        } catch (\Throwable $th) {
            Log::error('Questions Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update question');

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'domain_id' => 'required|exists:domains,id',
            'process_group_id' => 'required|exists:process_groups,id',
            'approach_id' => 'required|exists:approaches,id',
            'topic_id' => 'required|exists:topics,id',
            'type' => 'required|in:single_choice,multi_choice,fill_blank,matching,hotspot',
            'question_text' => 'nullable|string',
            'ans_explanation' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'is_correct' => 'nullable|array',
            'is_correct.*' => 'nullable|string',
            'blank_image' => 'nullable|image|max_size',
            'correct_answer' => 'nullable|string',
            'left_item' => 'nullable|array',
            'left_item.*' => 'nullable|string',
            'right_item' => 'nullable|array',
            'right_item.*' => 'nullable|string',
            'hotspot_image' => 'nullable|image|max_size',
            'x' => 'nullable|string',
            'y' => 'nullable|string',
            'width' => 'nullable|string',
            'height' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {

            $question = Question::findOrFail($id);

            // ================= UPDATE QUESTION =================
            $question->update([
                'product_id' => $request->product_id,
                'domain_id' => $request->domain_id,
                'process_group_id' => $request->process_group_id,
                'topic_id' => $request->topic_id,
                'approach_id' => $request->approach_id,
                'type' => $request->type,
                'question_text' => $request->question_text,
                'ans_explanation' => $request->ans_explanation,
            ]);

            // ================= OPTIONS =================
            if (in_array($request->type, ['single_choice', 'multi_choice'])) {

                // 🔥 PURANA DELETE
                $question->options()->delete();

                if (!empty($request->options)) {
                    foreach ($request->options as $key => $option) {

                        Option::create([
                            'question_id' => $question->id,
                            'option_text' => $option,
                            'is_correct' => in_array($key, $request->is_correct ?? []) ? '1' : '0',
                        ]);
                    }
                }
            } else {
                // agar type change ho gaya
                $question->options()->delete();
            }

            // ================= FILL BLANK =================
            if ($request->type == 'fill_blank') {

                $imagePath = optional($question->fillBlank)->image;

                if ($request->hasFile('blank_image')) {
                    $fillblank = $question->fillBlank;
                    if (isset($fillblank->image) && File::exists(public_path($fillblank->image))) {
                        File::delete(public_path($fillblank->image));
                    }
                    $image = $request->file('blank_image');
                    $image_ext = $image->getClientOriginalExtension();
                    $image_name = time() . '_blank_image.' . $image_ext;

                    $image_path = 'uploads/questions/blanks';
                    $image->move(public_path($image_path), $image_name);
                    $imagePath = $image_path . "/" . $image_name;
                }

                FillBlank::updateOrCreate(
                    ['question_id' => $question->id],
                    [
                        'image' => $imagePath ?? null,
                        'correct_answer' => $request->correct_answer,
                    ]
                );
            } else {
                FillBlank::where('question_id', $question->id)->delete();
            }

            // ================= MATCHING =================
            if ($request->type == 'matching') {

                $question->matchPairs()->delete();

                if (!empty($request->left_item)) {
                    foreach ($request->left_item as $key => $left) {

                        MatchPair::create([
                            'question_id' => $question->id,
                            'left_item' => $left,
                            'right_item' => $request->right_item[$key] ?? null,
                        ]);
                    }
                }
            } else {
                $question->matchPairs()->delete();
            }

            // ================= HOTSPOT =================
            if ($request->type == 'hotspot') {

                $imagePath = optional($question->hotspot)->image;

                if ($request->hasFile('hotspot_image')) {
                    $hotspot = $question->hotspot;
                    if (isset($hotspot->image) && File::exists(public_path($hotspot->image))) {
                        File::delete(public_path($hotspot->image));
                    }

                    $image = $request->file('hotspot_image');
                    $image_ext = $image->getClientOriginalExtension();
                    $image_name = time() . '_hotspot_image.' . $image_ext;

                    $image_path = 'uploads/questions/hotspot';
                    $image->move(public_path($image_path), $image_name);
                    $imagePath = $image_path . "/" . $image_name;
                }

                Hotspot::updateOrCreate(
                    ['question_id' => $question->id],
                    [
                        'image' => $imagePath ?? null, // uploaded image path
                        'x' => $request->x,
                        'y' => $request->y,
                        'width' => $request->width,
                        'height' => $request->height,
                        'radius' => '0',
                    ]
                );
            } else {
                Hotspot::where('question_id', $question->id)->delete();
            }

            return redirect()->route('dashboard.questions.index')->with('success', 'Question Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('Question Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete question');

        try {
            $question = Question::with('fillBlank', 'hotspot')->findOrFail($id);

            // ================= DELETE FILL BLANK IMAGE =================
            if ($question->fillBlank && $question->fillBlank->image) {

                $imagePath = public_path($question->fillBlank->image);

                if (File::exists($imagePath) && is_file($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // ================= DELETE HOTSPOT IMAGE =================
            if ($question->hotspot && $question->hotspot->image) {

                $imagePath = public_path($question->hotspot->image);

                if (File::exists($imagePath) && is_file($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // ================= DELETE RELATIONS =================
            $question->options()->delete();
            $question->fillBlank()->delete();
            $question->matchPairs()->delete();
            $question->hotspot()->delete();

            // ================= DELETE QUESTION =================
            $question->delete();

            return redirect()->route('dashboard.questions.index')->with('success', 'Question Deleted Successfully');
        } catch (\Throwable $th) {

            Log::error('Question Deletion Failed', [
                'error' => $th->getMessage()
            ]);

            return redirect()->back()
                ->with('error', "Something went wrong! Please try again later");

            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update question');
        try {
            $question = Question::findOrFail($id);
            $message = $question->is_active == 'active' ? 'Question Deactivated Successfully' : 'Question Activated Successfully';
            if ($question->is_active == 'active') {
                $question->is_active = 'inactive';
                $question->save();
            } else {
                $question->is_active = 'active';
                $question->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Question Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
