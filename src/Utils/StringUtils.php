<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 15.09.2017
 * Time: 21:12
 */

namespace Utils;

class StringUtils {

	public static $encoding = 'UTF-8';

	/**
	 * @param string $value
	 * @param bool   $trim
	 *
	 * @return bool
	 */
	public static function isEmpty($value, $trim = true): bool
	{
		return (is_string($value) && (($trim ? self::trim($value) : $value) == '') || (null === $value) || (is_bool($value) && !$value) || (is_array($value) && empty($value)));
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public static function trim($string): string
	{
		return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $string);
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public static function toLower($string): string
	{
		return mb_strtolower($string, self::$encoding);
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public static function toUpper($string)
	{
		return mb_strtoupper($string, self::$encoding);
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public static function upperCaseFirstLetter($string): string
	{
		$fc = mb_strtoupper(mb_substr($string, 0, 1, self::$encoding), self::$encoding);

		return $fc . mb_substr($string, 1, mb_strlen($string, self::$encoding), self::$encoding);
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public static function separateWords($text): string
	{
		return preg_replace('/(?<!\ )[A-Z]/', ' $0', $text);
	}

	/**
	 * @param string $lowerCaseAndUnderscoredWord
	 * @param bool   $saveSpaces
	 *
	 * @return string
	 */
	public static function camelizeUpper($lowerCaseAndUnderscoredWord, $saveSpaces = true): string
	{
		$result = mb_convert_case(str_replace('_', ' ', str_replace('-', ' ', $lowerCaseAndUnderscoredWord)), MB_CASE_TITLE, self::$encoding);

		if (false === $saveSpaces) {
			$result = str_replace(' ', '', $result);
		}

		return $result;
	}
}