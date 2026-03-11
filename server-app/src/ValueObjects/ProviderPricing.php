<?php

namespace App\ValueObjects;

/**
 * Class ProviderClientResponse
 * @package App\ValueObjects
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class ProviderPricing
{
    /**
     * @param string $name
     * @param Price $price
     */
    public function __construct(
        private string $name,
        private Price  $price,
    ) {}

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Price
     */
    public function price(): Price
    {
        return $this->price;
    }
}
