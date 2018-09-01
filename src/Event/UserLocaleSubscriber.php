<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 9/1/18
 * Time: 4:12 PM
 */

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserLocaleSubscriber implements EventSubscriberInterface
{
	/**
	 * @var SessionInterface
	 */
	private $session;

	/**
	 * UserLocaleSubscriber constructor.
	 *
	 * @param SessionInterface $session
	 */
	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	public static function getSubscribedEvents()
	{
		return [
			SecurityEvents::INTERACTIVE_LOGIN => [
				[
					'onInteractiveLogin',
					15
				]
			]
		];
	}

	public function onInteractiveLogin(InteractiveLoginEvent $event)
	{
		/** @var User $user */
		$user = $event->getAuthenticationToken()->getUser();

		$this->session->set('_locale', $user->getPreferences()->getLocale());
	}
}