<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasherService
{
	public function __construct(
		private readonly UserPasswordHasherInterface $passwordHasher,
		private readonly EntityManagerInterface $entityManager
	)
	{
	}

	public function setHashPassword(User $user): void
	{
		$user->setPassword($this->getHashPassword($user, $user->getPassword()));

		$this->persistAndFlushData($user);
	}

	private function getHashPassword(User $user, string $plainPassword): string
	{
		return $this->passwordHasher->hashPassword($user, $plainPassword);
	}

	private function persistAndFlushData(User $user): void
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}
}