<?php

namespace DctT\TracabilityBundle\Repository;

use App\Entity\Tracability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tracability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tracability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tracability[]    findAll()
 * @method Tracability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TracabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tracability::class);
    }
}
