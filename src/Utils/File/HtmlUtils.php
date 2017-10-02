<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 17.09.2017
 * Time: 00:26
 */

namespace Utils\File;


class HtmlUtils extends AbstractUtils {

	/**
	 * For now - unused
	 *
	 * @param string $data
	 *
	 * @return bool
	 */
	public static function save($data)
	{
		return static::saveToFile($data, '_html.html');
	}
}