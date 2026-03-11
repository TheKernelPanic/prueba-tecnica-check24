<?php

namespace App\Providers;

use App\Client\HttpClient;
use App\ValueObjects\PricingRequest;
use App\ValueObjects\ProviderPricing;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ProviderClient
 * @package App\Providers
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
abstract class ProviderClient
{
    /**
     * @var array
     */
    protected array $parameters;

    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
    ) {
        $this->parameters = $parameterBag->get($this->getName());
    }

    /**
     * @return string
     */
    protected abstract function getName(): string;

    /**
     * @param PricingRequest $request
     * @return ProviderPricing
     */
    public abstract function request(PricingRequest $request): ProviderPricing;
}
