<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $customers = Customer::with('phone')->get();

        return response()->json($customers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);


        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'zip_code' => $validated['zip_code'] ?? null,
        ]);

        if (!empty($validated['phone'])) {
            $customer->phone()->create([
                'phone_number' => $validated['phone'],
            ]);
        }


        return response()->json([
            'message'  => 'تم حفظ العميل بنجاح',
            'customer' => $customer->load('phone'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //find customer by id with her phone numbers 
        $customer = Customer::with("phone")->findOrFail($id);


        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ]);


        // 1. جيب العميل
        $customer = Customer::findOrFail($id);


        // 3. تحديث باقي البيانات
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'zip_code' => $request->zip_code,
        ]);

        if ($request->filled("phone")) {
            $customer->phone()->updateOrCreate(
                [],
                ["phone_number" => $request->phone]
            );
        }

        return response()->json([
            'message' => 'تم تحديث بيانات العميل بنجاح',
            'customer' => $customer->load('phone')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $customer = Customer::findOrFail($id);
        $customer->delete();
    }

    public function exportPdf(Request $request)
    {
        $customers = Customer::all();

        $pdf = PDF::loadView('customers.pdf', [
            'customers' => $customers
        ]);

        $pdf->setOption('footer-html', view('pdf.footer')->render());


        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="customers-' . time() . '.pdf"',
        ]);
    }
}
