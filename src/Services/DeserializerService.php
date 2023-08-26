<?php

namespace App\Services;

use App\Entity\User;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class DeserializerService
{
	public function __construct(
		private readonly HttpExceptionEmptyDataService $httpExceptionService,
		private readonly SerializerInterface $serializer,
	)
	{
	}

	public function requestDeserializer(Request $request): HttpExceptionEmptyDataService|User
	{
		$user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

		$this->httpExceptionService->throwHttpExceptionIfEmptyData($this->httpExceptionService::RESOURCE_ALREADY_EXIST ,$user);

		return $user;
	}
}