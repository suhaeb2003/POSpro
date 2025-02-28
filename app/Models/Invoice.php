<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable=['total','discount','vat','payable','user_id','customer_id'];
    function customer():BelongsTo{
        return $this->belongsTo(Customer::class);
    }
}
