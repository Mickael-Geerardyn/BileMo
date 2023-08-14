<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionEmptyData
{
	public const NOT_FOUND_STATUT_CODE = 404;

	/**
	 * @param int   $statutCode
	 * @param mixed $data
	 *
	 * @return bool
	 */
	public function throwHttpExceptionIfEmptyData(int $statutCode, mixed $data): bool
	{
		return (!$data) ? throw new HttpException($statutCode) : false;
	}
}