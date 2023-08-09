<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Repository\MobileRepository;
use App\Services\CacheService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MobileController extends AbstractController
{
	public function __construct(
		private MobileRepository $mobileRepository,
		private SerializerInterface $serializer,
		private CacheService $cacheService)
	{
		$this->mobileRepository = $mobileRepository;
		$this->serializer = $serializer;
		$this->cacheService = $cacheService;
	}
    #[Route('/api/mobiles', name: 'app_mobiles_list', methods: ["GET"])]
    public function getMobilesList(): JsonResponse
    {
		$mobilesList = $this->mobileRepository->findAll();

		$jsonMobilesList = $this->serializer->serialize($mobilesList, "json", ["groups" => "getMobile"]);

        return new JsonResponse($jsonMobilesList, Response::HTTP_OK, [], true);
    }
	#[Route('/api/mobiles/{id}', name: 'app_mobile', requirements: ['id' => '\d+'], methods: ['GET'])]
	public function getMobile(Mobile $mobile): JsonResponse
	{
		if(!$mobile)
		{
			return new JsonResponse(null, Response::HTTP_NOT_FOUND);
		}

		$jsonMobile = $this->serializer->serialize($mobile, "json", ['groups' => 'getMobile']);

		return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
	}
}
