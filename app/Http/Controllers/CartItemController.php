<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active'],
            ['total_price' => 0]
        );

        $item = CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $validated['product_id']],
            ['amount' => 0, 'total_price_item' => 0]
        );

        $item->increment('amount', $validated['amount']);
        $item->update([
            'total_price_item' => $item->amount * $item->product->price
        ]);

        $total = $cart->items()->sum('total_price_item');
        $cart->update(['total_price' => $total]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        $cart = $cartItem->cart;

        // hapus item
        $cartItem->delete();

        // hitung ulang total cart
        $total = $cart->items()->sum('total_price_item');
        $cart->update(['total_price' => $total]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function decrease(Request $request, CartItem $cartItem)
    {
        $cart = $cartItem->cart;

        if ($cartItem->amount > 1) {
            $cartItem->decrement('amount', 1);
            $cartItem->update([
                'total_price_item' => $cartItem->amount * $cartItem->product->price
            ]);
        } else {
            // kalau jumlah 1 → hapus item
            $cartItem->delete();
        }

        // hitung ulang total keranjang
        $total = $cart->items()->sum('total_price_item');
        $cart->update(['total_price' => $total]);

        return back()->with('success', 'Produk berhasil dikurangi dari keranjang.');
    }
}
