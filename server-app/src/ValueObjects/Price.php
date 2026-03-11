<?php

namespace App\ValueObjects;

use App\Enums\Currency;

/**
 * Class Price
 * @package App\ValueObjects
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class Price
{
    /**
     * @param float $base
     * @param float $withDiscount
     * @param Currency $currency
     */
    public function __construct(
        private float    $base,
        private float    $withDiscount,
        private Currency $currency
    ) {}

    /**
     * @return float
     */
    public function base(): float
    {
        return $this->base;
    }

    /**
     * @return float
     */
    public function withDiscount(): float
    {
        return $this->withDiscount;
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }
}
