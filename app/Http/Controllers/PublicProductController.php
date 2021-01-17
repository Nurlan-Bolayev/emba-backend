<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicProductController extends Controller
{
    public function all()
    {
        return Product::with('category', 'images')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function show(Product $product)
    {
        return $product->load('category', 'images');
    }
}
