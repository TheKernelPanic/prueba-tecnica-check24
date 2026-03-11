<?php

namespace App\UseCases;

use App\Repository\CampaignRepository;
use App\ValueObjects\Price;
use App\ValueObjects\PricingList;
use App\ValueObjects\ProviderPricing;
use Psr\Log\LoggerInterface;

/**
 * Class ApplyCampaignsUseCase
 * @package App\UseCases
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class ApplyCampaignsUseCase
{
    /**
     * @param CampaignRepository $campaignRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private CampaignRepository $campaignRepository,
        private LoggerInterface    $logger,
    ) {
    }

    /**
     * @param PricingList $pricingList
     * @return PricingList
     */
    public function execute(PricingList $pricingList): PricingList
    {
        $this->logger
            ->info(
                __METHOD__
            );

        $campaigns = $this->campaignRepository
            ->findAllWithAvailability();

        if (empty($campaigns)) {
            return $pricingList;
        }

        $updatedPricingList = new PricingList();
        foreach ($pricingList->value() as $providerPricing) {

            $provider     = $providerPricing->name();
            $price        = $providerPricing->price()->base();
            $withDiscount = $providerPricing->price()->withDiscount();
            $currency     = $providerPricing->price()->currency();

            foreach ($campaigns as $campaign) {

                $this->logger
                    ->info(
                        __METHOD__,
                        [
                            'message' => sprintf('Campaign applied with %d discount', $campaign->getDiscount())
                        ]
                    );

                $withDiscount -= $price * $campaign->getDiscount() / 100;
            }

            $updatedPricingList->add(
                new ProviderPricing(
                    $provider,
                    new Price(
                        $price,
                        $withDiscount,
                        $currency
                    ),
                )
            );
        }
        return $updatedPricingList;
    }
}
