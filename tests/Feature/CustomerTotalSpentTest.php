<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTotalSpentTest extends TestCase
{
    use RefreshDatabase;

    public function test_total_spent_includes_valid_orders_and_excludes_reversed_orders(): void
    {
        $user = User::factory()->create();

        $this->createOrder($user, 'pending', 'unpaid', 1200);
        $this->createOrder($user, 'delivered', 'paid', 2500);
        $this->createOrder($user, 'cancelled', 'unpaid', 900);
        $this->createOrder($user, 'returned', 'paid', 700);
        $this->createOrder($user, 'refunded', 'refunded', 600);

        $this->assertSame(3700.0, $user->total_spent);
    }

    private function createOrder(
        User $user,
        string $status,
        string $paymentStatus,
        int $total,
    ): void {
        $user->orders()->create([
            'order_number' => 'GNG-' . fake()->unique()->numerify('########'),
            'status' => $status,
            'payment_status' => $paymentStatus,
            'payment_method' => 'cod',
            'subtotal' => $total,
            'discount_amount' => 0,
            'shipping_amount' => 0,
            'tax_amount' => 0,
            'total' => $total,
            'shipping_address' => [
                'full_name' => 'Test Customer',
                'phone' => '01700000000',
                'address_line_1' => 'Dhaka',
                'city' => 'Dhaka',
                'district' => 'Dhaka',
                'division' => 'Dhaka',
            ],
        ]);
    }
}
