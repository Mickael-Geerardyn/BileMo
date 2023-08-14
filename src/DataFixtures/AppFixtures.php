<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Client;
use App\Entity\Mobile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	private const CLIENT_ARRAY = ["Client 1"];
	private const BRAND_ARRAY = ["SONY" => "Xperia 1 V", "SAMSUNG" => "GalaxyS21", "APPLE" => "iPhone 18 Pro", "LG" => "Velvet", "GOOGLE" => "Pixel 7 Pro", "XIAOMI" => "12 PRO", "ONEPLUS" => "11"];

	private array $brandsList;

	public function __construct(
		private UserPasswordHasherInterface $passwordHasher
	)
	{
		$this->passwordHasher = $passwordHasher;
	}
	public function load(ObjectManager $manager): void
	{
		$this->loadClients($manager);
		$this->loadBrand($manager);
		$this->loadMobile($manager);

		$manager->flush();
	}

	public function loadBrand(ObjectManager $manager): void
	{
		foreach(self::BRAND_ARRAY as $brandType => $brandModel) {

			$brand = new Brand();
			$brand->setName($brandType);
			$brand->setCreatedAt();
			$brand->setCreatedAt();

			$manager->persist($brand);

			$this->brandsList[$brandType] = $brand;
		}

	}

	public function loadMobile(ObjectManager $manager): void
	{
		foreach(self::BRAND_ARRAY as $brandType => $mobileType)
		{
			$mobile = new Mobile();

			$mobile->setName($mobileType);
			$mobile->setDescription("Description du mobile ${mobileType} de la marque ${brandType}");
			$mobile->setCreatedAt();
			$mobile->setQuantity(rand(0, 20));
			$mobile->setBrand($this->brandsList[array_search($mobileType,self::BRAND_ARRAY)]);
			$mobile->setCreatedAt();

			$manager->persist($mobile);
		}
	}

	public function loadClients(ObjectManager $manager): void
	{
		$client = new Client();
		$client->setName(self::CLIENT_ARRAY[array_rand(self::CLIENT_ARRAY)]);
		$client->setCreatedAt();

		$manager->persist($client);

		$this->loadUsers($client, $manager);
	}

	public function loadUsers(Client $client, ObjectManager $manager): void
	{
		$user = new User();
		$user->setEmail("user@BileMo.com");
		$user->setRoles(["ROLE_USER"]);
		$user->setPassword($this->passwordHasher->hashPassword($user, "password"));
		$user->setClient($client);
		$user->setCreatedAt();
		$manager->persist($user);

		$admin = new User();
		$admin->setEmail("admin@BileMo.com");
		$admin->setRoles(["ROLE_ADMIN"]);
		$admin->setPassword($this->passwordHasher->hashPassword($admin, "password"));
		$admin->setClient($client);
		$admin->setCreatedAt();
		$manager->persist($admin);
	}
}
