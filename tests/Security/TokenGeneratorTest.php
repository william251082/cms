<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 9/1/18
 * Time: 4:29 PM
 */

namespace App\Tests\Security;

use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
	public function testTokenGeneration()
	{
		$tokenGen = new TokenGenerator();
		$token = $tokenGen->getRandomSecureToken(30);
//		$token [15] = '*';
//		echo $token;

		$this->assertEquals(30, strlen($token));
//		$this->assertEquals(1, preg_match("/[A-Za-z0-9]/", $token)); ctype is better
		$this->assertTrue( ctype_alnum($token), 'Token contains incorrect characters');
	}

}