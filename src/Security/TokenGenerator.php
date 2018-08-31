<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/31/18
 * Time: 1:37 PM
 */

namespace App\Security;

class TokenGenerator
{
	private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	/**
	 * @param int $length
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getRandomSecureToken(int $length): string
	{
		$maxNumber = strlen(self::ALPHABET);
		$token = '';

		for ($i = 0; $i < $length; $i++) {
			$token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
		}

		return $token;
	}
}