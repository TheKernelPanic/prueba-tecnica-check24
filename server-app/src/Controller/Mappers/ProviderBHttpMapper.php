<?php

namespace App\Controller\Mappers;

use App\ValueObjects\Price;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProviderBHttpMapper
 * @package App\Controller\Mappers
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
class ProviderBHttpMapper
{
    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private SerializerInterface $serializer
    )
    {
    }

    /**
     * @param Request $request
     * @return array
     */
    public function fromRequest(Request $request): array
    {
        return $this->serializer
            ->decode($request->getContent(), 'xml');
    }

    /**
     * @param Price $price
     * @return Response
     * @throws ExceptionInterface
     */
    public function toResponse(Price $price): Response
    {
        $payloadData = [
            'Precio'       => $price->base(),
            'ConDescuento' => $price->withDiscount(),
            'Moneda'       => $price->currency()->value
        ];

        $responseBody = $this->serializer
            ->serialize(
                $payloadData,
                'xml',
                [
                    'xml_root_node_name' => 'RespuestaCotizacion'
                ]
            );

        return new Response(
            $responseBody,
            200,
            ['Content-Type' => 'application/xml']
        );
    }
}
