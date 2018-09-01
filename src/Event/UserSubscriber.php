<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 11:58 AM
 */

namespace App\Event;

use App\Entity\UserPreferences;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
	/**
	 * @varvMailer
	 */
	private $mailer;
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var string
	 */
	private $defaultLocale;

	/**
	 * UserSubscriber constructor.
	 *
	 * @param Mailer                        $mailer
	 * @param EntityManagerInterface        $entityManager
	 * @param string                        $defaultLocale
	 */
	public function __construct(Mailer $mailer,
								EntityManagerInterface $entityManager,
								string $defaultLocale)
	{
		$this->mailer = $mailer;
		$this->entityManager = $entityManager;
		$this->defaultLocale = $defaultLocale;
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
		$preferences = new UserPreferences();
		$preferences->setLocale($this->defaultLocale);

		$user = $event->getRegisteredUser();
		$user->setPreferences($preferences);

		$this->entityManager->flush();

		$this->mailer->sendConfirmationEmail($event->getRegisteredUser());
	}
}