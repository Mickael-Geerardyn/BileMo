<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

	public function findAllByPagination(Client $client ,int $page = 1, int $limit = 10): array
	{
		return $this->createQueryBuilder('users')
			->innerJoin("users.client", "client")
			->where("client = :client")
			->setParameter(":client", $client)
			->setFirstResult($limit * ($page - 1))
			->setMaxResults($limit)
			->getQuery()
			->getResult()
			;
	}

   public function findOneClientUser(string $userEmail, Client $client): ?User
    {
        return $this->createQueryBuilder('user')
			->innerJoin('user.client', 'client')
			->where('user.email = :email')
			->andWhere('user.client = :client')
			->setParameter(':email', $userEmail)
			->setParameter(':client', $client)
			->getQuery()
			->getOneOrNullResult()
        ;
    }
}
