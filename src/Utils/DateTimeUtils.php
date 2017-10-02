<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 19:25
 */

namespace Utils;

class DateTimeUtils {

	const FORMAT__YMD_HIS = 'Y-m-d H:i:s';
	const FORMAT__YMD = 'Y-m-d';


	/**
	 * @param string $dateTimeString
	 *
	 * @return \DateTime|false
	 */
	public static function createFromFormat($dateTimeString)
	{
		if ($dateTimeString instanceof \DateTime) {
			return $dateTimeString;
		}

		$formatList = [
			'y/m/d',
			'Y/m/d',
			self::FORMAT__YMD,
			self::FORMAT__YMD_HIS,
			'd-m-Y',
			'd-m-Y H:i:s',
			'Y-m-d H:i',
		];

		foreach ($formatList as $format) {
			$dateTime = \DateTime::createFromFormat($format, $dateTimeString);

			if ($dateTime instanceof \DateTime) {
				return $dateTime;
			}
		}

		return false;
	}
}