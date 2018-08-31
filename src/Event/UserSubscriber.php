<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 11:58 AM
 */

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			UserRegisterEvent::NAME => 'onUserRegister'
		];
	}

	public function onUserRegister(UserRegisterEvent $event)
	{
		
	}
}