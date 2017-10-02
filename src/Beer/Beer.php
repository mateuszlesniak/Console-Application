<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 00:28
 */

namespace Beer;

use Beer\Partial\AbstractEntity;
use Beer\Partial\Available;
use Beer\Partial\Glass;
use Beer\Partial\Label;
use Beer\Partial\Style;
use Utils\DateTimeUtils;
use Utils\StringUtils;

class Beer extends AbstractEntity {

	protected $id;
	protected $name;
	protected $abv;
	protected $glass;
	protected $style;
	protected $isOrganic;
	protected $labels;
	protected $status;
	protected $createDate;
	protected $ibu;

	protected $updateDate;
	protected $nameDisplay;
	protected $description;
	protected $statusDisplay;
	protected $available;

	protected function setRequiredFieldList()
	{
		$this->requiredFieldList = [
			'id',
			'abv',
			'name',
			'status',
			'ibu',
		];
	}

	protected function setOptionalFieldList()
	{
		$this->optionalFieldList = [
			'description',
			'updateDate',
			'nameDisplay',
			'statusDisplay',
			'available',
			'glass',
			'isOrganic',
			'labels',
			'createDate',
			'style',
		];
	}

	protected function setRequiredFieldToShowList()
	{
		$this->requiredFieldToShowList = [
			'id',
			'nameDisplay' => 'name',
			'ibu',
			'abv',
			'isOrganic',
		];
	}

	protected function setOptionalFieldToShowList()
	{
		$this->optionalFieldToShowList = [
			'description',
			'available',
			'createDate',
			'statusDisplay',
			'glass',
			'labels' => 'image',
		];
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return float
	 */
	public function getAbv()
	{
		return floatval($this->abv);
	}

	/**
	 * @return bool
	 */
	public function isOrganic()
	{
		return $this->isOrganic;
	}

	/**
	 * @return string
	 */
	public function getIsOrganic()
	{
		return true === $this->isOrganic ? "Yes" : "No";
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}

	/**
	 * @return Glass
	 */
	public function getGlass()
	{
		return $this->glass;
	}

	/**
	 * @return Style
	 */
	public function getStyle()
	{
		return $this->style;
	}

	/**
	 * @return Label
	 */
	public function getLabels()
	{
		return $this->labels;
	}

	/**
	 * @return int
	 */
	public function getIbu()
	{
		return intval($this->ibu);
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdateDate()
	{
		return $this->updateDate;
	}

	/**
	 * @return string
	 */
	public function getNameDisplay()
	{
		return $this->nameDisplay;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getStatusDisplay()
	{
		return $this->statusDisplay;
	}

	/**
	 * @return Available
	 */
	public function getAvailable()
	{
		return $this->available;
	}

	/**
	 * @param string $isOrganic
	 */
	protected function setIsOrganic($isOrganic)
	{
		if ('n' === StringUtils::toLower($isOrganic)) {
			$this->isOrganic = false;
		}

		$this->isOrganic = true;
	}

	/**
	 * @param \stdClass $labels
	 */
	protected function setLabels($labels)
	{
		$this->labels = (new Label())->retrieve($labels);
	}

	/**
	 * @param \stdClass $available
	 */
	protected function setAvailable($available)
	{
		$this->available = (new Available())->retrieve($available);
	}

	/**
	 * @param \stdClass $glass
	 */
	protected function setGlass($glass)
	{
		$this->glass = (new Glass())->retrieve($glass);
	}

	/**
	 * @param \stdClass $style
	 */
	protected function setStyle($style)
	{
		$this->style = (new Style())->retrieve($style);
	}

	protected function setIbu($ibu)
	{
		$this->ibu = (int)$ibu;
	}

	/**
	 * @param string $createDate
	 */
	protected function setCreateDate($createDate)
	{
		$this->createDate = DateTimeUtils::createFromFormat($createDate);
	}

	/**
	 * @param string $updateDate
	 */
	protected function setUpdateDate($updateDate)
	{
		$this->updateDate = DateTimeUtils::createFromFormat($updateDate);
	}

	protected function setAbv($abv)
	{
		$this->abv = floatval($abv);
	}
}