<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProcessGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcessGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view process group');
        try {
            $processGroups = ProcessGroup::all();
            return view('dashboard.process-groups.index', compact('processGroups'));
        } catch (\Throwable $th) {
            Log::error('process groups Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create process group');
        try {
            return view('dashboard.process-groups.create');
        } catch (\Throwable $th) {
            Log::error('process groups Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create process group');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:process_groups,slug|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Process Group Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $processGroup = new ProcessGroup();
            $processGroup->name = $request->name;
            $processGroup->slug = $request->slug;
            $processGroup->description = $request->description;
            $processGroup->save();

            DB::commit();
            return redirect()->route('dashboard.process-groups.index')->with('success', 'Process Group Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Process Group Store Failed', ['error' => $th->getMessage()]);
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
        $this->authorize('update process group');
        try {
            $processGroup = ProcessGroup::findOrFail($id);
            return view('dashboard.process-groups.edit', compact('processGroup'));
        } catch (\Throwable $th) {
            Log::error('Process Group Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update process group');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:process_groups,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $processGroup = ProcessGroup::findOrFail($id);
            $processGroup->name = $request->name;
            $processGroup->slug = $request->slug;
            $processGroup->description = $request->description;
            $processGroup->save();

            return redirect()->route('dashboard.process-groups.index')->with('success', 'Process Group Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('Process Group Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete process group');
        try {
            $processGroup = ProcessGroup::findOrFail($id);
            $processGroup->delete();
            return redirect()->back()->with('success', 'Process Group Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Process Group Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update process group');
        try {
            $processGroup = ProcessGroup::findOrFail($id);
            $message = $processGroup->is_active == 'active' ? 'Process Group Deactivated Successfully' : 'Process Group Activated Successfully';
            if ($processGroup->is_active == 'active') {
                $processGroup->is_active = 'inactive';
                $processGroup->save();
            } else {
                $processGroup->is_active = 'active';
                $processGroup->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Process Group Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
