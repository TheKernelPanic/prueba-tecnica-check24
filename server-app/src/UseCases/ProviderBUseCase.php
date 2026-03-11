<?php

namespace App\UseCases;

use App\Contracts\PricingCalculator;
use App\Enums\CarForm;
use App\Enums\CarUse;
use App\Exceptions\ProviderAvailabilityException;
use App\ValueObjects\CalculationParameters;
use App\ValueObjects\Price;
use Psr\Log\LoggerInterface;

/**
 * Class ProviderBUseCase
 * @package App\UseCases
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class ProviderBUseCase
{
    /**
     * @param PricingCalculator $pricingCalculator
     * @param LoggerInterface $logger
     */
    public function __construct(
        private PricingCalculator $pricingCalculator,
        private LoggerInterface   $logger,
    ) {
    }

    /**
     * @param int $age
     * @param CarForm $carForm
     * @param CarUse $carUse
     * @return Price
     */
    public function execute(int $age, CarForm $carForm, CarUse $carUse): Price
    {
        $this->logger->info(
            __METHOD__, [
                'age'     => $age,
                'carForm' => $carForm->value,
                'carUse'  => $carUse->value,
            ]
        );

        return $this->pricingCalculator
            ->setBase(250)
            ->addAgeRule(18, 29, 50)
            ->addAgeRule(30, 59, 20)
            ->addAgeRule(60, 60, 100)
            ->addCarRule(CarForm::Tourism, 30)
            ->addCarRule(CarForm::SUV, 200)
            ->addDiscountPercentageEvaluator(
                function (CalculationParameters $parameters) {
                    if ($parameters->carUse() == CarUse::Commercial) {
                        return 15;
                    }
                    return 0;
                }
            )
            ->calculate(
                new CalculationParameters(
                    $age,
                    $carForm,
                    $carUse,
                )
            );
    }
}
