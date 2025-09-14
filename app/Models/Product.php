<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_name', 'product_image', 'description', 'price', 'stock'];

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }
}
