<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('details.product')->where('user_id', Auth::id())->get();
        return view('transactions.index', compact('transactions'));
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
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->firstOrFail();

        DB::transaction(function () use ($cart) {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_price' => $cart->items->sum('total_price_item'),
                'transaction_date' => now(),
                'status' => 'success',
            ]);

            foreach ($cart->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'amount' => $item->amount,
                    'total_price_item' => $item->total_price_item,
                ]);

                // update stok produk
                $item->product->decrement('stock', $item->amount);

                // update saldo penjual
                $item->product->seller->increment('balance', $item->total_price_item);
            }

            $cart->items()->delete();
            $cart->update(['total_price' => 0]);
        });

        return redirect()->route('transactions.index')->with('success', 'Checkout berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function checkout()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        DB::transaction(function () use ($cart) {
            // buat transaksi
            $transaction = Transaction::create([
                'user_id' => $cart->user_id,
                'total_price' => $cart->items->sum('total_price_item'),
                'transaction_date' => now(),
                'status' => 'success',
            ]);

            // buat detail transaksi
            foreach ($cart->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'amount' => $item->amount,
                    'total_price_item' => $item->total_price_item,
                ]);

                // update stok produk
                $item->product->decrement('stock', $item->amount);

                // update saldo seller
                $item->product->seller->increment('balance', $item->total_price_item);
            }

            // tandai cart sudah checkout
            $cart->update(['status' => 'checked_out']);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Checkout berhasil, cart dipindahkan ke histori!');
    }
}
