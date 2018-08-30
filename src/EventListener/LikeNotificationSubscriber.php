<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/30/18
 * Time: 11:13 PM
 */

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\Post;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{
	public function getSubscribedEvents()
	{
		return [
			Events::onFlush
		];
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		/** @var PersistentCollection $collectionUpdate */
		foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate)
		{
			if (!$collectionUpdate->getOwner() instanceof Post){
				continue;
			}

//			dump($collectionUpdate->getMapping());die;
			if ('likedBy' !== $collectionUpdate->getMapping()['fieldName'])
			{
				continue;
			}

			$insertDiff = $collectionUpdate->getInsertDiff();

			if (!count($insertDiff))
			{
				return;
			}
			/** @var Post $post */
			$post = $collectionUpdate->getOwner();

			$notification = new LikeNotification();
			$notification->setUser($post->getUser());
			$notification->setPost($post);
			$notification->setLikedBy(reset($insertDiff));

			$em->persist($notification);

			$uow->computeChangeSet(
				$em->getClassMetadata(LikeNotification::class),
				$notification
			);
		}
	}
}