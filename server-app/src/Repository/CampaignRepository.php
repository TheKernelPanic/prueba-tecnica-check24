<?php

namespace App\Repository;

use App\Entity\Campaign;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campaign>
 */
class CampaignRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campaign::class);
    }

    /**
     * @param DateTimeInterface|null $date
     * @return Campaign[]
     */
    public function findAllWithAvailability(?DateTimeInterface $date = null): array
    {
        $date = $date ?? new \DateTime();

        return $this->createQueryBuilder('e')
            ->andWhere('e.fromAt <= :date')
            ->andWhere('e.toAt >= :date')
            ->andWhere('e.isActive = true')
            ->setParameter('date', $date)
            ->orderBy('e.fromAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
