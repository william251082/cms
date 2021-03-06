<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 4:46 PM
 */

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
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
	 * @var FormFactoryInterface
	 */
	private $formFactory;
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	private $router;
	/**
	 * @var FlashBagInterface
	 */
	private $flashBag;

	private $authorizationChecker;

	/**
	 * @param Twig_Environment                  $twig
	 * @param PostRepository                    $postRepository
	 * @param FormFactoryInterface              $formFactory
	 * @param EntityManagerInterface            $entityManager
	 * @param RouterInterface                   $router
	 * @param FlashBagInterface                 $flashBag
	 * @param AuthorizationCheckerInterface     $authorizationChecker
	 */
	public function __construct(Twig_Environment $twig,
	                            PostRepository $postRepository,
								FormFactoryInterface $formFactory,
								EntityManagerInterface $entityManager,
								RouterInterface $router,
							    FlashBagInterface $flashBag,
								AuthorizationCheckerInterface $authorizationChecker)
	{
		$this->twig           = $twig;
		$this->postRepository = $postRepository;
		$this->formFactory = $formFactory;
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->flashBag = $flashBag;
		$this->authorizationChecker = $authorizationChecker;
	}

	/**
	 * @Route("/", name="post_index")
	 * @param TokenStorageInterface $tokenStorage
	 *
	 * @param UserRepository        $userRepository
	 *
	 * @return Response
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function index(TokenStorageInterface $tokenStorage, UserRepository $userRepository)
	{
		$currentUser = $tokenStorage->getToken()->getUser();

		// Suggestions to follow users
		$usersToFollow = [];

		if ($currentUser instanceof User)
		{
			$posts = $this->postRepository->findAllByUsers($currentUser->getFollowing());

			$usersToFollow = count($posts) === 0 ? $userRepository->findAllWithMoreThan5PostsExceptUser($currentUser) : [];
		}
		else
			{
				$posts = $this->postRepository->findBy([], ['time' => 'DESC']);
			}

		$html = $this->twig->render(
			'post/index.html.twig', ['posts' => $posts, 'usersToFollow' => $usersToFollow ]
		);

		return new Response($html);
	}

	/**
	 * @Route("/edit/{id}", name="post_edit")
	 * @param Post    $post
	 * @param Request $request
	 *
	 * @return Response
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 * @Security("is_granted('edit', post)", message="Access denied")
	 */
	public function edit(Post $post, Request $request)
	{
//		$this->denyUnlessGranted('edit', $post); possible with base Controller

		if (!$this->authorizationChecker->isGranted('edit', $post))
		{
			throw new UnauthorizedHttpException();
		}

		$form = $this->formFactory->create(
			PostType::class, $post
		);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$this->entityManager->persist($post);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('post_index'));
		}

		return new Response($this->twig->render(
			'post/add.html.twig', ['form' => $form->createView()]
		));
	}

	/**
	 * @Route("/delete/{id}", name="post_delete")
	 * @param Post $post
	 * @Security("is_granted('delete', post)", message="Access denied")
	 * @return RedirectResponse
	 */
	public function delete(Post $post)
	{
		$this->entityManager->remove($post);
		$this->entityManager->flush();

		$this->flashBag->add('notice', 'Post was deleted');

		return new RedirectResponse($this->router->generate('post_index'));
	}

	/**
	 * @Route("/add", name="post_add")
	 * @Security("is_granted('ROLE_USER')")
	 * @param Request                   $request
	 * @param TokenStorageInterface     $tokenStorage
	 *
	 * @return Response
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function add(Request $request, TokenStorageInterface $tokenStorage)
	{
		//$user = $this->getUser(); possible with base Controller

		$user = $tokenStorage->getToken()->getUser();

		$post = new Post();
		$post->setUser($user);

		$form = $this->formFactory->create(PostType::class, $post);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$this->entityManager->persist($post);
			$this->entityManager->flush();

			return new RedirectResponse($this->router->generate('post_index'));
		}

		return new Response($this->twig->render(
			'post/add.html.twig', ['form' => $form->createView()]
		));
	}

	/**
	 * @Route("/user/{username}", name="post_user")
	 * @throws
	 * @param User $userWithPosts
	 * @return Response
	 */
	public function userPosts(User $userWithPosts)
	{
		$html = $this->twig->render(
			'post/user-posts.html.twig',
			[
				'posts' => $this->postRepository->findBy(
					['user' => $userWithPosts],
					['time' => 'DESC']
				),
				'user' =>$userWithPosts
				// lazy loading, using doctrine proxy classes
//				'posts' => $userWithPosts->getPosts()
			]
		);

		return new Response($html);
	}

	/**
	 * @Route("/{id}", name="post_post")
	 * @param Post $post
	 *
	 * @return Response
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function post(Post $post)
	{
//		$post = $this->postRepository->find($id);

		return new Response(
			$this->twig->render(
				'post/post.html.twig', [
					'post' => $post
				]
			)
		);
	}
}