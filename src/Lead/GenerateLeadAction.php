<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Lead;

use BrandEmbassy\Slim\ActionHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;
use Devio\Pipedrive\Pipedrive;
use Devio\Pipedrive\Resources\Persons;

class GenerateLeadAction implements ActionHandler
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
		/** @var $personsResource Persons */
		$personsResource = $this->pipedrive->make('persons');

		// 1. Hledat společnost
		// je/neni vytvoří/najde

		// 2. hledat osobu
		// je/neni vytvoří/najde

		// 3. vytvořit deal


		return $response->withJson(['status' => 'ok'], 201);
	}
}
