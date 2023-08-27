<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestService
{
	private const PAGE = 1;

	private const LIMIT = 10;
	public function __construct(
		protected RequestStack $requestStack
	)
	{
	}
	public function getRequestKeyPageData()
	{
		$request = $this->requestStack->getCurrentRequest();

		return $request->get("page", self::PAGE);
	}

	public function getRequestKeyLimitData()
	{
		$request = $this->requestStack->getCurrentRequest();

		return $request->get("limit", self::LIMIT);
	}
}