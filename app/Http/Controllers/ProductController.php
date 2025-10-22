<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Storage;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();


        $products->map(function ($product) {
            $product->image = $product->image ? asset('storage/' . $product->image) : null;
            return $product;
        });

        return response()->json($products);
    }

    public function indexWithPagination(Request $request)
    {
        $perPage = 10;
        $query = $request->query('query');

        $productsQuery = Product::with('category');

        if ($query) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->orWhereHas('category', function ($q2) use ($query) {
                        $q2->where('name', 'like', "%$query%");
                    });
            });
        }

        $products = $productsQuery->paginate($perPage);

        $products->getCollection()->transform(function ($product) {
            $product->image = $product->image ? asset('storage/' . $product->image) : null;
            return $product;
        });



        return response()->json($products);
    }

    public function indexWithStorage(string $id)
    {
        //get all product in this storage
        $storage = Storage::findOrFail($id)->products;


        return response()->json($storage);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'nullable|integer',
            'count' => 'nullable|integer',
            'minimum_quantity' => 'nullable|integer',
            'unit' => 'nullable|string|max:255',
            'buying_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        // حفظ الصورة لو فيه
        $imagePath = null;


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        // إنشاء المنتج
        $product = Product::create([
            "name" => $request->name,
            "description" => $request->description,
            "category_id" => $request->category_id,
            "count" => $request->count,
            "minimum_quantity" => $request->minimum_quantity,
            "unit" => $request->unit,
            "buying_price" => $request->buying_price,
            "selling_price" => $request->selling_price,
            "weight" => $request->weight,
            "image" => $imagePath,
            "status" => $request->status ?? 'inactive',
        ]);

        return response()->json([
            'message' => 'تم حفظ المنتج بنجاح',
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $product->image = $product->image ? asset('storage/' . $product->image) : null;
        return response()->json($product);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'nullable|integer',
            'count' => 'nullable|integer',
            'minimum_quantity' => 'nullable|integer',
            'unit' => 'nullable|string|max:255',
            'buying_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        // 1. جيب المنتج
        $product = Product::findOrFail($id);

        // 2. حفظ الصورة الجديدة لو فيه صورة
        if ($request->hasFile('image')) {
            //delete image if exists

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            //create image if not exists
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $product->image = $imagePath;
        }

        // 3. تحديث باقي البيانات
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'count' => $request->count,
            'minimum_quantity' => $request->minimum_quantity,
            'unit' => $request->unit,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'weight' => $request->weight,
            'status' => $request->status ?? 'inactive',
        ]);

        return response()->json([
            'message' => 'تم تحديث المنتج بنجاح',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
    }

    public function exportPdf(Request $request)
    {
        $products = Product::all();

        $pdf = PDF::loadView('products.pdf', [
            'products' => $products
        ]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="products-' . time() . '.pdf"',
        ]);
    }
}
