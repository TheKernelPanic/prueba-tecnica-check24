<?php

namespace App\Providers;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProvidersFactory
 * @package App\Providers
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
readonly class ProvidersFactory
{
    /**
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ContainerInterface $container,
        private LoggerInterface    $logger
    ) {
    }

    /**
     * @return ProviderClient[]
     */
    public function getProviders(): array
    {
        $this->logger
            ->info(
                __METHOD__
            );

        return array_map(
            fn ($definition) => $this->container->get($definition),
            $this->getDefinitions()
        );
    }

    /**
     * @return \class-string[]
     */
    private function getDefinitions(): array
    {
        return [
            ProviderAClient::class,
            ProviderBClient::class,
        ];
    }
}
