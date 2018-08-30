<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 4:04 PM
 */

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, Post::class);
	}

	/**
	 * @param Collection $users
	 *
	 * @return mixed
	 */
	public function findAllByUsers(Collection $users)
	{
		$qb = $this->createQueryBuilder('p');

		return $qb
				->select('p')
				->where('p.user IN (:following)')
				->setParameter('following', $users)
				->orderBy('p.time', 'DESC')
				->getQuery()
				->getResult();
	}
}