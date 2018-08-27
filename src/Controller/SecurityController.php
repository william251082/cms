<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/27/18
 * Time: 7:41 AM
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig_Environment;

class SecurityController
{
	/**
	 * @var Twig_Environment
	 */
	private $twig;

	/**
	 * SecurityController constructor.
	 *
	 * @param Twig_Environment $twig
	 */
	public function __construct(Twig_Environment $twig)
	{
		$this->twig = $twig;
	}

	/**
	 * @Route("/login", name="security_login")
	 * @param AuthenticationUtils $authenticationUtils
	 * @throws
	 * @return Response
	 *
	 */
	public function login(AuthenticationUtils $authenticationUtils)
	{
		return new Response($this->twig->render(
			'security/login.html.twig',
			[
				'last_username' => $authenticationUtils->getLastUsername(),
				'error' => $authenticationUtils->getLastAuthenticationError(),
 			]
		));
	}

	/**
	 * @Route("/logout", name="security_logout")
	 */
	public function logout()
	{

	}
}