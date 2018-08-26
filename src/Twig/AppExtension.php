<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 1:50 PM
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
	/**
	 * @var string
	 */
	private $locale;

	public function __construct(string $locale)
	{
		$this->locale = $locale;
	}

	public function getFilters()
	{
		return [
			new TwigFilter('price', [$this, 'priceFilter'])
		];
	}

	public function getGlobals()
	{
		return [
			'locale' => $this->locale
		];
	}

	public function priceFilter($number)
	{
		return '$'.number_format($number, 2, '.', ',');
	}
}