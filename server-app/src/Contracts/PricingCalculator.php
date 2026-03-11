<?php

namespace App\Contracts;


use App\Enums\CarForm;
use App\ValueObjects\CalculationParameters;
use App\ValueObjects\Price;

/**
 * Interface Provider
 * @package App\Contracts
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
interface PricingCalculator
{
    /**
     * @param float $base
     * @return PricingCalculator
     */
    public function setBase(float $base): PricingCalculator;

    /**
     * @param CalculationParameters $parameters
     * @return Price
     */
    public function calculate(CalculationParameters $parameters): Price;

    /**
     * @param int $min
     * @param int $max
     * @param float $amount
     * @return PricingCalculator
     */
    public function addAgeRule(int $min, int $max, float $amount): PricingCalculator;

    /**
     * @param CarForm $carForm
     * @param float $amount
     * @return PricingCalculator
     */
    public function addCarRule(CarForm $carForm, float $amount): PricingCalculator;

    /**
     * @param callable $handler
     * @return PricingCalculator
     */
    public function addDiscountPercentageEvaluator(callable $handler): PricingCalculator;
}
