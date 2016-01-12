<?php

namespace Tuna\BannerSlider\Model;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
class Image
{

	protected $subDir = 'tuna/bannerslider/images'; //actual path is pub/media/webkul/image


	protected $urlBuilder;

	protected $fileSystem;

	public function __construct(
		UrlInterface $urlBuilder,
		Filesystem $fileSystem
	)
	{
		$this->urlBuilder = $urlBuilder;
		$this->fileSystem = $fileSystem;
	}

	public function getBaseUrl()
	{
		return $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
	}

	public function getFilename()
	{

	}
}
