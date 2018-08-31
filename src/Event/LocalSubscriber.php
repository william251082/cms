<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 10:16 PM
 */

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocalSubscriber implements EventSubscriberInterface
{
	/**
	 * @var string
	 */
	private $defaultLocale;

	/**
	 * LocalSubscriber constructor.
	 *
	 * @param string $defaultLocale
	 */
	public function __construct($defaultLocale = 'en')
	{
		$this->defaultLocale = $defaultLocale;
	}

	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::REQUEST => [
				[
					'onKernelRequest',
					20
				]
			]
		];
	}

	public function onKernelRequest(GetResponseEvent $event)
	{
		$request = $event->getRequest();

		if (!$request->hasPreviousSession())
		{
			return;
		}

		if ($locale = $request->attributes->get('_locale'))
		{
			$request->getSession()->set('_locale', $locale);
		}
		else
		{
			$request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
		}
	}
}