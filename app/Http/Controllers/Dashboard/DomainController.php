<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view domain');
        try {
            $domains = Domain::all();
            return view('dashboard.domains.index', compact('domains'));
        } catch (\Throwable $th) {
            Log::error('domains Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create domain');
        try {
            return view('dashboard.domains.create');
        } catch (\Throwable $th) {
            Log::error('domains Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create domain');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:domains,slug|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Domain Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $domain = new Domain();
            $domain->name = $request->name;
            $domain->slug = $request->slug;
            $domain->description = $request->description;
            $domain->save();

            DB::commit();
            return redirect()->route('dashboard.domains.index')->with('success', 'Domain Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Domain Store Failed', ['error' => $th->getMessage()]);
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
        $this->authorize('update domain');
        try {
            $domain = Domain::findOrFail($id);
            return view('dashboard.domains.edit', compact('domain'));
        } catch (\Throwable $th) {
            Log::error('domain Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update domain');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:domains,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $domain = Domain::findOrFail($id);
            $domain->name = $request->name;
            $domain->slug = $request->slug;
            $domain->description = $request->description;
            $domain->save();

            return redirect()->route('dashboard.domains.index')->with('success', 'Domain Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('Domain Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete domain');
        try {
            $domain = Domain::findOrFail($id);
            $domain->delete();
            return redirect()->back()->with('success', 'Domain Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Domain Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update domain');
        try {
            $domain = Domain::findOrFail($id);
            $message = $domain->is_active == 'active' ? 'Domain Deactivated Successfully' : 'Domain Activated Successfully';
            if ($domain->is_active == 'active') {
                $domain->is_active = 'inactive';
                $domain->save();
            } else {
                $domain->is_active = 'active';
                $domain->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Domain Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
