<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 15.09.2017
 * Time: 20:46
 */

use Exception\FatalException;
use Exception\ApiException;
use Utils\StringUtils;
use Beer\Beer;
use Utils\File\JsonUtils;
use Utils\File\XmlUtils;
use Utils\File\PhpUtils;

class Api {

	const CONNECTION__URL = 'https://api.brewerydb.com/v2/';
	const CONNECTION__API_KEY = 'API_KEY';

	const ENDPOINT__BEERS = 'beers';
	const ENDPOINT__BEER = 'beer';

	const FORMAT__ALL = 'all';
	const FORMAT__JSON = 'json';
	const FORMAT__XML = 'xml';
	const FORMAT__PHP = 'php';

	private $url = null;
	private $saveAll = false;

	public function checkConnection()
	{

		$this->getUrl()->changeFormat(self::FORMAT__JSON);

		return StringUtils::toLower((new Response($this->connect(), $this->getUrl()->getFormat()))->getStatus()) === 'success';
	}

	/**
	 * @throws FatalException
	 * @throws ApiException
	 * @return Response
	 */
	protected function connect(): Response
	{
		$this->addApiKeyToUrl();

		EnvironmentUtils::printToConsole($this->getUrl()->buildUrl(), 'built url');

		// create curl resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, $this->getUrl()->buildUrl());

		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string
		$output = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch);

		try {
			$result = new Response($output, $this->getUrl()->getFormat());
		} catch (Exception $exception) {
			throw new FatalException('Cannot get response. Please try again');
		}

		if (!$result->isValid()) {
			throw new ApiException('You have errors in command. Please check it and try again');
		}

		return $result;
	}

	/**
	 * @return Api
	 */
	private function addApiKeyToUrl(): Api
	{
		$this->getUrl()->addQueryParameter('key', self::CONNECTION__API_KEY);

		return $this;
	}

	/**
	 * @return Url
	 */
	public function getUrl(): Url
	{
		if (null === $this->url) {
			$this->url = (new Url())->setUrlSite(self::CONNECTION__URL);
		}

		return $this->url;
	}

	/**
	 * @return Api
	 */
	public function formatXml(): Api
	{
		$this->getUrl()->changeFormat(self::FORMAT__XML);

		return $this;
	}

	/**
	 * @return Api
	 */
	public function formatJson(): Api
	{
		$this->getUrl()->changeFormat(self::FORMAT__JSON);

		return $this;
	}

	/**
	 * @return Api
	 */
	public function formatAll(): Api
	{
		$this->getUrl()->changeFormat(self::FORMAT__JSON);

		$this->saveAll = true;

		return $this;
	}

	/**
	 * @return Api
	 */
	public function formatPhp(): Api
	{
		$this->getUrl()->changeFormat(self::FORMAT__PHP);

		return $this;
	}

	/**
	 * @param string $identifier
	 *
	 * @return Response
	 */
	public function getBeer($identifier)
	{
		$this->getUrl()->addPath('beer')->addPath($identifier);

		return $this->processResult($this->connect());
	}

	/**
	 * @return Response
	 */
	public function getRandomBeer()
	{
		$this->getUrl()->addPath('beer')->addPath('random');

		return $this->processResult($this->connect());
	}

	/**
	 * @return Response
	 */
	public function getBeerMockup()
	{
		return $this->processResult(new Response("{\"message\":\"Request Successful\",\"data\":{\"id\":\"oeGSxs\",\"name\":\"Naughty 90\",\"nameDisplay\":\"Naughty 90\",\"description\":\"Our Naughty 90 Oaked IPA was inspired by ocean bound, oak barreled brews of the 1800\u2019s. Packed with hops on a 90-minute schedule, uniquely malted, then aged on a bed of oak, this IPA delivers a taste that is both innovative and timeless.\",\"abv\":\"6.2\",\"ibu\":\"100\",\"glasswareId\":5,\"availableId\":4,\"styleId\":30,\"isOrganic\":\"N\",\"labels\":{\"icon\":\"https:\/\/s3.amazonaws.com\/brewerydbapi\/beer\/oeGSxs\/upload_FiPLQC-icon.png\",\"medium\":\"https:\/\/s3.amazonaws.com\/brewerydbapi\/beer\/oeGSxs\/upload_FiPLQC-medium.png\",\"large\":\"https:\/\/s3.amazonaws.com\/brewerydbapi\/beer\/oeGSxs\/upload_FiPLQC-large.png\"},\"status\":\"verified\",\"statusDisplay\":\"Verified\",\"createDate\":\"2012-01-03 02:43:45\",\"updateDate\":\"2015-12-16 10:34:27\",\"glass\":{\"id\":5,\"name\":\"Pint\",\"createDate\":\"2012-01-03 02:41:33\"},\"available\":{\"id\":4,\"name\":\"Seasonal\",\"description\":\"Available at the same time of year, every year.\"},\"style\":{\"id\":30,\"categoryId\":3,\"category\":{\"id\":3,\"name\":\"North American Origin Ales\",\"createDate\":\"2012-03-21 20:06:45\"},\"name\":\"American-Style India Pale Ale\",\"shortName\":\"American IPA\",\"description\":\"American-style India pale ales are perceived to have medium-high to intense hop bitterness, flavor and aroma with medium-high alcohol content. The style is further characterized by floral, fruity, citrus-like, piney, resinous, or sulfur-like American-variety hop character. Note that one or more of these American-variety hop characters is the perceived end, but the hop characters may be a result of the skillful use of hops of other national origins. The use of water with high mineral content results in a crisp, dry beer. This pale gold to deep copper-colored ale has a full, flowery hop aroma and may have a strong hop flavor (in addition to the perception of hop bitterness). India pale ales possess medium maltiness which contributes to a medium body. Fruity-ester flavors and aromas are moderate to very strong. Diacetyl can be absent or may be perceived at very low levels. Chill and\/or hop haze is allowable at cold temperatures. (English and citrus-like American hops are considered enough of a distinction justifying separate American-style IPA and English-style IPA categories or subcategories. Hops of other origins may be used for bitterness or approximating traditional American or English character. See English-style India Pale Ale\",\"ibuMin\":\"50\",\"ibuMax\":\"70\",\"abvMin\":\"6.3\",\"abvMax\":\"7.5\",\"srmMin\":\"6\",\"srmMax\":\"14\",\"ogMin\":\"1.06\",\"fgMin\":\"1.012\",\"fgMax\":\"1.018\",\"createDate\":\"2012-03-21 20:06:45\",\"updateDate\":\"2015-04-07 15:26:37\"}},\"status\":\"success\"}", 'json'));
	}

	/**
	 * @param int $ibu
	 * @param int $page
	 *
	 * @return Response
	 */
	public function getBeerList($ibu, $page = 1)
	{
		$this->getUrl()->addPath('beers')->addQueryParameter('p', $page)->addQueryParameter('ibu', $ibu);

		return $this->processResult($this->connect());
	}

	/**
	 * @param Response $response
	 *
	 * @return Response
	 * @throws ApiException
	 */
	private function processResult(Response $response)
	{
		if (!$response->isValid()) {
			throw new ApiException('Cannot process response from server. Please try again');
		}

		$data = $response->getData();

		$beerList = [];

		if (is_object($data) && property_exists($data, 'id')) {
			$beerList[] = (new Beer())->retrieve($data);
		} else {
			foreach ($data as $beerData) {
				$beerList[] = (new Beer())->retrieve($beerData);
			}
		}

		$response->setPreparedData($beerList);

		if (EnvironmentUtils::isDeveloperEnvironment()) {
			$xmlToJson = $response::convert('xml', 'json', $response->getRawResponse());
			$xmlToPhp = $response::convert('xml', 'php', $response->getRawResponse());

			$phpToJson = $response::convert('php', 'json', $response->getRawResponse());
			$phpToXml = $response::convert('php', 'xml', $response->getRawResponse());
		}

		switch ($this->getUrl()->getFormat()) {
			case Api::FORMAT__JSON:
				JsonUtils::save($response->getRawResponse());

				if (true === $this->saveAll) {
					$jsonToXml = $response::convert('json', 'xml', $response->getRawResponse());
					$jsonToPhp = $response::convert('json', 'php', $response->getRawResponse());

					XmlUtils::save($jsonToXml);
					PhpUtils::save($jsonToPhp);
				}
				break;
			case Api::FORMAT__XML:
				XmlUtils::save($response->getRawResponse());
				break;
			case Api::FORMAT__PHP:
				PhpUtils::save($response->getRawResponse());
				break;
		}

		return $response;
	}

}