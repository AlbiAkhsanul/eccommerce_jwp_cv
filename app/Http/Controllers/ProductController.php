<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('seller')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);


        if ($request->hasFile('product_image')) {
            $validated['product_image'] = $request->file('product_image')->store('products', 'public');
        }

        $validated['user_id'] = Auth::id();

        Product::create($validated);

        return redirect()->route('dashboard')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('seller');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        if ($request->hasFile('product_image')) {
            // Hapus gambar lama jika ada
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            // Simpan gambar baru
            $validated['product_image'] = $request->file('product_image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('dashboard')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus gambar produk dari storage jika ada
        if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
            Storage::disk('public')->delete($product->product_image);
        }
        $product->delete();
        return redirect()->route('dashboard')->with('success', 'Produk berhasil dihapus.');
    }
}
