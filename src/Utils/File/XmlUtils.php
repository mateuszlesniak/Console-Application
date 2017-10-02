<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 17.09.2017
 * Time: 00:27
 */

namespace Utils\File;


class XmlUtils extends AbstractUtils {

	/**
	 * @param string $data
	 *
	 * @return bool
	 */
	public static function save($data)
	{
		return static::saveToFile($data, '_xml.xml');
	}
}