<?php
/**
 * Created by PhpStorm.
 * User: leesiuu
 * Date: 16.09.2017
 * Time: 21:25
 */

class Picture {

	protected $url;

	private $base64Image = null;

	/**
	 * Picture constructor.
	 *
	 * @param $url
	 *
	 * @author Mateusz Lesniak <mateuszlesniak.work@gmail.com>
	 */
	public function __construct($url)
	{
		$this->url = (new Url())->setUrlSite($url);
	}

	/**
	 * @TODO
	 *
	 * @return string
	 */
	public function getBase64EncodedImage()
	{
		if (null === $this->base64Image) {
			$this->base64Image = base64_encode('asas');
		}

		return $this->base64Image;
	}

	/**
	 * @return string
	 */
	public function getPictureUrlString(): string
	{
		return $this->getUrl()->buildUrl();
	}

	/**
	 * @return Url
	 */
	private function getUrl(): Url
	{
		return $this->url;
	}
}