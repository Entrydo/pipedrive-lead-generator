<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Interest;

use BrandEmbassy\Slim\ActionHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;
use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Pipedrive;
use Entrydo\Pipedrive\Pipedrive\Exceptions\PersonNotFound;
use Entrydo\Pipedrive\Pipedrive\GetPersonIdByEmail;
use Nette\Utils\Json;

class SendInterestToPipedriveAction implements ActionHandler
{
	/**
	 * @var Pipedrive
	 */
	private $pipedrive;

	/**
	 * @var int
	 */
	private $stageId;

	/**
	 * @var GetPersonIdByEmail
	 */
	private $getPersonIdByEmail;


	public function __construct(int $stageId, Pipedrive $pipedrive, GetPersonIdByEmail $getPersonIdByEmail)
	{
		$this->pipedrive = $pipedrive;
		$this->stageId = $stageId;
		$this->getPersonIdByEmail = $getPersonIdByEmail;
	}


	/**
	 * @param mixed[] $arguments
	 *
	 * Accepts body:
	 *  {
	 *		"name": "Jan Mikes",
	 *      "phone": "+420773686207",
	 *		"email": "jan@entrydo.com"
	 *	}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = []): ResponseInterface
	{
		$data = Json::decode($request->getBody()->getContents());

		$name = $data->name;
		$phone = $data->phone ?? '';
		$personId = $this->getPersonId($name, $phone, $data->email);

		$this->createDeal(
			'Deal - ' . $name,
			$personId
		);

		return $response->withJson(['status' => 'ok'], 201);
	}


	private function getPersonId(string $name, string $phone, string $email): int
	{
		try {
			return $this->getPersonIdByEmail->__invoke($email);
		} catch (PersonNotFound $e) {
			$personAddResponse = $this->pipedrive->persons()->add([
				'name' => $name,
				'email' => $email,
				'phone' => str_replace(' ', '', $phone),
			]);

			return $personAddResponse->getData()->id;
		}
	}


	private function createDeal(
		string $title,
		int $personId
	): Response
	{
		return $this->pipedrive->deals()->add([
			'title' => $title,
			'person_id' => $personId,
			'stage_id' => $this->stageId,
		]);
	}
}
