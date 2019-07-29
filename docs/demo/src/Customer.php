<?php
namespace App;

class Customer
{
    /** @var string */
    private $name;

    /** @var \DateTimeImmutable */
    private $birthday;

    public function __construct(string $name, \DateTimeImmutable $birthday)
    {
        $this->name = $name;
        $this->birthday = $birthday;
    }

    /**
     * Discount on birthday
     *
     * @return numeric Discount percentage
     */
    public function getDiscount()
    {
        return $this->birthday->format('md') == date('md') ? 20 : 0;
    }
}
