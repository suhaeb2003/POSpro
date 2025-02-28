<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['user_id','categorie_id','name','price','unit','image_url'];
    function categorie(){
        return $this->belongsTo(Categorie::class);
    }
    protected $hidden=['create_at','updated_at'];
}
