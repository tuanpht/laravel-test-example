<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Customer;
use App\Product;
use App\Order;
use DateTimeImmutable;

class SociableOrderTest extends TestCase
{
    public function test_price_return_correct_value()
    {
        $product = new Product('PS4', 100);
        $customer = new Customer('Jane', new DateTimeImmutable(date('Y/m/d')));
        $order = new Order($customer, $product);

        $this->assertEquals(80, $order->price());
    }
}
