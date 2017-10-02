<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 19:36
 */

namespace Beer\Partial;


class Style extends AbstractEntity {

	protected $id;
	protected $categoryId;
	protected $category;
	protected $name;
	protected $shortName;
	protected $description;
	protected $ibuMin;
	protected $ibuMax;
	protected $abvMin;
	protected $abvMax;
	protected $srmMin;
	protected $srmMax;
	protected $ogMin;
	protected $ogMax;
	protected $fgMIin;
	protected $fgMax;
	protected $createDate;
	protected $updateDate;

	protected function setRequiredFieldList()
	{
		$this->requiredFieldList = [
			'id',
			'categoryId',
			'category',
			'name',
			'shortName',
			'description',
			'createDate',
			'updateDate',
		];
	}

	protected function setOptionalFieldList()
	{
		$this->optionalFieldList = [
			'ibuMin',
			'ibuMax',
			'abvMin',
			'abvMax',
			'srmMin',
			'srmMax',
			'ogMin',
			'ogMax',
			'fgMIin',
			'fgMax',
		];
	}

	protected function setRequiredFieldToShowList()
	{
		$this->requiredFieldToShowList = [

		];
	}

	protected function setOptionalFieldToShowList()
	{
		$this->optionalFieldToShowList = [

		];
	}

	/**
	 * @param \stdClass $category
	 */
	protected function setCategory($category)
	{
		$this->category = (new Category())->retrieve($category);
	}
}