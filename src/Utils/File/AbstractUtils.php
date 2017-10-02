<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 17.09.2017
 * Time: 00:26
 */

namespace Utils\File;


use Exception\FileException;
use Utils\DateTimeUtils;

abstract class AbstractUtils {

	/**
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return boolean
	 *
	 */
	protected static function copy($sourcePath, $destinationPath): bool
	{
		return copy($sourcePath, $destinationPath);
	}

	/**
	 * @param string $sourcePath
	 *
	 * @return boolean
	 *
	 */
	protected static function delete($sourcePath): bool
	{
		return unlink($sourcePath);
	}

	/**
	 * @param string $sourcePath
	 * @param string $destinationPath
	 *
	 * @return boolean
	 * @throws \Exception
	 *
	 */
	protected static function move($sourcePath, $destinationPath): bool
	{

		if (false === self::copy($sourcePath, $destinationPath)) {
			throw new \Exception("Cannot move file from path {$sourcePath}");
		}

		if (false === self::delete($sourcePath)) {
			throw new \Exception("Cannot delete file from source path {$sourcePath}");
		}

		return true;
	}

	abstract public static function save($data);

	/**
	 * @param string $data
	 * @param string $filename
	 *
	 * @throws FileException
	 * @return bool
	 */
	protected static function saveToFile($data, $filename)
	{
		$fileName = (new \DateTime())->format('Y_m_d__H_i_s___') . $filename;

		\EnvironmentUtils::printToConsole(\Index::getRootDirectory() . DIRECTORY_SEPARATOR . $fileName, 'file save path');

		if (false === file_put_contents(\Index::getRootDirectory() . DIRECTORY_SEPARATOR . $fileName, $data)) {
			throw new FileException('Cannot save file in main folder. Please try again');
		}

		\EnvironmentUtils::printMessage('Success! File stored in main folder.');
		\EnvironmentUtils::printMessage(' Your beer(s) is waiting for you!');

		return true;
	}
}