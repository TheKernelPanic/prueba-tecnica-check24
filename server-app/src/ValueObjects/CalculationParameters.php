<?php

namespace App\ValueObjects;

use App\Enums\CarForm;
use App\Enums\CarUse;

/**
 * Class CalculationParameters
 * @package App\ValueObjects
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class CalculationParameters
{
    /**
     * @param int $age
     * @param CarForm $carForm
     * @param CarUse $carUse
     */
    public function __construct(
       private int $age,
       private CarForm $carForm,
       private CarUse $carUse
    ) {
    }

    /**
     * @return int
     */
    public function age(): int
    {
        return $this->age;
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
