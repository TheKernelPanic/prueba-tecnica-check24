<?php

namespace App\Controller\Mappers;

use App\Enums\CarForm;
use App\Enums\CarUse;
use App\ValueObjects\PricingList;
use App\ValueObjects\PricingRequest;
use App\ValueObjects\ProviderPricing;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CalculateHttpMapper
 * @package App\Controller\Mappers
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class CalculateHttpMapper
{
    /**
     * @param Request $request
     * @return PricingRequest
     */
    public function fromRequest(Request $request): PricingRequest
    {
        $requestData = json_decode($request->getContent(), true);

        $age     = $requestData['driver_age']
            ?? throw new BadRequestException('Invalid driver age');
        $carForm = CarForm::tryFrom($requestData['car_form'])
            ?? throw new BadRequestException('Invalid car form');
        $carUse  = CarUse::tryFrom($requestData['car_use'])
            ?? throw new BadRequestException('Invalid car use');

        return new PricingRequest(
            $age,
            $carForm,
            $carUse,
        );
    }

    /**
     * @param PricingList $pricingList
     * @return array
     */
    public function toResponse(PricingList $pricingList): array
    {
        return array_map(
            function (ProviderPricing $pricingRequest) {
                return [
                    'provider'     => $pricingRequest->name(),
                    'price'        => $this->formatNumber(
                        $pricingRequest->price()->base()
                    ),
                    'withDiscount' => $this->formatNumber(
                        $pricingRequest->price()->withDiscount()
                    ),
                ];
            },
            $pricingList->value()
        );
    }

    /**
     * @param float $number
     * @return string
     */
    private function formatNumber(float $number): string
    {
        return number_format($number, 2, ',', '.');
    }
}
