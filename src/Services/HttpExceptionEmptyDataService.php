<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionEmptyDataService
{
	public const NOT_FOUND_STATUT_CODE = 404;
	public const RESOURCE_ALREADY_EXIST = 406;

	/**
	 * @param int   $statutCode
	 * @param mixed $data
	 *
	 * @return HttpException|bool
	 */
	public function throwHttpExceptionIfEmptyData(int $statutCode, mixed $data): HttpException|bool
	{
		return (!$data) ? throw new HttpException($statutCode) : false;
	}
}