<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\UserRepository;
use App\Services\DeserializerService;
use App\Services\HttpExceptionEmptyDataService;
use App\Services\UserPasswordHasherService;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
		private readonly SerializerInterface           $serializer,
		private readonly EntityManagerInterface        $manager,
		private readonly UserRepository                $userRepository,
		private readonly HttpExceptionEmptyDataService $httpExceptionEmptyData,
		private readonly DeserializerService $deserializerService,
		private readonly UserPasswordHasherService $passwordHasherService
	)
	{
	}

	#[Route('/api/client/user',
		name: 'app_client_user_new',
		methods: ["POST"]
	)]
	#[IsGranted('ROLE_ADMIN', message: "Vous ne pouvez pas réaliser cette action")]
	public function newClientUser(
		Request $request
	): JsonResponse
	{
		$user = $this->deserializerService->requestDeserializer($request);

		$this->passwordHasherService->setHashPassword($user);

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
	#[IsGranted('ROLE_ADMIN', message: "Vous ne pouvez pas réaliser cette action")]
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
