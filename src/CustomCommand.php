<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 21:04
 */

use Commando\Command;

class CustomCommand extends Command {

	/**
	 * @param array|null $tokens
	 */
	public function __construct($tokens = null)
	{
		if (empty($tokens)) {
			if (defined('STDIN')) {
				$tokens = $_SERVER['argv'];
			} else {
				$tokens = $_GET;
			}
		}

		$this->setTokens($tokens);
	}
}