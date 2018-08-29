<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="This email is already used")
 * @UniqueEntity(fields="username", message="This username is already used")
 */
class User implements UserInterface, Serializable
{
	const ROLE_USER = 'ROLE_USER';
	const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\Column(type="string", length=50, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Length(min=5, max=50)
	 */
    private $username;

	/**
	 * @ORM\Column(type="string")
	 */
    private $password;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Length(min=8, max=4096)
	 */
	private $plainPassword;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
    private $email;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank()
	 * @Assert\Length(min=4, max=50)
	 */
    private $fullName;

	/**
	 * @var array
	 * @ORM\Column(type="simple_array")
	 */
    private $roles;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user"))
	 */
    private $posts;

    public function __construct()
    {
    	$this->posts = new ArrayCollection();
    }

	public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return mixed
	 */
	public function getFullName()
	{
		return $this->fullName;
	}

	/**
	 * Returns the roles granted to the user.
	 *
	 * <code>
	 * public function getRoles()
	 * {
	 *     return array('ROLE_USER');
	 * }
	 * </code>
	 *
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return array (Role|string)[] The user roles
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * @param array $roles
	 */
	public function setRoles(array $roles): void
	{
		$this->roles = $roles;
	}

	/**
	 * Returns the password used to authenticate the user.
	 *
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string The password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt()
	{
		return null;
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{

	}

	/**
	 * String representation of object
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize()
	{
		return serialize([
			$this->id,
			$this->username,
			$this->password
		]);
	}

	/**
	 * Constructs the object
	 * @link  https://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized <p>
	 *                           The string representation of the object.
	 *                           </p>
	 *
	 * @return void
	 * @since 5.1.0
	 */
	public function unserialize($serialized)
	{
		list(
			$this->id,
			$this->username,
			$this->password
			) = unserialize($serialized);
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email): void
	{
		$this->email = $email;
	}

	/**
	 * @param mixed $fullName
	 */
	public function setFullName($fullName): void
	{
		$this->fullName = $fullName;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username): void
	{
		$this->username = $username;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password): void
	{
		$this->password = $password;
	}

	/**
	 * @return mixed
	 */
	public function getPlainPassword()
	{
		return $this->plainPassword;
	}

	/**
	 * @param mixed $plainPassword
	 */
	public function setPlainPassword($plainPassword): void
	{
		$this->plainPassword = $plainPassword;
	}

	/**
	 * @return mixed
	 */
	public function getPosts()
	{
		return $this->posts;
	}


}
