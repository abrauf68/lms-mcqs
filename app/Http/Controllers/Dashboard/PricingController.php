<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view pricing');
        try {
            $pricings = Pricing::all();
            return view('dashboard.pricings.index', compact('pricings'));
        } catch (\Throwable $th) {
            Log::error('pricing Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create pricing');
        try {
            $uniqueFeatures = collect(Pricing::pluck('features'))
                ->map(function ($item) {
                    return json_decode($item, true); // Convert string to array
                })
                ->flatten()
                ->filter() // remove nulls
                ->unique()
                ->values()
                ->all();
            return view('dashboard.pricings.create', compact('uniqueFeatures'));
        } catch (\Throwable $th) {
            Log::error('pricings Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create pricing');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:pricings,slug|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:monthly,yearly',
            'duration' => 'required|integer|min:1',
            'tag' => 'nullable|string|max:255',
            'features' => 'required',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Pricing Store Validation Failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $pricing = new Pricing();
            $pricing->name = $request->name;
            $pricing->slug = $request->slug;
            $pricing->price = $request->price;
            $pricing->type = $request->type;
            $pricing->duration = $request->duration;
            $pricing->tag = $request->tag;
            if($request->features)
            {
                $features = json_encode(
                    collect(json_decode($request->features, true))->pluck('value')->all()
                );
                $pricing->features = $features;
            }
            $pricing->description = $request->description;
            $pricing->save();

            DB::commit();
            return redirect()->route('dashboard.pricings.index')->with('success', 'Pricing Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Pricing Store Failed', ['error' => $th->getMessage()]);
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
        $this->authorize('update pricing');
        try {
            $pricing = Pricing::findOrFail($id);
            $uniqueFeatures = collect(Pricing::pluck('features'))
                ->map(function ($item) {
                    return json_decode($item, true); // Convert string to array
                })
                ->flatten()
                ->filter() // remove nulls
                ->unique()
                ->values()
                ->all();
            return view('dashboard.pricings.edit', compact('pricing', 'uniqueFeatures'));
        } catch (\Throwable $th) {
            Log::error('Pricing Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update pricing');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:pricings,slug,' . $id,
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:monthly,yearly',
            'duration' => 'required|integer|min:1',
            'tag' => 'nullable|string|max:255',
            'features' => 'required',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $pricing = Pricing::findOrFail($id);
            $pricing->name = $request->name;
            $pricing->slug = $request->slug;
            $pricing->price = $request->price;
            $pricing->type = $request->type;
            $pricing->duration = $request->duration;
            $pricing->tag = $request->tag;
            if($request->features)
            {
                $features = json_encode(
                    collect(json_decode($request->features, true))->pluck('value')->all()
                );
                $pricing->features = $features;
            }
            $pricing->description = $request->description;
            $pricing->save();

            return redirect()->route('dashboard.pricings.index')->with('success', 'Pricing Updated Successfully');
        } catch (\Throwable $th) {
            Log::error('Pricing Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete pricing');
        try {
            $pricing = Pricing::findOrFail($id);
            $pricing->delete();
            return redirect()->back()->with('success', 'Pricing Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Pricing Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update pricing');
        try {
            $pricing = Pricing::findOrFail($id);
            $message = $pricing->is_active == 'active' ? 'Pricing Deactivated Successfully' : 'Pricing Activated Successfully';
            if ($pricing->is_active == 'active') {
                $pricing->is_active = 'inactive';
                $pricing->save();
            } else {
                $pricing->is_active = 'active';
                $pricing->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Pricing Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
