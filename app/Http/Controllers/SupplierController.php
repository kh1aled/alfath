<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with("phone")->get();

        return response()->json($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'city'     => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'zip_code' => $validated['zip_code'] ?? null,
        ]);


        if (!empty($validated['phone'])) {
            $supplier->phone()->create(
                [
                    'phone_number' => $validated['phone'],
                ]
            );
        }

        return response()->json([
            'message'  => 'تم حفظ المورد بنجاح',
            'supplier' => $supplier,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::with("phone")->findOrFail($id);

        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string|max:255',
            'city'     => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update($validated);

        return response()->json([
            'message'  => 'تم تحديث بيانات المورد بنجاح',
            'supplier' => $supplier,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json([
            'message' => 'تم حذف المورد بنجاح',
        ]);
    }

    public function exportPdf()
    {
        $suppliers = Supplier::all();

        $pdf = PDF::loadView('suppliers.pdf', [
            'suppliers' => $suppliers
        ]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="suppliers-' . time() . '.pdf"',
        ]);
    }
}
