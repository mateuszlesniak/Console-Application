<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 19:35
 */

namespace Beer\Partial;

use Utils\DateTimeUtils;

class Glass extends AbstractEntity {

	protected $id;
	protected $name;
	protected $createDate;

	protected function setRequiredFieldList()
	{
		$this->requiredFieldList = [
			'id',
			'name',
			'createDate',
		];
	}

	protected function setOptionalFieldList()
	{
		$this->optionalFieldList = [];
	}

	protected function setCreateDate($createDate)
	{
		$this->createDate = DateTimeUtils::createFromFormat($createDate);
	}

	protected function setRequiredFieldToShowList()
	{
		$this->requiredFieldToShowList = [
			'name',
		];
	}

	protected function setOptionalFieldToShowList()
	{
		$this->optionalFieldToShowList = [];
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
	 * @return \DateTime
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}


}