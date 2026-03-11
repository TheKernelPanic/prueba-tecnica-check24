<?php

namespace App\ValueObjects;

use App\Enums\CarForm;
use App\Enums\CarUse;

/**
 * Class PricingRequest
 * @package App\ValueObjects
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class PricingRequest
{
    /**
     * @param int $driverAge
     * @param CarForm $carForm
     * @param CarUse $carUse
     */
    public function __construct(
        private int $driverAge,
        private CarForm $carForm,
        private CarUse $carUse,
    ) {}

    /**
     * @return int
     */
    public function driverAge(): int
    {
        return $this->driverAge;
    }

    /**
     * @return CarForm
     */
    public function carForm(): CarForm
    {
        return $this->carForm;
    }

    /**
     * @return CarUse
     */
    public function carUse(): CarUse
    {
        return $this->carUse;
    }
}
