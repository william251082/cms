<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 1:13 PM
 */

namespace App\Mailer;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

class Mailer
{
	/**
	 * @var Swift_Mailer
	 */
	private $mailer;
	private $twig;
	/**
	 * @var string
	 */
	private $mailFrom;

	/**
	 * Mailer constructor.
	 *
	 * @param Swift_Mailer     $mailer
	 * @param Twig_Environment $twig
	 * @param string           $mailFrom
	 */
	public function __construct(Swift_Mailer $mailer,
								Twig_Environment $twig,
								string $mailFrom)
	{
		$this->mailer = $mailer;
		$this->twig = $twig;
		$this->mailFrom = $mailFrom;
	}

	public function sendConfirmationEmail(User $user)
	{
		$body = $this->twig->render('email/registration.html.twig', [
			'user' => $user
		]);

		$message = (new Swift_Message())
			->setSubject('Welcome to Post App!')
			->setFrom($this->mailFrom)
			->setTo($user->getEmail())
			->setBody($body, 'text/html');

		$this->mailer->send($message);
	}
}