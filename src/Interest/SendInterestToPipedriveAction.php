<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Interest;

use BrandEmbassy\Slim\ActionHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;
use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Pipedrive;
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


	public function __construct(int $stageId, Pipedrive $pipedrive)
	{
		$this->pipedrive = $pipedrive;
		$this->stageId = $stageId;
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
		// Search person by email
		$personSearchResponse = $this->pipedrive->searchResults()->searchFromField(
			$email,
			'personField',
			'email',
			[
				'return_item_ids' => 1,
				'exact_match' => 1,
			]
		);
		$personSearchResults = $personSearchResponse->getData();

		if (\count($personSearchResults)) {
			return $personSearchResults[0]->id;
		}

		$personAddResponse = $this->pipedrive->persons()->add([
			'name' => $name,
			'email' => $email,
			'phone' => str_replace(' ', '', $phone),
		]);

		return $personAddResponse->getData()->id;
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
