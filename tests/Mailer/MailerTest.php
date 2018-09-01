<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 9/1/18
 * Time: 4:42 PM
 */

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Twig_Environment;

class MailerTest extends TestCase
{
	/**
	 *
	 */
	public function testConfirmationEmail()
	{
		$user = new User();
		$user->setEmail('john@doe.com');

		$swiftMailer = $this
			->getMockBuilder(Swift_Mailer::class)
			->disableOriginalConstructor()
			->getMock();

		$twigMock = $this
			->getMockBuilder(Twig_Environment::class)
			->disableOriginalConstructor()
			->getMock();


		$mailer = new Mailer($swiftMailer, $twigMock, 'me@domain.com');
		$mailer->sendConfirmationEmail($user);
	}
}