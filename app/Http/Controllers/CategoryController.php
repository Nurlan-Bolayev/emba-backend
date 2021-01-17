<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function all()
    {
        return Category::with('creator')->get();
    }

    public function get(Category $category)
    {
        return $category->load('products');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:5',
        ]);

        return Category::forceCreate(array_merge($data, [
            'slug' => Str::slug($data['name']),
            'creator_id' => $request->user()->id,
        ]));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|min:5',
        ]);


        $category->forceFill(array_merge($data, [
            'slug' => Str::slug($data['name']),
        ]))->save();
        return $category;
    }

    public function delete(Category $category)
    {
        $category->delete();
        return 'Deleted.';
    }
}
