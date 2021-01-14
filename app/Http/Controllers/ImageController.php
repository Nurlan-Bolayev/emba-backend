<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $attrs = $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $image = $request->file('image');
        $meta = json_encode([
            'original_name' => $image->getClientOriginalName(),
            'resolution' => [
                'height' => getimagesize($image)[1],
                'width' => getimagesize($image)[0],
            ],
            'size' => $image->getSize(),
        ]);

        $body = [
            'creator_id' => $request->user()->id,
            'product_id' => $product->id,
            'path' => $image->store('uploads'),
            'meta' => $meta,
        ];

        return Image::forceCreate($body);
    }

    public function delete(Request $request, Image $image)
    {
        Storage::delete($image->path);

        $image->delete();

        return 'done';
    }
}
