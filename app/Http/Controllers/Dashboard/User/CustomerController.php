<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $this->authorize('view user');
        try {
            $customers = User::with('profile')->role('user')->get();
            return view('dashboard.users.customers.index', compact('customers'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Customer Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function show(string $id)
    {
        $this->authorize('view user');
        try {
            $customer = User::with('profile', 'userBillings', 'userPricings', 'transactions')->findOrFail($id);
            return view('dashboard.users.customers.show', compact('customer'));
        } catch (\Throwable $th) {
            Log::error('Customer Show Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
