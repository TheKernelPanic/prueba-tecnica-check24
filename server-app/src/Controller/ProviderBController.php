<?php

namespace App\Controller;

use App\Controller\Mappers\ProviderBHttpMapper;
use App\Enums\CarForm;
use App\Enums\CarUse;
use App\Traits\HttpDelayTrait;
use App\UseCases\ProviderBUseCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ProviderBController
 * @package App\Controller
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
final class ProviderBController extends AbstractController
{
    use HttpDelayTrait;

    /**
     * @param ProviderBUseCase $useCase
     * @param ProviderBHttpMapper $mapper
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ProviderBUseCase $useCase,
        private readonly ProviderBHttpMapper $mapper,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/provider-b/quote', name: 'app_provider_b', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $this->logger
            ->info(
                __METHOD__, [
                    'request' => $request->request->all(),
                ]
            );

        $requestData = $this->mapper
            ->fromRequest($request);

        $age     = $requestData['EdadConductor'] ?? null;
        $carForm = CarForm::tryFrom($requestData['TipoCoche'] ?? null);
        $carUse  = CarUse::tryFrom($requestData['UsoCoche'] ?? null);

        $this->addDelay(5);

        if (!is_numeric($age) || is_null($carUse) || is_null($carForm)) {
            throw new BadRequestException('Invalid request body');
        }

        $price = $this->useCase
            ->execute(
            $age,
            $carForm,
            $carUse
        );

        return $this->mapper
            ->toResponse($price);
    }
}
