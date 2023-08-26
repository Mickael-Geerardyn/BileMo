<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Services\CacheService;
use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	private const USERS_CACHE_ID = "usersCacheId";
	public function __construct(
		private readonly CacheService $cacheService
	)
	{
	}

	/**
	 * @param Client $client
	 * @param string $user_email
	 *
	 * @return JsonResponse
	 * @throws InvalidArgumentException
	 */
	#[Route('/api/client/{client_id}/user/{user_email}',
		name: 'app_client_user',
		requirements: ['client_id' => '\d+', 'user_email' => '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}'],
		methods: ["GET"]
	)]
	#[ParamConverter('client', class: Client::class, options: ['id' => 'client_id'])]
	public function getClientUser(
		Client $client,
		string $user_email
	): JsonResponse
	{
		$jsonUsers = $this->cacheService->getUserCache($user_email ,$client);

		return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
	}

	/**
	 * @param Client $client
	 *
	 * @return JsonResponse
	 * @throws InvalidArgumentException
	 */
	#[Route('/api/client/{id}/users',
		name: 'app_client_users',
		requirements: ['id' => '\d+'] ,
		methods: ["GET"]
	)]
    public function getAllClientUsers(Client $client): JsonResponse
    {
		$jsonUsers = $this->cacheService->getUsersCache(self::USERS_CACHE_ID, $client);

		return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }
}
