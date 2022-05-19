<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 *
 * @method null|Log find($id, $lockMode = null, $lockVersion = null)
 * @method null|Log findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function count(array $criteria): int
    {
        return $this->getQueryBuilderByCriteria($criteria)
            ->select('count(l.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    protected function getQueryBuilderByCriteria(array $criteria): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('l')
            ;

        if (\array_key_exists('serviceNames', $criteria)) {
            foreach ($criteria['serviceNames'] as $serviceName) {
                $queryBuilder->andWhere('l.serviceName = :serviceNameParameter')
                    ->setParameter('serviceNameParameter', $serviceName)
                ;
            }
        }

        if (\array_key_exists('startDate', $criteria)) {
            $queryBuilder->andWhere('l.createdAt > :startDateParameter')
                ->setParameter('startDateParameter', $criteria['startDate'])
            ;
        }

        if (\array_key_exists('endDate', $criteria)) {
            $queryBuilder->andWhere('l.createdAt < :endDateParameter')
                ->setParameter('endDateParameter', $criteria['endDate'])
            ;
        }

        if (\array_key_exists('statusCode', $criteria)) {
            $queryBuilder->andWhere('l.statusCode = :statusCodeParameter')
                ->setParameter('statusCodeParameter', (int) $criteria['statusCode'])
            ;
        }

        return $queryBuilder;
    }
}
