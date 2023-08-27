<?php

namespace App\Services;

use App\Entity\Client;
use App\Entity\Mobile;
use App\Repository\MobileRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializationContext;
use Psr\Cache\InvalidArgumentException;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{
	public const MOBILES_CACHE_TAG = "mobilesCacheTag";
	public const MOBILE_CACHE_TAG = "mobileCacheTag";
	public const USERS_CACHE_TAG = "usersCacheTag";
	public const USER_CACHE_TAG = "userCacheTag";
	private const USER_CACHE_ID = "user_email_";
	private const EXPIRATION_TIME_CACHE = 36000;
	private string $usersCacheId = "usersCacheId_";
	private string $mobilesCacheId = "mobilesCacheId_";
	private string $page;
	private string $limit;

	public function __construct(
		private readonly TagAwareCacheInterface $tagAwareCache,
		private readonly UserRepository $userRepository,
		private readonly MobileRepository $mobileRepository,
		private readonly SerializerInterface $serializer,
		private readonly HttpExceptionEmptyDataService $httpExceptionService,
		private readonly RequestService $requestService
	)
	{
		$this->page = $this->requestService->getRequestKeyPageData();
		$this->limit = $this->requestService->getRequestKeyLimitData();
		$this->usersCacheId .= $this->page . "_" . $this->limit;
		$this->mobilesCacheId .= $this->page . "_" . $this->limit;
	}

	/**
	 * @param Mobile $mobile
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function getMobileCache(Mobile $mobile): string
	{
			return $this->tagAwareCache->get($mobile->getId(), function (ItemInterface $item) use ($mobile){

				$item->tag(self::MOBILE_CACHE_TAG);
				$item->expiresAfter(self::EXPIRATION_TIME_CACHE);
				$context = SerializationContext::create()->setGroups(['getMobile']);

				return $this->serializer->serialize($mobile, "json", $context);
			});
	}

	/**
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function getMobilesCache(): mixed
	{
		return $this->tagAwareCache->get($this->mobilesCacheId, function (ItemInterface $item) {

				$item->tag(self::MOBILES_CACHE_TAG);
				$item->expiresAfter(self::EXPIRATION_TIME_CACHE);

				$mobilesList = $this->mobileRepository->findAllByPagination($this->page, $this->limit);

				// Check if mobiles array is not empty before serialize. If mobiles is a serialize array, this will not be 	 an HttpException instance because param should be string or object, json is an array and so isn't entered in the if condition in ExceptionSubscriber to send good http statut code.
				$this->httpExceptionService->throwHttpExceptionIfEmptyData($this->httpExceptionService::NOT_FOUND_STATUT_CODE, $mobilesList);

				$context = SerializationContext::create()->setGroups(['getMobiles']);

				return $this->serializer->serialize($mobilesList, "json", $context);
			});
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getUsersCache(Client $client): mixed
	{
		return $this->tagAwareCache->get($this->usersCacheId, function (ItemInterface $item) use ($client)
		{
			$item->tag(self::USERS_CACHE_TAG);
			$item->expiresAfter(self::EXPIRATION_TIME_CACHE);

			$usersList = $this->userRepository->findAllByPagination($client, $this->page, $this->limit);

			$this->httpExceptionService->throwHttpExceptionIfEmptyData($this->httpExceptionService::NOT_FOUND_STATUT_CODE, $usersList);

			$context = SerializationContext::create()->setGroups(['getClientUsers']);

			return $this->serializer->serialize($usersList, "json", $context);
		});
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getUserCache(string $userEmailCacheId, Client $client): mixed
	{
		return $this->tagAwareCache->get(self::USER_CACHE_ID . $userEmailCacheId, function (ItemInterface $item) use ($userEmailCacheId, $client)
		{

			$usersList = $this->userRepository->findOneClientUser($userEmailCacheId, $client);

			$this->httpExceptionService->throwHttpExceptionIfEmptyData($this->httpExceptionService::NOT_FOUND_STATUT_CODE, $usersList);

			$item->tag(self::USER_CACHE_TAG);
			$item->expiresAfter(self::EXPIRATION_TIME_CACHE);

			$context = SerializationContext::create()->setGroups(['getClientUser']);

			return $this->serializer->serialize($usersList, "json", $context);
		});
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function invalidateTagsAndKey(array $tags, string $userEmail = null): bool
	{
		if(!$userEmail)
		{
			$this->tagAwareCache->delete(self::USER_CACHE_ID . $userEmail);
		}

		return $this->tagAwareCache->invalidateTags($tags);
	}
}