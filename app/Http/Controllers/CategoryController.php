<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all()->map(function ($product) {
            $product->image = asset('storage/' . $product->image);
            return $product;
        });


        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('categories', $imageName, 'public');
        }

        $category = Category::create([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $imagePath,
        ]);

        return response()->json([
            'message' => 'تم حفظ الصنف بنجاح',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = Category::findOrFail($id);
        $category->image = asset('storage/' . $category->image);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $category = Category::findOrFail($id);

        // لو في صورة جديدة
        if ($request->hasFile("image")) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file("image");
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs("categories", $imageName, "public");
            $category->image = $imagePath;
        }

        // تحديث البيانات بدون تغيير الصورة لو مفيش صورة جديدة
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'message' => 'تم تحديث الصنف بنجاح',
            'category' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::findOrFail($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'message' => 'تم حذف الصنف بنجاح'
        ]);
    }
}
