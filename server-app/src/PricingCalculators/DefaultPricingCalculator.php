<?php

namespace App\PricingCalculators;

use App\Contracts\PricingCalculator;
use App\Enums\CarForm;
use App\Enums\Currency;
use App\ValueObjects\CalculationParameters;
use App\ValueObjects\Price;

/**
 * Class PricingCalculator
 * @package App\PricingCalculators
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class DefaultPricingCalculator implements PricingCalculator
{
    /**
     * @var array
     */
    private array $carRules = [];

    /**
     * @var array
     */
    private array $ageRules = [];

    /**
     * @var callable[]
     */
    private array $discountPercentageEvaluators = [];

    /**
     * @param float $base
     * @param Currency $currency
     */
    public function __construct(
        private float             $base = 0,
        private readonly Currency $currency = Currency::EUR,
    ) {
    }

    /**
     * @param int $min
     * @param int $max
     * @param float $amount
     * @return DefaultPricingCalculator
     */
    public function addAgeRule(int $min, int $max, float $amount): self
    {
        $this->ageRules[] = [
            'min'    => $min,
            'max'    => $max,
            'amount' => $amount,
        ];

        return $this;
    }

    /**
     * @param CarForm $carForm
     * @param float $amount
     * @return DefaultPricingCalculator
     */
    public function addCarRule(CarForm $carForm, float $amount): self
    {
        $this->carRules[] = [
            'carForm' => $carForm,
            'amount'  => $amount
        ];

        return $this;
    }

    /**
     * @param callable $handler
     * @return DefaultPricingCalculator
     */
    public function addDiscountPercentageEvaluator(callable $handler): self
    {
        $this->discountPercentageEvaluators[] = $handler;

        return $this;
    }

    /**
     * @param CalculationParameters $parameters
     * @return Price
     */
    public function calculate(CalculationParameters $parameters): Price
    {
        $withDiscount = $this->base;

        foreach ($this->ageRules as $rule) {
            if ($parameters->age() >= $rule['min'] && $parameters->age() <= $rule['max']) {
                $withDiscount -= $rule['amount'];
            }
        }
        foreach ($this->carRules as $rule) {
            if ($parameters->carForm() == $rule['carForm']) {
                $withDiscount -= $rule['amount'];
            }
        }
        foreach ($this->discountPercentageEvaluators as $handler) {
            $percentage = max(0, min(100, $handler($parameters)));
            if ($percentage > 0) {
                $withDiscount -= $this->base * $percentage / 100;
            }
        }

        return new Price(
            $this->base,
            $withDiscount,
            $this->currency
        );
    }

    /**
     * @param float $base
     * @return PricingCalculator
     */
    public function setBase(float $base): PricingCalculator
    {
        $this->base = $base;

        return $this;
    }
}
