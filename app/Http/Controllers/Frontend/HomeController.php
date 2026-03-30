<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
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
}
