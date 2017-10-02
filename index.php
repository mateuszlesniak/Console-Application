<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 15.09.2017
 * Time: 20:22
 */

require_once 'vendor/autoload.php';

use Color\CustomColor;
use Commando\Command;
use Exception\UrlException;
use Exception\ApiException;
use Exception\FatalException;
use Utils\StringUtils;

$commandLine = new CustomCommand();

if (EnvironmentUtils::isCommandLineInterface()) {
	if (false === getenv('ENVIRONMENT')) {
		EnvironmentUtils::setProductionEnvironment();
	}

	$greeting = new CustomColor();

	echo $greeting('Hello in beer application!')->green() . EnvironmentUtils::getEndOfLineSymbol();
	echo $greeting('Type "php index.php --help" for more information about using application')->red()->bold() . EnvironmentUtils::getEndOfLineSymbol();
	echo $greeting('Have fun!')->yellow() . EnvironmentUtils::getEndOfLineSymbol();

	$commandLine->option('type')
		->aka('t')
		->require()
		->describedAs('Type of request (beer/beers)');

	$commandLine->option('page')
		->aka('p')
		->require(false)
		->describedAs('[optional] Select page of result list. Default is first');

	$commandLine->option('ibu')
		->require(false)
		->describedAs('[optional] If you want list of beers please specify IBU');

	$commandLine->option('identifier')
		->aka('i')
		->require(false)
		->describedAs('[optional] Id for specific request type (beer)');

	$commandLine->option('format')
		->aka('f')
		->require(false)
		->describedAs('[optional] Kind of returning format (json,php,xml). Leave empty for default (json)')
		->must(function ($format) {
			$formatList = [
				Api::FORMAT__JSON,
				Api::FORMAT__PHP,
				Api::FORMAT__XML,
			];

			return in_array(StringUtils::toLower($format), $formatList);
		});

	$commandLine->option('random')
		->aka('r')
		->default('false')
		->require(false)
		->describedAs('[optional] If you want random beer please use this option with true value');

} else {
	if (false === getenv('ENVIRONMENT')) {
		EnvironmentUtils::setDevelopmentEnvironment();
	}

	define('PHP_EOL_WEB', '<br>');
}

try {
	$commandLineApplication = new Index();

	if (EnvironmentUtils::isCommandLineInterface()) {
		$commandLineApplication->setVariablesFormCommandLine($commandLine);
		$commandLineApplication->checkIfRequestIsValid();
		$commandLineApplication->processCliRequest();
	} else {
		$commandLineApplication->processBrowserRequest('beer');
	}

} catch (FatalException $fatalException) {
	if (EnvironmentUtils::isDeveloperEnvironment()) {
		echo $fatalException->getMessage();
	}

	echo 'Fatal error. Application stopped' . EnvironmentUtils::getEndOfLineSymbol();
} catch (UrlException $urlException) {
	if (EnvironmentUtils::isDeveloperEnvironment()) {
		echo $urlException->getMessage();
	}

	echo 'Fatal error. Application stopped' . EnvironmentUtils::getEndOfLineSymbol();
} catch (ApiException $apiException) {
	echo $apiException->getMessage();
} catch (\Exception $exception) {
	if (EnvironmentUtils::isDeveloperEnvironment()) {
		echo $exception->getMessage();
	}
	echo 'Fatal error. Application stopped' . EnvironmentUtils::getEndOfLineSymbol();
}

class Index {

	private $variables = [
		'type'       => null,
		'page'       => 1,
		'ibu'        => null,
		'identifier' => null,
		'format'     => Api::FORMAT__ALL,
		'random'     => false,
	];
	private $apiConnection = null;

	private $cacheConsoleText = null;

	/**
	 * @param Command $command
	 *
	 * @throws ApiException
	 */
	public function setVariablesFormCommandLine(Command $command): void
	{
		$this->variables['type'] = $command['type'];

		if (true === boolval($command['random'])) {
			$this->variables['random'] = true;
		}

		if (StringUtils::isEmpty($command['identifier']) &&
			in_array($this->getRequestType(), [
					Api::ENDPOINT__BEER,
				]
			) &&
			false === boolval($command['random'])
		) {
			throw new ApiException("You cannot use {$this->getRequestType()} endpoint until you set id");
		} else {
			$this->variables['identifier'] = $command['identifier'];
		}

		if (!StringUtils::isEmpty($command['format'])) {
			$this->variables['format'] = StringUtils::toLower($command['format']);
		}

		if (!StringUtils::isEmpty($command['page'])) {
			$this->variables['page'] = StringUtils::toLower($command['page']);
		}
		if (StringUtils::isEmpty($command['ibu']) &&
			in_array($this->getRequestType(), [
					Api::ENDPOINT__BEERS,
				]
			)
		) {
			throw new ApiException("You cannot use {$this->getRequestType()} endpoint until you set ibu");
		} else {
			$this->variables['ibu'] = $command['ibu'];
		}
	}

	/**
	 * Created for visual testing
	 *
	 * @@param string $type
	 *
	 * @throws FatalException
	 */
	public function processBrowserRequest($type): void
	{
		$api = $this->getApiConnection();
		$api->formatJson();

		switch ($type) {
			case Api::ENDPOINT__BEER:
				//				$result = $api->getBeerMockup();
				$result = $api->getRandomBeer();
				//				$result = $api->getBeer('P7CZW0');
				//				$result = $api->getBeerList(100, 1);
				break;
			case Api::ENDPOINT__BEERS:
				$result = $api->getBeerList(random_int(0, 120), 1);
				break;
			default:
				throw new FatalException('Unexpected error. Exiting...');
		}

		$this->displayResult($result);
	}

	/**
	 * @throws FatalException
	 */
	public function processCliRequest(): void
	{
		$api = $this->getApiConnection();

		switch ($this->getRequestFormat()) {
			case Api::FORMAT__XML:
				$api->formatXml();
				break;
			case Api::FORMAT__PHP:
				$api->formatPhp();
				break;
			case Api::FORMAT__ALL:
				$api->formatAll();
				break;
			case Api::FORMAT__JSON:
			default:
				$api->formatJson();
				break;
		}

		switch ($this->getRequestType()) {
			case Api::ENDPOINT__BEER:
				if ($this->canGetRandomBeer()) {
					$result = $api->getRandomBeer();

				} else {
					$result = $api->getBeer($this->getRequestIdentifier());
				}
				break;
			case Api::ENDPOINT__BEERS:
				$result = $api->getBeerList($this->getRequestIbu(), $this->getRequestPage());
				break;
			default:
				throw new FatalException('Unexpected error. Exiting...');
		}

		if (EnvironmentUtils::isDeveloperEnvironment()) {
			$this->displayResult($result);
		}
	}

	/**
	 * @return Api
	 * @throws FatalException
	 */
	public function getApiConnection()
	{
		if (null === $this->apiConnection) {
			$this->apiConnection = new Api();
		}

		return $this->apiConnection;
	}

	/**
	 * @return string
	 */
	private function getRequestType(): string
	{
		return $this->variables['type'];
	}

	/**
	 * @return string
	 * @throws ApiException
	 */
	private function getRequestIdentifier()
	{
		if (null === $this->variables['identifier'] && false === $this->canGetRandomBeer()) {
			throw new ApiException('Cannot get id. Check if you set it before');
		}

		return $this->variables['identifier'];
	}

	/**
	 * @return string
	 */
	private function getRequestFormat(): string
	{
		return $this->variables['format'];
	}

	/**
	 * @return int
	 */
	private function getRequestPage(): int
	{
		return $this->variables['page'];
	}

	/**
	 * @return int
	 * @throws ApiException
	 */
	private function getRequestIbu(): int
	{
		if (null === $this->variables['ibu']) {
			throw new ApiException('Cannot get IBU. Check if you set it before');
		}

		return intval($this->variables['ibu']);
	}

	/**
	 * @param Response $response
	 */
	private function displayResult($response)
	{
		$totalPages = 0;
		if (property_exists($response->getData(), 'numberOfPages')) {
			$totalPages = $response->getData()->numberOfPages;
		}

		$currentPage = 0;
		if (property_exists($response->getData(), 'currentPage')) {
			$currentPage = $response->getData()->currentPage;
		}

		$beersCount = count($response->getPreparedData());

		if (0 < $totalPages) {
			echo "{$this->getCacheConsoleText("---------------------------------------")->green()}" . EnvironmentUtils::getEndOfLineSymbol();
			echo "{$this->getCacheConsoleText("Page {$currentPage} of {$totalPages}")->green()}" . EnvironmentUtils::getEndOfLineSymbol();
			echo "{$this->getCacheConsoleText("---------------------------------------")->green()}" . EnvironmentUtils::getEndOfLineSymbol();
		}

		$currentBeer = 1;
		foreach ($response->getPreparedData() as $beer) {
			if (1 < $beersCount) {
				echo "{$this->getCacheConsoleText("---- Beer {$currentBeer} of {$beersCount} ----")->yellow()}" . EnvironmentUtils::getEndOfLineSymbol();
			}

			$beer->showToScreen();
		}
	}

	public function checkIfRequestIsValid(): bool
	{
		if ('beers' === $this->getRequestType()) {
			//will works only when default page will be not set
			if (null === $this->getRequestPage()) {
				throw new ApiException('Please specify page number if you want list of beers!');
			}
		}

		if ('beer' === $this->getRequestType() && (null === $this->getRequestIdentifier() && false === $this->canGetRandomBeer())) {
			throw new ApiException('Please specify beer id if you want specific kind of beer!');
		}

		return true;
	}

	/**
	 * @param string $text
	 *
	 * @return CustomColor
	 */
	public function getCacheConsoleText($text)
	{
		if (null === $this->cacheConsoleText) {
			$this->cacheConsoleText = new CustomColor($text);
		}

		return ($this->cacheConsoleText)($text);
	}

	/**
	 * @return bool
	 */
	private function canGetRandomBeer(): bool
	{
		return $this->variables['random'];
	}

	/**
	 * @return string
	 */
	public static function getRootDirectory(): string
	{
		return __DIR__;
	}
}