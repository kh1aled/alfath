<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $employees = Employee::with("phone")->get();
        $employees->each(
            function ($employee) {
                $employee->photo = $employee->photo ? asset('storage/' . $employee->photo) : null; // Convert photo path to URL
            }
        );
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate the request data
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:employees,email",
            "username" => "required|string|max:255|unique:employees,username",
            "password" => "required|string|min:8",
            "role" => "required|string|max:50",
            "status" => "required|in:active,inactive",
            "address" => "nullable|string|max:255",
            "date_of_birth" => "nullable|date",
            "hire_date" => "required|date",
            "salary" => "required|numeric|min:0",
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "phone" => "nullable|numeric|digits_between:10,15",
        ]);

        // If a photo is uploaded, handle the file upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public disk under 'employees' directory
            $photoPath = $image->storeAs('employees', $imageName,  'public');
            $validatedData['photo'] = $photoPath;
        }

        // Create the employee record
        $employee = Employee::create([
            "name" => $validatedData['name'],
            "email" => $validatedData['email'],
            "username" => $validatedData['username'],
            "password" => bcrypt($validatedData['password']), // Hash the password
            "role" => $validatedData['role'],
            "status" => $validatedData['status'],
            "address" => $validatedData['address'] ?? null,
            "date_of_birth" => $validatedData['date_of_birth'] ?? null,
            "hire_date" => $validatedData['hire_date'],
            "salary" => $validatedData['salary'],
            "photo" => $validatedData['photo'] ?? null,
        ]);

        // If a phone number is provided, create a new phone record

        if ($request->filled('phone')) {
            $employee->phone()->create([
                'phone_number' => $validatedData['phone'],
            ]);
        }

        return response()->json([
            'message' => 'Saved employee successfully',
            'employee' => $employee->load('phone'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $employee = Employee::with('phone')->findOrFail($id);
        $employee->photo = $employee->photo ? asset('storage/' . $employee->photo) : null; // Convert photo path to URL
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "username" => "required|string|max:255",
            "role" => "required|string|max:50",
            "status" => "required|in:active,inactive",
            "address" => "nullable|string|max:255",
            "date_of_birth" => "nullable|date",
            "hire_date" => "required|date",
            "salary" => "required|numeric|min:0",
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "phone" => "nullable|numeric|digits_between:10,15",
        ]);

        $employee = Employee::findOrFail($id);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . "." . $image->getClientOriginalExtension();
            $photoPath = $image->storeAs('employees', $imageName, 'public');
            $validatedData['photo'] = $photoPath;
        }

        $updateData = [
            "name" => $validatedData['name'],
            "email" => $validatedData['email'],
            "username" => $validatedData['username'],
            "role" => $validatedData['role'],
            "status" => $validatedData['status'],
            "address" => $validatedData['address'] ?? null,
            "date_of_birth" => $validatedData['date_of_birth'] ?? null,
            "hire_date" => $validatedData['hire_date'],
            "salary" => $validatedData['salary'],
            "photo" => $validatedData['photo'] ?? $employee->photo,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $employee->update($updateData);

        if ($request->filled('phone')) {
            $employee->phone()->updateOrCreate(
                [],
                ['phone_number' => $validatedData['phone']]
            );
        }

        return response()->json([
            'message' => 'تم تحديث بيانات الموظف بنجاح',
            'employee' => $employee->load('phone'),
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //delete employee
        $employee = Employee::findOrFail($id);
        $employee->delete();

        //delete the associated phone records
        $employee->phone()->delete();
    }
}
