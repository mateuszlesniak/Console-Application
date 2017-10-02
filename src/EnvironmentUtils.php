<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 15.09.2017
 * Time: 21:10
 */

use Utils\StringUtils;

class EnvironmentUtils {

	protected static $environment = 'development';

	/**
	 * @return string
	 */
	public static function getEnvironment(): string
	{
		return static::$environment;
	}

	/**
	 * @return bool
	 */
	public static function isDeveloperEnvironment(): bool
	{
		return StringUtils::toLower(static::getEnvironment()) === 'develop' || StringUtils::toLower(static::getEnvironment()) === 'development' || StringUtils::toLower(static::getEnvironment()) === 'dev';
	}

	/**
	 * @param mixed  $variable
	 * @param string $etiquette
	 * @param bool   $checkEnvironment
	 */
	public static function printToConsole($variable, $etiquette = '', $checkEnvironment = true): void
	{
		switch (gettype($variable)) {
			case 'array':
				$printArray = [];

				foreach ($variable as $item => $value) {
					if (is_object($value)) {
						$value = 'object ' . get_class($value);
					}
					$printArray[] = "{$item}: {$value}";
				}

				$printString = implode(' | ', $printArray);
				break;
			case 'object':
				$printString = 'object - ' . get_class($variable);
				break;
			case 'resource':
				$printString = 'variable is an resource';
				break;
			case 'unknown type':
				$printString = 'ERROR';
				break;
			case 'NULL':
				$printString = 'null';
				break;
			case 'boolean':
			case 'integer':
			case 'float':
			case 'string':
			case 'double':
			default:
				$printString = gettype($variable) . ': ' . $variable;
		}

		if (static::isDeveloperEnvironment() || !$checkEnvironment) {
			echo (StringUtils::isEmpty($etiquette) ? 'debug::variable(s)' : $etiquette) . ' - ' . $printString . EnvironmentUtils::getEndOfLineSymbol();
		}
	}

	/**
	 * @param string $message
	 */
	public static function printMessage($message)
	{
		echo $message . EnvironmentUtils::getEndOfLineSymbol();
	}

	public static function setProductionEnvironment(): void
	{
		static::$environment = 'production';
	}

	public static function setDevelopmentEnvironment(): void
	{
		static::$environment = 'development';
	}

	/**
	 * @return bool
	 */
	public static function isCommandLineInterface(): bool
	{
		return (php_sapi_name() === 'cli');
	}

	/**
	 * @return string
	 */
	public static function getEndOfLineSymbol(): string
	{
		if (true === static::isCommandLineInterface()) {
			return PHP_EOL;
		} else {
			return PHP_EOL_WEB;
		}
	}
}