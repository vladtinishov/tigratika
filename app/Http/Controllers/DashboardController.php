<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private ProductRepository $productRepo;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    public function index()
    {
        $title = 'Дашборд';
        $productsCount = $this->productRepo->getCount();

        return view('pages.dashboard.index')->with([
            'title' => $title,
            'productsCount' => $productsCount
        ]);
    }
}
