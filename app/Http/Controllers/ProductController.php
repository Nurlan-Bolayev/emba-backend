<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3|max:2000',
            'category_id' => 'required|int|exists:categories,id',
        ]);

        return Product::forceCreate(array_merge($attrs, [
            'slug' => Str::slug($attrs['name']),
            'creator_id' => $request->user()->id,
        ]));
    }

    public function update(Request $request, Product $product)
    {
        $attrs = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3|max:2000',
            'category_id' => 'required|int|exists:categories,id',
        ],[

        ]);
         $product->forceFill($attrs)->save();
         return $product;
    }

    public function delete(Product $product)
    {

        $product->delete();

        return 'done';
    }
}
