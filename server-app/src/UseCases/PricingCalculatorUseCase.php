<?php

namespace App\UseCases;

use App\Providers\ProvidersFactory;
use App\ValueObjects\PricingList;
use App\ValueObjects\PricingRequest;
use App\ValueObjects\ProviderPricing;
use Psr\Log\LoggerInterface;

/**
 * Class PricingCalculatorUseCase
 * @package App\UseCases
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class PricingCalculatorUseCase
{
    /**
     * @param ProvidersFactory $providersFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ProvidersFactory $providersFactory,
        private LoggerInterface  $logger
    ) {}

    /**
     * @param PricingRequest $request
     * @return PricingList
     */
    public function execute(PricingRequest $request): PricingList
    {
        $this->logger->info(
            __METHOD__,
            [
                'request' => $request,
            ]
        );

        $responses = [];
        foreach ($this->providersFactory->getProviders() as $provider) {
            $responses[] = $provider->request($request);
        }

        $this->sortByCheapestPrice($responses);

        return new PricingList($responses);
    }

    /**
     * @param ProviderPricing[] $providerPricingList
     * @return void
     */
    private function sortByCheapestPrice(array &$providerPricingList): void
    {
        usort($providerPricingList, function (ProviderPricing $back, ProviderPricing $next) {
            return $back->price()->withDiscount() - $next->price()->withDiscount();
        });
    }
}
