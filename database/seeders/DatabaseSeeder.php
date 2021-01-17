<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'Samir Nabiyev',
            'email' => 'snebiyev@gmail.com',
            'password' => \Hash::make('samir12'),
        ]);

        collect([
            'YATAQ DƏSTİ',
            'QONAQ DƏSTİ',
            'YUMŞAQ MEBEL',
            'STUL VƏ MASALAR',
            'DƏHLİZ DƏSTİ',
            'MƏTBƏX DƏSTİ',
            'UŞAQ DƏSTİ',
            'DÖŞƏK',
        ])->each(fn($name) => Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'creator_id' => $admin->id,
        ]));


        Product::create([
            'name' => 'ARİZONA',
            'description' => "Dizayn:Loft
Rəng:Qara mərmər və ağac tekstura
Material:DSP, dəmir dəstək, fasad və ayaqlar, parça və dəri başlıq, metal dekor, fume güzgü
Kupe dolab: 2400 x 2178 x 624
Paltar dolabı 1-qapılı: 500x2178x617-KhFF
Kamod: 1000 x 820 x 447
Çarpayı: 1864 x 1100 x 2095
Tumba: 600 x 436 x 447
Güzgü: 802 x 802 x 40",
            'price' => 2529,
            'slug' => Str::slug('ARİZONA'),
            'category_id' => Category::first()->id,
            'creator_id' => $admin->id,
        ]);
    }
}
