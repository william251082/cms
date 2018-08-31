<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 11:58 AM
 */

namespace App\Event;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig_Environment;

class UserSubscriber implements EventSubscriberInterface
{
	/**
	 * @var Swift_Mailer
	 */
	private $mailer;
	/**
	 * @var Twig_Environment
	 */
	private $twig;

	/**
	 * UserSubscriber constructor.
	 *
	 * @param Swift_Mailer     $mailer
	 * @param Twig_Environment $twig
	 */
	public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
	{
		$this->mailer = $mailer;
		$this->twig = $twig;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			UserRegisterEvent::NAME => 'onUserRegister'
		];
	}

	/**
	 * @param UserRegisterEvent $event
	 * @throws 
	 */
	public function onUserRegister(UserRegisterEvent $event)
	{
		$body = $this->twig->render('email/registration.html.twig', [
			'user' => $event->getRegisteredUser()
		]);

		$message = (new Swift_Message())
			->setSubject('Welcome to Post App!')
			->setFrom('post@post.com')
			->setTo($event->getRegisteredUser()->getEmail())
			->setBody($body, 'text/html');

		$this->mailer->send($message);
	}
}