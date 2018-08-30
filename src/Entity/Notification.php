<?php
/**
 * Created by PhpStorm.
 * User: williamdelrosario
 * Date: 8/30/18
 * Time: 6:17 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Notification
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"like" = "LikeNotification"})
 */
abstract class Notification
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 */
	private $user;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $seen;

	public function __construct()
	{
		$this->seen = false;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getSeen()
	{
		return $this->seen;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user): void
	{
		$this->user = $user;
	}

	/**
	 * @param mixed $seen
	 */
	public function setSeen($seen): void
	{
		$this->seen = $seen;
	}


}