<?php

namespace App\Providers;

use App\Enums\Currency;
use App\ValueObjects\Price;
use App\ValueObjects\PricingRequest;
use App\ValueObjects\ProviderPricing;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

/**
 * Class ProviderAClient
 * @package App\Providers
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class ProviderAClient extends ProviderClient
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'provider-a';
    }

    /**
     * @param PricingRequest $request
     * @return ProviderPricing
     * @throws GuzzleException
     */
    public function request(PricingRequest $request): ProviderPricing
    {
        $client = new Client(
            [
                'base_uri' => $this->parameters['host'],
                'verify'   => false,
            ]
        );

        /**
         * @var Response $response
         */
        $response = $client->post(
            $this->parameters['uri'],
            [
                'json' => [
                    'driver_age' => $request->driverAge(),
                    'car_form'   => $request->carForm(),
                    'car_use'    => $request->carUse(),
                ]
            ]
        );

        $responseData = json_decode($response->getBody()->getContents(), true);

        return new ProviderPricing(
            $this->getName(),
            new Price(
                $responseData['price'],
                $responseData['withDiscount'],
                Currency::tryFrom($responseData['currency']),
            )
        );
    }
}
