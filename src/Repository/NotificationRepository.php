<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/30/18
 * Time: 10:34 PM
 */

namespace App\Repository;

use App\Entity\LikeNotification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LikeNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeNotification[]    findAll()
 * @method LikeNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, LikeNotification::class);
	}

	public function findUnseenByUser(User $user)
	{
		$qb = $this->createQueryBuilder('n');

		return $qb
			->select('count(n)')
			->where('n.user = :user')
			->setParameter('user', $user)
			->getQuery()
			->getSingleScalarResult();

	}
}