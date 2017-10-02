<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 19:37
 */

namespace Beer\Partial;


class Label extends AbstractEntity {

	protected $icon;
	protected $medium;
	protected $large;

	protected function setRequiredFieldList()
	{
		$this->requiredFieldList = [
			'icon',
			'medium',
			'large',
		];
	}

	protected function setOptionalFieldList()
	{
		$this->optionalFieldList = [];
	}

	protected function setRequiredFieldToShowList()
	{
		$this->requiredFieldToShowList = [
			'medium',
		];
	}

	protected function setOptionalFieldToShowList()
	{
		$this->optionalFieldToShowList = [];
	}

	/**
	 * @param mixed $icon
	 */
	protected function setIcon($icon)
	{
		$this->icon = new \Picture($icon);
	}

	/**
	 * @param mixed $medium
	 */
	protected function setMedium($medium)
	{
		$this->medium = new \Picture($medium);
	}

	/**
	 * @param mixed $large
	 */
	protected function setLarge($large)
	{
		$this->large = new \Picture($large);
	}

	/**
	 * @return \Picture
	 */
	public function getIconImage(): \Picture
	{
		return $this->getIcon();
	}

	/**
	 * @return \Picture
	 */
	public function getMediumImage(): \Picture
	{
		return $this->getMedium();
	}

	/**
	 * @return \Picture
	 */
	public function getLargeImage(): \Picture
	{
		return $this->getLarge();
	}

	/**
	 * @return \Picture
	 */
	private function getIcon(): \Picture
	{
		return $this->icon;
	}

	/**
	 * @return \Picture
	 */
	private function getMedium(): \Picture
	{
		return $this->medium;
	}

	/**
	 * @return \Picture
	 */
	private function getLarge(): \Picture
	{
		return $this->large;
	}

}