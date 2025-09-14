<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    public function checkout(Cart $cart)
    {
        return DB::transaction(function () use ($cart) {
            $transaction = Transaction::create([
                'user_id' => $cart->user_id,
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

                $item->product->decrement('stock', $item->amount);
                $item->product->seller->increment('balance', $item->total_price_item);
            }

            $cart->update(['status' => 'checked_out']);

            return $transaction;
        });
    }
}
