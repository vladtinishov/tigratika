<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function createMany($data)
    {
        return Product::query()->insert($data);
    }

    public function getCount()
    {
        return Product::query()->count();
    }

    public function getColl()
    {
        return Product::query();
    }

}
