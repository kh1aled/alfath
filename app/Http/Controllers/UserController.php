<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return users with phone and role 

        $users = User::with(["phone", "role"])->get();


        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                'unique:users,username',
            ],
            'phone' => ['nullable', 'regex:/^01[0-9]{9}$/'],
            'role_id' => ['required', 'exists:roles,id'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role_id' => $validated['role_id'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'avatar' => 'header-profile.svg',
        ]);

        if ($request->filled("phone")) {
            $user->phone()->create([
                "phone_number" => $validated['phone']
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . "." . $extension;
            $image->storeAs("users", $imageName, "public");
            $user->avatar = $imageName;
            $user->save();
        } else {
            $user->avatar = "header-profile.svg";
            $user->save();
        }

        return response()->json([
            "message" => "User Created Successfully",
            "user" => $user
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::findOrFail($id);

        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'phone' => ['nullable', 'regex:/^01[0-9]{9}$/'],
            'role_id' => ['required', 'exists:roles,id'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->role_id = $validated['role_id'];
        $user->email = $validated['email'];
        $user->address = $validated['address'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . "." . $extension;
            $image->storeAs("users", $imageName, "public");
            $user->avatar = $imageName;
        }

        $user->save();


        $phone = $user->phone()->first();

        if ($phone) {
            $phone->update([
                "phone_number" => $validated['phone']
            ]);
        } else {
            $user->phone()->create([
                "phone_number" => $validated['phone']
            ]);
        }

        return response()->json([
            "message" => "User Updated Successfully",
            "user" => $user
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::findOrFail($id);

        //if image exists
        if ($user->avatar != null && $user->avatar != 'header-profile.svg') {
            Storage::disk('public')->delete("/users" . $user->avatar);
        }

        $user->delete();
    }

    public function exportPdf(Request $request)
    {
        $users = User::with('phone')->get();
        $pdf = PDF::loadView('users.pdf', [
            'users' => $users
        ]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="users-' . time() . '.pdf"',
        ]);
    }
}
