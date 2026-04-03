<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Approach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApproachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view approach');
        try {
            $approaches = Approach::all();
            return view('dashboard.approaches.index', compact('approaches'));
        } catch (\Throwable $th) {
            Log::error('approaches Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create approach');
        try {
            return view('dashboard.approaches.create');
        } catch (\Throwable $th) {
            Log::error('approaches Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create approach');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:approaches,slug|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Approach Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $approach = new Approach();
            $approach->name = $request->name;
            $approach->slug = $request->slug;
            $approach->description = $request->description;
            $approach->save();

            DB::commit();
            return redirect()->route('dashboard.approaches.index')->with('success', 'Approach Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Approach Store Failed', ['error' => $th->getMessage()]);
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
        $this->authorize('update approach');
        try {
            $approach = Approach::findOrFail($id);
            return view('dashboard.approaches.edit', compact('approach'));
        } catch (\Throwable $th) {
            Log::error('approach Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update approach');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:approaches,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $approach = Approach::findOrFail($id);
            $approach->name = $request->name;
            $approach->slug = $request->slug;
            $approach->description = $request->description;
            $approach->save();

            return redirect()->route('dashboard.approaches.index')->with('success', 'Approach Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('Approach Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete approach');
        try {
            $approach = Approach::findOrFail($id);
            $approach->delete();
            return redirect()->back()->with('success', 'Approach Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Approach Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update approach');
        try {
            $approach = Approach::findOrFail($id);
            $message = $approach->is_active == 'active' ? 'Approach Deactivated Successfully' : 'Approach Activated Successfully';
            if ($approach->is_active == 'active') {
                $approach->is_active = 'inactive';
                $approach->save();
            } else {
                $approach->is_active = 'active';
                $approach->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Approach Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
