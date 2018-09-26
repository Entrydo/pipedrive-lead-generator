<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\CORS;

use BrandEmbassy\Slim\Middleware;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;

class CORSMiddleware implements Middleware
{
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
	{
		return $response
			->withHeader('Access-Control-Allow-Origin', '*')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, X-Authorization, Accept, Content-Type, Origin, X-Origin, Accept-Language')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	}
}
