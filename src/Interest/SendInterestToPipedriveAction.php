<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Interest;

use BrandEmbassy\Slim\ActionHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;
use Devio\Pipedrive\Pipedrive;
use Nette\Utils\Json;

class SendInterestToPipedriveAction implements ActionHandler
{
	/**
	 * @var Pipedrive
	 */
	private $pipedrive;


	public function __construct(Pipedrive $pipedrive)
	{
		$this->pipedrive = $pipedrive;
	}


	/**
	 * @param mixed[] $arguments
	 *
	 * Accepts body:
	 *  {
	 *		"name": "Jan Mikes",
	 *		"email": "jan@entrydo.com"
	 *	}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = []): ResponseInterface
	{
		$data = Json::decode($request->getBody()->getContents());

		return $response->withJson(['status' => 'ok'], 201);
	}
}
