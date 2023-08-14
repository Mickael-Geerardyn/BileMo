<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\CacheService;
use App\Services\HttpExceptionEmptyData;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{

	public function __construct(
		private readonly SerializerInterface    $serializer,
		private readonly EntityManagerInterface $manager,
		private readonly UserRepository         $userRepository,
		private readonly HttpExceptionEmptyData $httpExceptionEmptyData,
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

	/**
	 * @param Client $client
	 * @param string $user_email
	 *
	 * @return JsonResponse
	 * @throws HttpException
	 */
	#[Route('/api/client/{client_id}/user/{user_email}',
		name: 'app_delete_client_user',
		requirements: ['client_id' => '\d+', 'user_email' => '.+@.+\\..+'],
		methods: ["DELETE"]
	)]
	#[ParamConverter('client', class: Client::class, options: ['id' => 'client_id'])]
	public function deleteClientUser(
		Client $client,
		string $user_email
	): JsonResponse
	{
		$user = $this->userRepository->findOneClientUser($user_email, $client);

		$this->httpExceptionEmptyData->throwHttpExceptionIfEmptyData($this->httpExceptionEmptyData::NOT_FOUND_STATUT_CODE, $user);

		$this->manager->remove($user);
		$this->manager->flush();

		return new JsonResponse(null, Response::HTTP_NO_CONTENT);
	}
}
