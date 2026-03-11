<?php

namespace App\ValueObjects;

/**
 * Class PricingList
 * @package App\ValueObjects
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class PricingList
{
    /**
     * @param ProviderPricing[] $data
     */
    public function __construct(
        private array $data = []
    ) {
    }

    /**
     * @return ProviderPricing[]
     */
    public function value(): array
    {
        return $this->data;
    }

    public function add(ProviderPricing $providerPricing): void
    {
        $this->data[] = $providerPricing;
    }
}
