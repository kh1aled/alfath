<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::all();

        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        //
        $validated = $request->validated();


        $role = Role::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => "Role Created Successfully",
            'role' => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();

        $role->name = $validated['name'];
        $role->save();

        return response()->json([
            'message' => "Role Updated Successfully",
            'role' => $role
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return response()->json([
                'message' => 'Role deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the role.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function exportPdf(Request $request)
    {
        $roles = Role::all();
        $pdf = PDF::loadView('roles.pdf', [
            'roles' => $roles
        ]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="roles-' . time() . '.pdf"',
        ]);
    }
}
