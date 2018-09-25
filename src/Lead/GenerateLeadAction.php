<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Lead;

use BrandEmbassy\Slim\ActionHandler;
use BrandEmbassy\Slim\Request\RequestInterface;
use BrandEmbassy\Slim\Response\ResponseInterface;
use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Pipedrive;
use Devio\Pipedrive\Resources\Deals;
use Devio\Pipedrive\Resources\Organizations;
use Devio\Pipedrive\Resources\Persons;
use Devio\Pipedrive\Resources\SearchResults;
use Entrydo\Pipedrive\CustomField\DealCustomField;
use Entrydo\Pipedrive\CustomField\OrganizationCustomField;
use Nette\Utils\Json;

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
	 *		"name": "Jan Mikeš",
	 *		"companyId": "1234567",
	 *		"web": "https://janmikes.cz",
	 *		"companyName": "Entrajdo",
	 *		"eventName": "Moje test událost",
	 *		"expectedVisitors": 1000,
	 *		"email": "jan@entrydo.com",
	 *		"phone": "+420773686207",
	 *		"eventDate": "2018-09-30"
	 *	}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = []): ResponseInterface
	{
		$data = Json::decode($request->getBody()->getContents());

		$organizationId = $this->getOrganizationId($data->companyId, $data->companyName);
		$personId = $this->getPersonId($organizationId, $data->name, $data->email, $data->phone);

		$this->createDeal(
			$data->eventName,
			$personId,
			$organizationId,
			$data->expectedVisitors,
			$data->web,
			$data->eventDate
		);

		return $response->withJson(['status' => 'ok'], 201);
	}


	private function getOrganizationId(string $companyId, string $companyName): int
	{
		// Search by Company ID
		$organizationSearchResponse = $this->pipedrive->searchResults()->searchFromField(
			$companyId,
			'organizationField',
			OrganizationCustomField::ID,
			[
				'return_item_ids' => 1,
				'exact_match' => 1,
			]
		);
		$organizationSearchResults = $organizationSearchResponse->getData();

		if (\count($organizationSearchResults)) {
			return $organizationSearchResults[0]->id;
		}

		// If does not exist, create company
		$organizationAddResponse = $this->pipedrive->organizations()->add([
			'name' => $companyName,
			OrganizationCustomField::ID => $companyId,
		]);

		return $organizationAddResponse->getData()->id;
	}


	private function getPersonId(int $organizationId, string $name, string $email, string $phone): int
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
			'org_id' => $organizationId,
		]);

		return $personAddResponse->getData()->id;
	}


	private function createDeal(
		string $title,
		int $personId,
		int $organizationId,
		int $attendance,
		string $website,
		string $date
	): Response
	{
		return $this->pipedrive->deals()->add([
			'title' => $title,
			'person_id' => $personId,
			'org_id' => $organizationId,
			DealCustomField::ATTENDANCE => $attendance,
			DealCustomField::WEBSITE => $website,
			DealCustomField::EVENT_DATE => $date,
		]);
	}
}
