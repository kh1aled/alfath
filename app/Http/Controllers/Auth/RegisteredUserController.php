<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                'unique:users,username',
            ],
            'phone' => ['required', 'regex:/^01[0-9]{9}$/'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if($request->filled("phone")){
            Phone::create(['phone_number' => $request->phone]);
        }

        $user = User::create([
            'name' => $request->name,
            'avatar' => "header-profile.svg",
            'email' => $request->email,
            'username' => $request->username,
            'address' => $request->address,
            'password' => Hash::make($request->string('password')),
        ]);

         // If a phone number is provided, create a new phone record

        if ($request->filled('phone')) {
            $user->phone()->create([
                'phone_number' => $validatedData['phone'],
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
