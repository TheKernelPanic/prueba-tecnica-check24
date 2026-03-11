<?php

namespace App\Providers;

use App\Enums\Currency;
use App\ValueObjects\Price;
use App\ValueObjects\PricingRequest;
use App\ValueObjects\ProviderClientRequest;
use App\ValueObjects\ProviderPricing;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProviderBClient
 * @package App\Providers
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class ProviderBClient extends ProviderClient
{
    /**
     * @param ParameterBagInterface $parameterBag
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ParameterBagInterface       $parameterBag,
        private SerializerInterface $serializer
    )
    {
        parent::__construct($parameterBag);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'provider-b';
    }

    /**
     * @param PricingRequest $request
     * @return ProviderPricing
     */
    public function request(PricingRequest $request): ProviderPricing
    {
        $client = new Client(
            [
                'base_uri' => $this->parameters['host'],
                'verify'   => false,
            ]
        );

        $body = $this->serializer->serialize(
            [
                'EdadConductor'      => $request->driverAge(),
                'TipoCoche'          => $request->carForm()->value,
                'UsoCoche'           => $request->carUse()->value,
                'ConductorOcasional' => 'No' // TODO: Where ¿?
            ],
            'xml',
            ['xml_root_node_name' => 'SolicitudCotizacion']
        );

        /**
         * @var Response $response
         */
        $response = $client->post(
            $this->parameters['uri'],
            [
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/xml',
                    'Accept' => 'application/xml',
                ],
            ]
        );

        $responseData = $this->serializer
            ->decode(
                $response->getBody()->getContents(),
                'xml'
            );

        return new ProviderPricing(
            $this->getName(),
            new Price(
                $responseData['Precio'],
                $responseData['ConDescuento'],
                Currency::tryFrom($responseData['Moneda']),
            )
        );
    }
}
