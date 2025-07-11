<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo danh mục nếu chưa có
        if (Category::count() === 0) {
            \App\Models\Category::factory()->count(5)->create();
        }

        $sampleImages = glob(database_path('seeders/images/products/*.{jpg,jpeg,png}'), GLOB_BRACE);

        // Nếu chưa có ảnh mẫu, tạo thông báo
        if (count($sampleImages) === 0) {
            $this->command->warn('⚠️ Không tìm thấy ảnh mẫu trong database/seeders/images/products/');
        }

        // Tạo 50 sản phẩm
        for ($i = 0; $i < 50; $i++) {
            $name = fake()->unique()->words(3, true);
            $slug = Str::slug($name);

            // Chọn ảnh ngẫu nhiên
            $sourceImage = $sampleImages[array_rand($sampleImages)] ?? null;
            $storedImagePath = null;

            if ($sourceImage && file_exists($sourceImage)) {
                $fileName = 'products/' . uniqid() . '.' . pathinfo($sourceImage, PATHINFO_EXTENSION);
                Storage::disk('public')->put($fileName, file_get_contents($sourceImage));
                $storedImagePath = $fileName;
            }

            Product::create([
                'category_id' => Category::inRandomOrder()->first()->id,
                'name'        => $name,
                'slug'        => $slug,
                'price'       => fake()->randomFloat(2, 10000, 500000),
                'stock'       => fake()->numberBetween(0, 100),
                'image'       => $storedImagePath,
                'description' => fake()->paragraph(),
                'featured'    => fake()->boolean(10),
                'is_active'   => fake()->boolean(90),
            ]);
        }

        $this->command->info('✅ Đã tạo 50 sản phẩm với hình ảnh thực.');
    }
}
