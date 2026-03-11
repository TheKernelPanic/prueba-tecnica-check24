<?php

namespace App\Controller;

use App\Controller\Mappers\CalculateHttpMapper;
use App\UseCases\ApplyCampaignsUseCase;
use App\UseCases\PricingCalculatorUseCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class CalculateController
 * @package App\Controller
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
final class CalculateController extends AbstractController
{
    /**
     * @param PricingCalculatorUseCase $pricingCalculatorUseCase
     * @param ApplyCampaignsUseCase $applyCampaignsUseCase
     * @param CalculateHttpMapper $mapper
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly PricingCalculatorUseCase $pricingCalculatorUseCase,
        private readonly ApplyCampaignsUseCase    $applyCampaignsUseCase,
        private readonly CalculateHttpMapper      $mapper,
        private readonly LoggerInterface          $logger
    ) {
    }

    #[Route('/calculate', name: 'app_calculate')]
    public function index(Request $request): JsonResponse
    {
        $this->logger
            ->info(
                __METHOD__,
                [
                    'request' => $request->request->all(),
                ]
            );

        $pricingList = $this->pricingCalculatorUseCase->execute(
            $this->mapper
                ->fromRequest($request)
        );

        $pricingList = $this->applyCampaignsUseCase
            ->execute($pricingList);

        return $this->json(
            $this->mapper
                ->toResponse(
                    $pricingList
                )
        );
    }
}
