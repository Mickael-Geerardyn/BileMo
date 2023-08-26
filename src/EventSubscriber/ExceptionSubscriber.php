<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
	private const HTTP_CODES_MESSAGES =
		[
			400 => "Une erreur est intervenue lors de l'authentification, veuillez réessayer",
			401 => "Vous n'êtes pas autorisé à accéder à cette ressource, veuillez vous connecter",
			403 => "Accès refusé",
			404 => "La resource demandée n'a pas été trouvée",
			405 => "Méthode HTTP non autorisée",
			406 => "L'email de l'utilisateur est déjà existant",
			500 => "Une erreur inconnue est survenue"
		];

    public function onKernelException(ExceptionEvent $event): void
    {
		$exception = $event->getThrowable();

		if ($exception instanceof HttpException) {

			$data = [
				'status' => $exception->getStatusCode(),
				'message' => (self::HTTP_CODES_MESSAGES[$exception->getStatusCode()]) ? self::HTTP_CODES_MESSAGES[$exception->getStatusCode()] : $exception->getMessage()
			];

			$event->setResponse(new JsonResponse($data));

		} else {

			$data = [
				'status' => 500,
				'message' => $exception->getMessage() //self::HTTP_CODES_MESSAGES[500]
			];
		}

		$event->setResponse(new JsonResponse($data));
	}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
