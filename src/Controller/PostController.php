<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 4:46 PM
 */

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

/**
 * @Route("/post")
 */
class PostController
{
	/**
	 * @var \Twig_Environment
	 */
	private $twig;
	/**
	 * @var PostRepository
	 */
	private $postRepository;

	/**
	 * @param \Twig_Environment $twig
	 * @param PostRepository    $postRepository
	 */
	public function __construct(Twig_Environment $twig,
	                            PostRepository $postRepository)
	{
		$this->twig           = $twig;
		$this->postRepository = $postRepository;
	}

	/**
	 * @Route("/", name="post_index")
	 */
	public function index()
	{
		$html = $this->twig->render('post/index.html.twig', [
			'posts' => $this->postRepository->findAll()
		]);

		return new Response($html);
	}
}