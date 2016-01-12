<?php

namespace Tuna\BannerSlider\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;


class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{

	const NAME = 'thumbnail';

	const ALT_FIELD = 'banner_name';

	private $_getModel;

	private $editUrl;

	private $_objectManager = null;

	protected $uiComponentFactory;


	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		\Tuna\BannerSlider\Model\Image $imageHelper,
		\Magento\Framework\UrlInterface $urlBuilder,
		\Magento\Framework\ObjectManagerInterface $objectManager,

		array $components = [],
		array $data = []
	) {
		$this->uiComponentFactory = $uiComponentFactory;
		parent::__construct($context, $uiComponentFactory, $components, $data); //important part
		$this->imageHelper    = $imageHelper;
		$this->urlBuilder     = $urlBuilder;
		$this->_objectManager = $objectManager;

	}

	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			$fieldName = $this->getData('name'); //@return: image

			foreach ($dataSource['data']['items'] as & $item) {
				$filename = 'myimage.png';
				$item[$fieldName . '_src'] = $this->imageHelper->getBaseUrl().$filename;
				$item[$fieldName . '_alt'] = $this->getAlt($item) ?: $filename;
				$item[$fieldName . '_orig_src'] = $this->imageHelper->getBaseUrl().$filename;
			}
		}


		return $dataSource;
	}

	protected function getAlt($row)
	{
		$altField = $this->getData('config/altField') ?: self::ALT_FIELD;
		return isset($row[$altField]) ? $row[$altField] : null;
	}
}