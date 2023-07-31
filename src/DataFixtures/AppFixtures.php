<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Client;
use App\Entity\Mobile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	private UserPasswordHasherInterface $passwordHasher;
	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}

    public function load(ObjectManager $manager): void
    {
		$user = new Client();
		$user->setEmail("user@BileMo.com");
		$user->setRoles(["ROLE_USER"]);
		$user->setPassword($this->passwordHasher->hashPassword($user, "password"));
		$manager->persist($user);

		$admin = new Client();
		$admin->setEmail("admin@BileMo.com");
		$admin->setRoles(["ROLE_ADMIN"]);
		$admin->setPassword($this->passwordHasher->hashPassword($admin, "password"));
		$manager->persist($admin);


		for($i = 0; $i < 10; $i++)
		{
			$brand = new Brand();
			$brand->setName("Marque N°" . $i);
			$brand->setCreatedAt();
			$manager->persist($brand);

			$listBrand[] = $brand;
		}

		for($i = 0; $i < 20; $i++)
		{
			$mobile = new Mobile();

			$mobile->setName("mobile N°" . $i);
			$mobile->setDescription("Description du mobile N°" . $i);
			$mobile->setCreatedAt();
			$mobile->setQuantity(rand(0, 20));
			$mobile->setBrand($listBrand[array_rand($listBrand)]);

			$manager->persist($mobile);
		}

        $manager->flush();
    }
}
