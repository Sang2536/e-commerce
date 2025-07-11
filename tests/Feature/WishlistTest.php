<?php

namespace Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_wishlist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wishlist.index');
    }

    public function test_user_can_add_product_to_wishlist()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('wishlist.add', $product));

        $response->assertRedirect();
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_cannot_add_same_product_to_wishlist_twice()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('wishlist.add', $product));

        $response->assertRedirect();
        $this->assertDatabaseCount('wishlists', 1);
    }

    public function test_user_can_remove_product_from_wishlist()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('wishlist.remove', $product));

        $response->assertRedirect();
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_guest_cannot_access_wishlist()
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect(route('login'));
    }
}
