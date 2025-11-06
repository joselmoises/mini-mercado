<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Banana',
                'description' => 'Bananas frescas e maduras, ricas em potássio.',
                'price' => 45.00,
                'stock' => 100,
                'image' => 'https://images.unsplash.com/photo-1603833665858-e61d17a86224?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Maçã',
                'description' => 'Maçãs vermelhas crocantes e suculentas.',
                'price' => 60.00,
                'stock' => 80,
                'image' => 'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Laranja',
                'description' => 'Laranjas doces e sumarenta, perfeitas para sumo.',
                'price' => 35.00,
                'stock' => 120,
                'image' => 'https://images.unsplash.com/photo-1582979512210-99b6a53386f9?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Manga',
                'description' => 'Mangas tropicais doces e aromáticas.',
                'price' => 80.00,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Morango',
                'description' => 'Morangos frescos e doces.',
                'price' => 120.00,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Uva',
                'description' => 'Uvas sem sementes, doces e crocantes.',
                'price' => 150.00,
                'stock' => 60,
                'image' => 'https://images.unsplash.com/photo-1537640538966-79f369143f8f?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Abacaxi',
                'description' => 'Abacaxi tropical fresco e suculento.',
                'price' => 90.00,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Melancia',
                'description' => 'Melancia vermelha e refrescante.',
                'price' => 70.00,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e7845e3?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Papaia',
                'description' => 'Papaia madura e doce.',
                'price' => 55.00,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1617112848923-cc2234396a8d?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Kiwi',
                'description' => 'Kiwis verdes e refrescantes.',
                'price' => 100.00,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1585059895524-72359e06133a?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Pêra',
                'description' => 'Pêras suculentas e doces.',
                'price' => 65.00,
                'stock' => 70,
                'image' => 'https://images.unsplash.com/photo-1568570695917-5093607c6a89?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Cereja',
                'description' => 'Cerejas frescas e deliciosas.',
                'price' => 180.00,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1528821128474-27f963b062bf?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Ameixa',
                'description' => 'Ameixas maduras e suculentas.',
                'price' => 95.00,
                'stock' => 55,
                'image' => 'https://images.unsplash.com/photo-1574856344991-aaa31b6f4ce3?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Framboesa',
                'description' => 'Framboesas frescas e aromáticas.',
                'price' => 200.00,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1577069861033-55d04cec4ef5?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Melão',
                'description' => 'Melão doce e refrescante.',
                'price' => 75.00,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1571575173700-afb9492e6a50?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Limão',
                'description' => 'Limões frescos e aromáticos.',
                'price' => 30.00,
                'stock' => 150,
                'image' => 'https://images.unsplash.com/photo-1590502593747-42a996133562?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Tangerina',
                'description' => 'Tangerinas doces e fáceis de descascar.',
                'price' => 40.00,
                'stock' => 90,
                'image' => 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Coco',
                'description' => 'Cocos frescos e naturais.',
                'price' => 85.00,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Pêssego',
                'description' => 'Pêssegos macios e doces.',
                'price' => 110.00,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1629828874514-944c3acf2d37?w=500',
                'is_available' => true,
            ],
            [
                'name' => 'Romã',
                'description' => 'Romãs frescas com sementes suculentas.',
                'price' => 130.00,
                'stock' => 28,
                'image' => 'https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a?w=500',
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
