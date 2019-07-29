<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Customer;
use App\Product;
use App\Order;

class SolitaryOrderMockeryTest extends TestCase
{
    public function test_price_return_correct_value()
    {
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getPrice')
            ->once()
            ->andReturn(100);

        $customer = Mockery::mock(Customer::class);
        $customer->shouldReceive('getDiscount')
            ->once()
            ->andReturn(20);

        $order = new Order($customer, $product);

        $this->assertEquals(80, $order->price());
    }
}
