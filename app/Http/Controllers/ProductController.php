<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('is_available', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function show(Product $product): Response
    {
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }
}
