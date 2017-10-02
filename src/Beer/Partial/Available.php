<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 20:03
 */

namespace Beer\Partial;


class Available extends AbstractEntity {

	protected $id;
	protected $name;
	protected $description;

	protected function setRequiredFieldList()
	{
		$this->requiredFieldList = [
			'id',
			'name',
			'description',
		];
	}

	protected function setOptionalFieldList()
	{
		$this->optionalFieldList = [];
	}

	protected function setRequiredFieldToShowList()
	{
		$this->requiredFieldToShowList = [
			'name',
			'description',
		];
	}

	protected function setOptionalFieldToShowList()
	{
		$this->optionalFieldToShowList = [

		];
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return intval($this->id);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}


}