<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function all()
    {
        return Product::with('category', 'creator')->get();
    }

    public function show(Product $product)
    {
        return $product->load('images');
    }

    public function create(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'price' => 'required|float|min:0',
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
            'price' => 'required|float|min:0',
            'description' => 'required|string|min:3|max:2000',
            'category_id' => 'required|int|exists:categories,id',
        ]);

        $body = [
            'slug' => Str::slug($attrs['name']),
        ];

        $product->forceFill(array_merge($attrs, $body))->save();
        return $product;
    }

    public function delete(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::delete($image->path);
            $image->delete();
        }

        $product->delete();

        return 'done';
    }
}
