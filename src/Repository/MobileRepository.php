<?php

namespace App\Repository;

use App\Entity\Mobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mobile>
 *
 * @method Mobile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mobile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mobile[]    findAll()
 * @method Mobile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mobile::class);
    }


	public function findAllByPagination(int $page = 1, int $limit = 10)
	{
		return $this->createQueryBuilder('mobiles')
					->setFirstResult($limit * ($page - 1))
					->setMaxResults($limit)
					->getQuery()
					->getResult()
			;
	}
}
