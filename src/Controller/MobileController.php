<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Services\CacheService;
use App\Services\HttpExceptionEmptyDataService;
use JMS\Serializer\SerializationContext;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MobileController extends AbstractController
{
	private const ID_MOBILES_CACHE = "mobilesCache";

	public function __construct(
		private readonly CacheService $cacheService,
		private readonly HttpExceptionEmptyDataService $httpExceptionEmptyData
	)
	{
	}

	/**
	 * @return JsonResponse
	 * @throws InvalidArgumentException
	 */
    #[Route('/api/mobiles', name: 'app_mobiles_list', methods: ["GET"])]
    public function getMobilesList(): JsonResponse
    {
		$jsonMobilesList = $this->cacheService->getMobilesCache(self::ID_MOBILES_CACHE);

        return new JsonResponse($jsonMobilesList, Response::HTTP_OK, [], true);
    }


	/**
	 * @param Mobile $mobile
	 *
	 * @return JsonResponse
	 * @throws InvalidArgumentException
	 */
	#[Route('/api/mobile/{id}', name: 'app_mobile', requirements: ['id' => '\d+'], methods: ['GET'])]
	public function getMobile(
		Mobile $mobile
	): JsonResponse
	{
		$this->httpExceptionEmptyData->throwHttpExceptionIfEmptyData($this->httpExceptionEmptyData::NOT_FOUND_STATUT_CODE, $mobile);

		$jsonMobile = $this->cacheService->getMobileCache($mobile);

		return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
	}
}
