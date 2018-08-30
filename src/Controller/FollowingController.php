<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/29/18
 * Time: 7:19 PM
 */

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FollowingController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends Controller
{
	/**
	 * @Route("/follow/{id}", name="following_follow")
	 * @param User $userToFollow
	 * @return RedirectResponse
	 */
	public function follow(User $userToFollow)
	{
		/** @var User $currentUser */
		$currentUser = $this->getUser();
		$currentUser->getFollowing()->add($userToFollow);

		$this->getDoctrine()->getManager()->flush();

		return $this->redirectToRoute('post_user', [
			'username' => $userToFollow->getUsername()
		]);
	}

	/**
	 * @Route("/unfollow/{id}", name="following_unfollow")
	 * @param User $userToUnFollow
	 * @return RedirectResponse
	 */
	public function unfollow(User $userToUnFollow)
	{
		/** @var User $currentUser */
		$currentUser = $this->getUser();
		$currentUser->getFollowing()->removeElement($userToUnFollow);

		$this->getDoctrine()->getManager()->flush();

		return $this->redirectToRoute('post_user', [
			'username' => $userToUnFollow->getUsername()
		]);
	}


}