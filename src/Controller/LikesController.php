<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/30/18
 * Time: 5:24 PM
 */

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LikesController
 * @package App\Controller
 * @Route("/likes")
 */
class LikesController extends Controller
{
	/**
	 * @Route("/like/{id}", name="likes_like")
	 * @param Post $post
	 * @return JsonResponse
	 */
	public function like(Post $post)
	{
		/** @var $currentUser */
		$currentUser = $this->getUser();

		if (!$currentUser instanceof User)
		{
			return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
		}

		$post->like($currentUser);
		$this->getDoctrine()->getManager()->flush();

		return new JsonResponse([
			'count' => $post->getLikedBy()->count()
		]);
	}

	/**
	 * @param Post $post
	 * @Route("unlike/{id}", name="likes_unlike")
	 * @return JsonResponse
	 */
	public function unlike(Post $post)
	{
		/** @var $currentUser */
		$currentUser = $this->getUser();

		if (!$currentUser instanceof User)
		{
			return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
		}

		$post->getLikedBy()->removeElement($currentUser);
		$this->getDoctrine()->getManager()->flush();

		return new JsonResponse([
			'count' => $post->getLikedBy()->count()
		]);
	}
}