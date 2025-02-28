<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProduct extends Model
{
    protected $fillable = ['invoice_id', 'user_id', 'product_id', 'qty', 'sale_price'];

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}
