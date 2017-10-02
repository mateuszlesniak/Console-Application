<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 17.09.2017
 * Time: 00:02
 */

namespace Beer\Partial;


use Utils\DateTimeUtils;

class Category extends AbstractEntity {

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
	 * @param string $createDate
	 */
	protected function setCreateDate($createDate)
	{
		$this->createDate = DateTimeUtils::createFromFormat($createDate);
	}

	/**
	 * @return \DateTime
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}
}