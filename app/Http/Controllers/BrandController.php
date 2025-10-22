<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all()->map(function ($brand) {
            $brand->image = $brand->image ? asset('storage/' . $brand->image) : null;
            return $brand;
        });

        return response()->json($brands);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:brands,code',
            'country' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'website' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // صورة
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('brands', $imageName, 'public');
        }

        // توليد كود تلقائي لو مش موجود
        $code = $request->code ?: strtoupper(substr($request->name, 0, 3)) . '-' . rand(100, 999);

        $brand = Brand::create([
            'name' => $request->name,
            'code' => $code,
            'country' => $request->country,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'website' => $request->website,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => '✅ تم إضافة البراند بنجاح',
            'brand' => $brand
        ], 201);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->image = $brand->image ? asset('storage/' . $brand->image) : null;

        return response()->json($brand);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:brands,code,' . $id,
            'country' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'website' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // صورة جديدة
        if ($request->hasFile('image')) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('brands', $imageName, 'public');
            $brand->image = $imagePath;
        }

        // تحديث البيانات
        $brand->name = $request->name;
        $brand->code = $request->code ?: $brand->code;
        $brand->country = $request->country;
        $brand->description = $request->description;
        $brand->status = $request->status ?? $brand->status;
        $brand->website = $request->website;
        $brand->save();

        return response()->json([
            'message' => '✅ تم تحديث البراند بنجاح',
            'brand' => $brand
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return response()->json([
            'message' => '🗑️ تم حذف البراند بنجاح'
        ]);
    }
}
