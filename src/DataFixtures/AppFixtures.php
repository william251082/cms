<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 5:24 PM
 */

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		for ($i = 0; $i < 10; $i++)
		{
			$post = new Post();
			$post->setText('Some random text ' . rand(0,100));
			$post->setTime(new DateTime('2018-03-15'));
			$manager->persist($post);
		}

		$manager->flush();
	}
}