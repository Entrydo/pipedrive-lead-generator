<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Lead;

use BrandEmbassy\Slim\ActionHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;

class GenerateLeadAction implements ActionHandler
{
	/**
	 * @param mixed[] $arguments
	 *
	 * Accepts body:
	 *  {
	 *		name: '',
	 *		companyId: '',
	 *		web: '',
	 *		companyName: '',
	 *		eventName: '',
	 *		expectedVisitors: '',
	 *		email: '',
	 *		phone: '',
	 *		eventDate: ''
 	 *	}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = []): ResponseInterface
	{
		return $response->withJson(['status' => 'ok'], 201);
	}
}
