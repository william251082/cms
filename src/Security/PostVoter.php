<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/29/18
 * Time: 8:36 AM
 */

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
	const EDIT = 'edit';
	const DELETE = 'delete';

	protected function supports($attribute, $subject)
	{
		if (!in_array($attribute, [self::EDIT, self::DELETE]))
		{
			return false;
		}

		if (!$subject instanceof Post)
		{
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		$authenticatedUser = $token->getUser();

		if (!$authenticatedUser instanceof User)
		{
			return false;
		}

		/** @var Post $post */
		$post = $subject;

		return $post->getUser()->getId() === $authenticatedUser->getId();
	}
}