<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Customer;
use App\Product;
use App\Order;

class SolitaryOrderTest extends TestCase
{
    public function test_price_return_correct_value()
    {
        $product = $this->createMock(Product::class);
        $product->expects($this->once())
            ->method('getPrice')
            ->will($this->returnValue(100));

        $customer = $this->createMock(Customer::class);
        $customer->expects($this->once())
            ->method('getDiscount')
            ->will($this->returnValue(20));

        $order = new Order($customer, $product);

        $this->assertEquals(80, $order->price());
    }
}
