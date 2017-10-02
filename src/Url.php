<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 15.09.2017
 * Time: 23:03
 */

use Exception\UrlException;
use Utils\StringUtils;

class Url {

	private $urlSite;
	private $queryOptions = [];
	private $pathStack = [];

	private $changed = false;
	private $cachedUrl = '';

	/**
	 * @param string $urlSite
	 *
	 * @throws UrlException
	 *
	 * @return Url
	 */
	public function setUrlSite($urlSite): Url
	{
		if (false === strpos($urlSite, 'http://') && false === strpos($urlSite, 'https://')) {
			throw new UrlException($urlSite . ' is not properly formatted url!');
		}

		$this->urlSite = $this->filter($urlSite);

		$this->changed = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function buildUrl(): string
	{
		if ($this->isChanged()) {
			$queryStack = [];

			foreach ($this->queryOptions as $name => $value) {
				$queryStack[] = $name . '=' . $value;
			}

			$this->cachedUrl = $this->urlSite . '/' . $this->buildPath() . (!empty($queryStack) ? "?" : "") . implode('&', $queryStack);
		}

		return $this->cachedUrl;
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	private function filter($text): string
	{
		$text = StringUtils::trim($text);

		$text = str_replace('\\', '/', $text);

		preg_match('/(\bhttp\b|\bhttps\b:\/\/[a-zA-Z0-9\.]+){1}([\/a-zA-Z0-9\.\_\-]+){0,1}/u', $text, $matchedRoute);

		if (!empty($matchedRoute)) {
			$text = $matchedRoute[1];

			if (isset($matchedRoute[2])) {
				$pathList = explode('/', $matchedRoute[2]);
				foreach ($pathList as $path) {
					$this->addPath($path);
				}
			}
		}

		if (substr($text, -1) === '/') {
			$text = substr($text, 0, strlen($text) - 1);
		}

		if (substr($text, 0) === '/') {
			$text = substr($text, 1);
		}

		return $text;
	}

	/**
	 * @param string $path
	 *
	 * @return Url
	 */
	public function addPath($path): Url
	{
		$path = $this->filter($path);

		if (!StringUtils::isEmpty($path) && !in_array($path,$this->pathStack)) {
			$this->pathStack[] = $path;

			$this->changed = true;
		}

		return $this;
	}

	/**
	 * @param string $parameterName
	 * @param string $parameterValue
	 *
	 * @return Url
	 */
	public function addQueryParameter($parameterName, $parameterValue): Url
	{
		$this->queryOptions[StringUtils::toLower($this->filter($parameterName))] = $this->filter($parameterValue);

		$this->changed = true;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isChanged(): bool
	{
		return $this->changed;
	}

	/**
	 * @param $format
	 *
	 * @return Url
	 */
	public function changeFormat($format): Url
	{
		$this->queryOptions['format'] = $this->filter($format);

		return $this;
	}

	/**
	 * @return string
	 */
	private function buildPath(): string
	{
		$pathWithRequiredSlashList = [
			Api::ENDPOINT__BEER,
		];

		$lastEndpoint = end($this->pathStack);

		return implode('/', $this->pathStack) . (in_array($lastEndpoint, $pathWithRequiredSlashList) ? '/' : '');
	}

	/**
	 * @return string
	 */
	public function getFormat(): string
	{
		if (null === $this->queryOptions['format']) {
			return 'json';
		}

		return $this->queryOptions['format'];
	}
}