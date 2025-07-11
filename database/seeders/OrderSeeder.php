<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Vui lòng tạo Customer và Product trước khi seed đơn hàng.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            $orderItems = [];

            $subtotal = 0;

            foreach ($products->random(rand(1, 5)) as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $total = $quantity * $price;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'total'      => $total,
                ];

                $subtotal += $total;
            }

            $tax = $subtotal * 0.1;
            $shipping = rand(15000, 30000);
            $total = $subtotal + $tax + $shipping;

            $order = Order::create([
                'customer_id'      => $customer->id, // 🟢 cập nhật customer_id
                'order_number'     => strtoupper(Str::random(10)),
                'status'           => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
                'payment_status'   => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
                'payment_method'   => fake()->randomElement(['cod', 'bank_transfer']),

                'shipping_name'    => $customer->name,
                'shipping_email'   => $customer->email,
                'shipping_phone'   => $customer->phone ?? fake()->phoneNumber(),
                'shipping_address' => $customer->address ?? fake()->address(),
                'shipping_city'    => $customer->city ?? fake()->city(),
                'shipping_state'   => $customer->state ?? fake()->state(),
                'shipping_zip_code'=> $customer->zip_code ?? fake()->postcode(),
                'notes'            => fake()->optional()->sentence(),

                'subtotal'         => $subtotal,
                'tax'              => $tax,
                'shipping_cost'    => $shipping,
                'total'            => $total,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }
        }
    }
}
