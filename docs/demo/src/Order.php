<?php
namespace App;

class Order
{
    /** @var Customer */
    private $customer;

    /** @var Product */
    private $product;

    public function __construct(Customer $customer, Product $product)
    {
        $this->customer = $customer;
        $this->product = $product;
    }

    /**
     * Order price
     *
     * @return numeric
     */
    public function price()
    {
        $price = $this->product->getPrice();
        if (!$discount = $this->customer->getDiscount()) {
            return $price;
        }

        return $price - $discount / 100 * $price;
    }
}
