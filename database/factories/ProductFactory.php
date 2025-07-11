<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name'        => $name,
            'slug'        => Str::slug($name),
            'price'       => $this->faker->randomFloat(2, 10000, 500000),
            'stock'       => $this->faker->numberBetween(0, 100),
            'image'       => $this->faker->imageUrl(640, 640, 'technics', true), // bạn có thể thay bằng đường dẫn lưu ảnh thực
            'description' => $this->faker->paragraph(),
            'featured'    => $this->faker->boolean(10),
            'is_active'   => $this->faker->boolean(90),
        ];
    }
}
