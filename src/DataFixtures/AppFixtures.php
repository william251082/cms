<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/26/18
 * Time: 5:24 PM
 */

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * AppFixtures constructor.
	 *
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 */
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function load(ObjectManager $manager)
	{
		$this->loadUsers($manager);
		$this->loadPosts($manager);
	}

	private function loadPosts(ObjectManager $manager)
	{
		for ($i = 0; $i < 10; $i++)
		{
			$post = new Post();
			$post->setText('Some random text ' . rand(0,100));
			$post->setTime(new DateTime('2018-03-15'));
			$post->setUser($this->getReference('john_doe'));
			$manager->persist($post);
		}

		$manager->flush();
	}

	private function loadUsers(ObjectManager $manager)
	{
		$user = new User();
		$user->setUsername('john_doe');
		$user->setFullName('John Doe');
		$user->setEmail('john_doe@doe.com');
		$user->setPassword(
			$this->passwordEncoder->encodePassword($user, 'john123')
		);

		$this->addReference('john_doe', $user);

		$manager->persist($user);
		$manager->flush();
	}
}