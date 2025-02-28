<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable=['name','user_id'];
    protected $hidden=['created_at','updated_at'];
}
