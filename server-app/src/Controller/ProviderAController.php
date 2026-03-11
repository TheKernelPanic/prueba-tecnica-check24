<?php

namespace App\Controller;

use App\Enums\CarForm;
use App\Enums\CarUse;
use App\Traits\HttpDelayTrait;
use App\UseCases\ProviderAUseCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ProviderAController
 * @package App\Controller
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
final class ProviderAController extends AbstractController
{
    use HttpDelayTrait;

    /**
     * @param ProviderAUseCase $useCase
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ProviderAUseCase $useCase,
        private readonly LoggerInterface  $logger,
    ) {
    }

    #[Route('/provider-a/quote', name: 'app_provider_a', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $this->logger
            ->info(
                __METHOD__, [
                    'request' => $request->request->all(),
                ]
            );

        $requestData = json_decode($request->getContent(), true);

        $age     = $requestData['driver_age'] ?? null;
        $carForm = CarForm::tryFrom($requestData['car_form'] ?? null);
        $carUse  = CarUse::tryFrom($requestData['car_use'] ?? null);

        $this->addDelay(2);

        if (!is_int($age) || is_null($carUse) || is_null($carForm)) {
            throw new BadRequestException('Invalid request body');
        }
        $price = $this->useCase->execute(
            $age,
            $carForm,
            $carUse
        );

        return $this->json([
            'price'             => $price->base(),
            'withDiscount'      => $price->withDiscount(),
            'currency'          => $price->currency()->value,
        ]);
    }
}
