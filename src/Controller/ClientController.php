<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Services\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
	public function __construct(
		private readonly SerializerInterface $serializer,
		private readonly EntityManagerInterface $manager
	)
	{
	}

	#[Route('/api/new/user',
		name: 'app_new_user',
		methods: ["POST"]
	)]
	public function newClientUser(
		Request $request
	): JsonResponse
	{
		$user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

		$this->manager->persist($user);
		$this->manager->flush();

		$context = SerializationContext::create()->setGroups(['getClientUser']);
		$jsonUser = $this->serializer->serialize($user, 'json', $context);

		return new JsonResponse($jsonUser, Response::HTTP_CREATED, json: true);
	}
}
