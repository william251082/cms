<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/28/18
 * Time: 6:06 PM
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
	/**
	 * @Route("/register", name="user_register")
	 *
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 */
	public function register(UserPasswordEncoderInterface $passwordEncoder,
							 Request $request)
	{

//		$this->denyAccessUnlessGranted('edit', $post) possible for base Controller
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$password = $passwordEncoder->encodePassword(
				$user,
				$user->getPlainPassword()
			);

			$user->setPassword($password);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('post_index');
		}

		return $this->render('register/register.html.twig', [
			'form' => $form->createView()
		]);
	}
}