<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view topic');
        try {
            $topics = Topic::all();
            return view('dashboard.topics.index', compact('topics'));
        } catch (\Throwable $th) {
            Log::error('topics Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create topic');
        try {
            return view('dashboard.topics.create');
        } catch (\Throwable $th) {
            Log::error('topics Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create topic');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:topics,slug|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Topic Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $topic = new Topic();
            $topic->name = $request->name;
            $topic->slug = $request->slug;
            $topic->description = $request->description;
            $topic->save();

            DB::commit();
            return redirect()->route('dashboard.topics.index')->with('success', 'Topic Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Topic Store Failed', ['error' => $th->getMessage()]);
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
        $this->authorize('update topic');
        try {
            $topic = Topic::findOrFail($id);
            return view('dashboard.topics.edit', compact('topic'));
        } catch (\Throwable $th) {
            Log::error('topic Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update topic');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:topics,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $topic = Topic::findOrFail($id);
            $topic->name = $request->name;
            $topic->slug = $request->slug;
            $topic->description = $request->description;
            $topic->save();

            return redirect()->route('dashboard.topics.index')->with('success', 'Topic Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('Topic Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete topic');
        try {
            $topic = Topic::findOrFail($id);
            $topic->delete();
            return redirect()->back()->with('success', 'Topic Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Topic Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update topic');
        try {
            $topic = Topic::findOrFail($id);
            $message = $topic->is_active == 'active' ? 'Topic Deactivated Successfully' : 'Topic Activated Successfully';
            if ($topic->is_active == 'active') {
                $topic->is_active = 'inactive';
                $topic->save();
            } else {
                $topic->is_active = 'active';
                $topic->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Topic Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
