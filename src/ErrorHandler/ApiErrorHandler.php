<?php

declare(strict_types=1);

namespace Entrydo\Pipedrive\ErrorHandler;

use BrandEmbassy\Slim\ErrorHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;
use Throwable;
use Tracy\ILogger;

class ApiErrorHandler implements ErrorHandler
{
	/**
	 * @var ILogger
	 */
	private $logger;

	public function __construct(ILogger $logger)
	{
		$this->logger = $logger;
	}


	public function __invoke(RequestInterface $request, ResponseInterface $response, ?Throwable $e = null): ResponseInterface
	{
		$this->logger->log($e, ILogger::EXCEPTION);

		return $response->withJson(['errors' => ['Unknown error occured. Please try again later.']], 500);
	}
}
