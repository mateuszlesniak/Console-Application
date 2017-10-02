<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 00:26
 */

use Beer\Beer;
use Utils\StringUtils;
use Exception\FatalException;

class Response {

	private $response;

	private $message = 'undefined';
	private $data = [];
	private $preparedData = null;
	private $status = 'undefined';

	private $format = null;

	public function __construct($data, $format)
	{
		$this->response = $data;
		$this->format = $format;

		switch ($format) {
			case Api::FORMAT__JSON:
				$object = json_decode($data);
				break;
			case Api::FORMAT__XML:
				$object = json_decode(json_encode(simplexml_load_string($data)));
				break;
			case Api::FORMAT__PHP:
				$object = static::convertArrayToObject(unserialize($data));
				break;
			default:
				throw new FatalException('Fatal exception! Please use proper format type!');
		}

		if (property_exists($object, 'message')) {
			$this->message = $object->message;
		}
		if (property_exists($object, 'data')) {
			$this->data = $object->data;
		}
		if (property_exists($object, 'status')) {
			$this->status = StringUtils::toLower($object->status);
		}
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @return stdClass
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		return 'success' === $this->getStatus();
	}

	/**
	 * @return Beer[]
	 */
	public function getPreparedData()
	{
		return $this->preparedData;
	}

	/**
	 * @param Beer[] $preparedData
	 */
	public function setPreparedData($preparedData)
	{
		$this->preparedData = $preparedData;
	}

	/**
	 * @param array $array
	 *
	 * @return stdClass
	 */
	private static function convertArrayToObject($array): stdClass
	{
		$returnObject = new stdClass();

		foreach ($array as $item => $value) {
			if (is_array($value)) {
				$returnObject->$item = static::convertArrayToObject($value);
			} else {
				$returnObject->$item = $value;
			}
		}

		return $returnObject;
	}

	/**
	 * @param string $from
	 * @param string $to
	 * @param string $data
	 *
	 * @throws FatalException
	 * @return string
	 */
	public static function convert($from, $to, $data)
	{
		if (
			(!in_array($from, [
				Api::FORMAT__PHP,
				Api::FORMAT__JSON,
				Api::FORMAT__XML,
			])) ||
			(!in_array($from, [
				Api::FORMAT__PHP,
				Api::FORMAT__JSON,
				Api::FORMAT__XML,
			]))
		) {
			throw new FatalException('please specify right format to conversion');
		}

		switch ($from) {
			case Api::FORMAT__JSON:
				switch ($to) {
					case Api::FORMAT__XML:
						$array = json_decode($data, true);

						$xml = new SimpleXMLElement('<root/>');
						$flippedArray = static::flipArray($array);
						array_walk_recursive($flippedArray, [$xml, 'addChild']);

						return $xml->asXML();
					case Api::FORMAT__PHP:
						return serialize(json_decode($data, true));
					default:
						return '';
				}
			case Api::FORMAT__XML:
				switch ($to) {
					case Api::FORMAT__JSON:
						return json_encode(simplexml_load_string($data));
					case Api::FORMAT__PHP:
						return serialize(json_decode(json_encode(simplexml_load_string($data)), true));
					default:
						return '';
				}
			case Api::FORMAT__PHP:
				switch ($to) {
					case Api::FORMAT__JSON:
						return json_encode(unserialize($data));
					case Api::FORMAT__XML:
						$array = unserialize($data);

						$xml = new SimpleXMLElement('<root/>');
						$flippedArray = static::flipArray($array);
						array_walk_recursive($flippedArray, [$xml, 'addChild']);

						return $xml->asXML();
					default:
						return '';
				}
			default:
				return '';
		}
	}

	/**
	 * @return string
	 */
	public function getRawResponse()
	{
		return $this->response;
	}

	private static function flipArray($arr)
	{
		$flippedArray = [];

		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				$flippedArray[$key] = static::flipArray($value);
			} else {
				$flippedArray[$value] = $key;
			}
		}

		return $flippedArray;
	}
}