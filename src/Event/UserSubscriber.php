<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 11:58 AM
 */

namespace App\Event;

use App\Mailer\Mailer;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig_Environment;

class UserSubscriber implements EventSubscriberInterface
{
	/**
	 * @varvMailer
	 */
	private $mailer;

	/**
	 * UserSubscriber constructor.
	 *
	 * @param Mailer     $mailer
	 */
	public function __construct(Mailer $mailer)
	{
		$this->mailer = $mailer;
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
		$this->mailer->sendConfirmationEmail($event->getRegisteredUser());
	}
}