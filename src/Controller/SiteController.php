<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 1:42 PM
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/site")
 */
class SiteController
{
	/**
	 * @var \Twig_Environment
	 */
	private $twig;
	/**
	 * @var SessionInterface
	 */
	private $session;
	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * @param \Twig_Environment $twig
	 * @param SessionInterface $session
	 * @param RouterInterface $router
	 */
	public function __construct(
		\Twig_Environment $twig,
		SessionInterface $session,
		RouterInterface $router
	)
	{
		$this->twig = $twig;
		$this->session = $session;
		$this->router = $router;
	}

	/**
	 * @Route("/", name="site_index")
	 */
	public function index()
	{
		$html = $this->twig->render(
			'site/index.html.twig',
			[
				'posts' => $this->session->get('posts')
			]
		);

		return new Response($html);
	}

	/**
	 * @Route("/add", name="site_add")
	 */
	public function add()
	{
		$posts = $this->session->get('posts');
		$posts[uniqid()] = [
			'title' => 'A random title '.rand(1, 500),
			'text' => 'Some random text nr '.rand(1, 500),
			'date' => new \DateTime(),
		];
		$this->session->set('posts', $posts);

		return new RedirectResponse($this->router->generate('site_index'));
	}

	/**
	 * @Route("/show/{id}", name="site_show")
	 */
	public function show($id)
	{
		$posts = $this->session->get('posts');

		if (!$posts || !isset($posts[$id])) {
			throw new NotFoundHttpException('Post not found');
		}

		$html = $this->twig->render(
			'site/post.html.twig',
			[
				'id' => $id,
				'post' => $posts[$id],
			]
		);

		return new Response($html);
	}
}