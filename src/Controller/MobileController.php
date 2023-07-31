<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Repository\MobileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MobileController extends AbstractController
{
    #[Route('/api/mobiles', name: 'app_mobiles_list', methods: ["GET"])]
    public function getMobilesList(
		MobileRepository $mobileRepository,
		SerializerInterface $serializer,
		UrlGeneratorInterface $urlGenerator
	): JsonResponse
    {
		$mobilesList = $mobileRepository->findAll();

		$jsonMobilesList = $serializer->serialize($mobilesList, "json", ["groups" => "getMobile"]);

        return new JsonResponse($jsonMobilesList, Response::HTTP_OK, [], true);
    }
	#[Route('/api/mobiles/{id}', name: 'app_mobile', requirements: ['id' => '\d+'], methods: ['GET'])]
	public function getMobile(Mobile $mobile, SerializerInterface $serializer): JsonResponse
	{
		if(!$mobile)
		{
			return new JsonResponse(null, Response::HTTP_NOT_FOUND);
		}

		$jsonMobile = $serializer->serialize($mobile, "json", ['groups' => 'getMobile']);

		return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
	}
}
