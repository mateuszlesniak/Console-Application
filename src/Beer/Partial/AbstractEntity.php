<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 20:03
 */

namespace Beer\Partial;


use Color\CustomColor;
use Exception\FatalException;
use Utils\DateTimeUtils;
use Utils\StringUtils;

abstract class AbstractEntity implements EntityInterface {

	protected $requiredFieldList = [];
	protected $optionalFieldList = [];

	protected $requiredFieldToShowList = [];
	protected $optionalFieldToShowList = [];

	private $isValidObject = false;

	private $cacheConsoleText = null;

	/**
	 * AbstractEntity constructor.
	 *
	 * @author Mateusz Lesniak <mateuszlesniak.work@gmail.com>
	 */
	public function __construct()
	{
		$this->setRequiredFieldList();
		$this->setOptionalFieldList();

		$this->setRequiredFieldToShowList();
		$this->setOptionalFieldToShowList();
	}


	/**
	 * @param \stdClass $class
	 *
	 * @return $this
	 * @throws FatalException
	 */
	public function retrieve(\stdClass $class)
	{
		foreach ($this->getRequiredFieldList() as $requiredField) {
			if (!property_exists($class, $requiredField)) {
				//				throw new FatalException(sprintf(
				//						"Cannot retrieve %s field in class %s",
				//						$requiredField,
				//						self::class)
				//				);

				// better is set empty field instead not showing all data
				$this->$requiredField = 'cannot retrieve data';

				continue;
			}

			$methodName = "set" . StringUtils::upperCaseFirstLetter($requiredField);
			if (method_exists($this, $methodName)) {
				$this->$methodName($class->$requiredField);
			} else {
				$this->$requiredField = $class->$requiredField;
			}
		}

		$this->isValidObject = true;

		foreach ($this->getOptionalFieldList() as $optionalField) {
			if (!property_exists($class, $optionalField)) {
				continue;
			}

			$methodName = "set" . StringUtils::upperCaseFirstLetter($optionalField);
			if (method_exists($this, $methodName)) {
				$this->$methodName($class->$optionalField);
			} else {
				$this->$optionalField = $class->$optionalField;
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	protected function getRequiredFieldList(): array
	{
		return $this->requiredFieldList;
	}

	/**
	 * @return array
	 */
	protected function getOptionalFieldList(): array
	{
		return $this->optionalFieldList;
	}

	abstract protected function setRequiredFieldList();

	abstract protected function setOptionalFieldList();

	abstract protected function setRequiredFieldToShowList();

	abstract protected function setOptionalFieldToShowList();

	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		return $this->isValidObject;
	}

	public function showToScreen()
	{
		foreach ($this->requiredFieldToShowList as $identifier => $requiredField) {
			if (!$this->isValid()) {
				echo "{$this->getCacheConsoleText('These field have not got all required data to show it to the screen')->red()->bg_black()}" . \EnvironmentUtils::getEndOfLineSymbol();

				break;
			}

			if (!is_int($identifier)) {
				$fieldName = $identifier;
			} else {
				$fieldName = $requiredField;
			}

			$getMethodName = 'get' . StringUtils::upperCaseFirstLetter($fieldName);

			if (method_exists($this, $getMethodName)) {
				$field = $this->$getMethodName();

				if (is_object($this->$fieldName)) {
					$value = $this->getValueFromObject($this->$fieldName);
				} else {
					$value = $field;
				}
			} else {
				$value = $this->$requiredField;
			}

			if (!StringUtils::isEmpty($value)) {
				echo "{$this->getCacheConsoleText(StringUtils::camelizeUpper(StringUtils::separateWords($requiredField)))->white()->bg_black()}: {$this->getCacheConsoleText($value)->black()->bg_white()}" . \EnvironmentUtils::getEndOfLineSymbol();
			}
		}

		foreach ($this->optionalFieldToShowList as $identifier => $requiredField) {
			if (!$this->isValid()) {
				echo "{$this->getCacheConsoleText('These field have not got all required data to show it to the screen')->red()->bg_black()}" . \EnvironmentUtils::getEndOfLineSymbol();

				break;
			}

			if (!is_int($identifier)) {
				$fieldName = $identifier;
			} else {
				$fieldName = $requiredField;
			}

			$getMethodName = 'get' . StringUtils::upperCaseFirstLetter($fieldName);

			if (method_exists($this, $getMethodName)) {
				$field = $this->$getMethodName();

				if (is_object($this->$fieldName)) {
					$value = $this->getValueFromObject($this->$fieldName);
				} else {
					$value = $field;
				}
			} else {
				$value = $this->$requiredField;
			}

			if (!StringUtils::isEmpty($value)) {
				echo "{$this->getCacheConsoleText(StringUtils::camelizeUpper(StringUtils::separateWords($requiredField)))->white()->bg_black()}: {$this->getCacheConsoleText($value)->black()->bg_white()}" . \EnvironmentUtils::getEndOfLineSymbol();
			}
		}
	}

	/**
	 * @param AbstractEntity $object
	 *
	 * @return string
	 */
	protected function getValueFromObject($object): string
	{
		$value = "";
		switch (get_class($object)) {
			case \DateTime::class:
				/** @var \DateTime $object */

				$value = $object->format(DateTimeUtils::FORMAT__YMD_HIS);
				break;
			case Available::class:
				/** @var Available $object */

				if (!$object->isValid()) {
					break;
				}

				$value = "{$object->getName()} - {$object->getDescription()}";
				break;
			case Glass::class:
				/** @var Glass $object */

				if (!$object->isValid()) {
					break;
				}

				$value = $object->getName();
				break;
			case Label::class:
				/** @var Label $object */

				if (!$object->isValid()) {
					break;
				}

				$value = '<img src="' . $object->getMediumImage()->getPictureUrlString() . '">';
				break;
			default:
				\EnvironmentUtils::printToConsole(get_class($object));
				break;
		}

		return $value;
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
}