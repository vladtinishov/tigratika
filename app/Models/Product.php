<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'name',
        'available',
        'url',
        'price',
        'oldprice',
        'currencyId',
        'categoryId',
        'picture',
        'vendor',
    ];
}
