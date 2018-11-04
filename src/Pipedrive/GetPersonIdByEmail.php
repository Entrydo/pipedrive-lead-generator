<?php declare (strict_types=1);

namespace Entrydo\Pipedrive\Pipedrive;

use Devio\Pipedrive\Pipedrive;
use Entrydo\Pipedrive\Pipedrive\Exceptions\PersonNotFound;

final class GetPersonIdByEmail
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
	 * @throws PersonNotFound
	 */
	public function __invoke(string $email): int
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

		throw new PersonNotFound();
	}
}
