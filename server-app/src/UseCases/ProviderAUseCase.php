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
 * Class ProviderAUseCase
 * @package App\UseCases
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class ProviderAUseCase
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
     * @throws ProviderAvailabilityException
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

        if (!$this->isAvailable()) {
            throw new ProviderAvailabilityException();
        }

        return $this->pricingCalculator
            ->setBase(217)
            ->addAgeRule(18, 24, 70)
            ->addAgeRule(56, 56, 90)
            ->addCarRule(CarForm::SUV, 100)
            ->addCarRule(CarForm::Compact, 10)
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

    /**
     * @return bool
     */
    private function isAvailable(): bool
    {
        $rand = rand(0, 100);

        if ($rand > 0 && $rand <= 10) {
            return false;
        }
        return true;
    }
}
