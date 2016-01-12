<?php

namespace Tuna\BannerSlider\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\AbstractComponent;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Tuna\BannerSlider\Model\Image;

class ImageBanner extends \Magento\Ui\Component\Listing\Columns\Column
{
    const ALT_FIELD = 'image_alt';
	const NAME   	= 'image';
	protected $_urlBuilder;
	protected $_imageModel;
	protected $_storeManager;

	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		UrlInterface $urlBuilder,
		StoreManagerInterface  $storeManager,
		Image $imageModel,
		array $components = [],
		array $data = []
	){
		parent::__construct($context, $uiComponentFactory, $components, $data);
		$this->_urlBuilder      = $urlBuilder;
		$this->_storeManager    = $storeManager;
		$this->_imageModel      = $imageModel;


	}

	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			$fieldName = $this->getData('name');
			foreach ($dataSource['data']['items'] as & $item) {
				$url = '';
				if($item[$fieldName] != '' && $fieldName) {
					$url = $this->_storeManager->getStore()->getBaseUrl(
							\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
						).$item['image'];
				}
				$item[$fieldName . '_src'] = $url;
				$item[$fieldName . '_alt'] = $this->getAlt($item)? :'';
				$item[$fieldName . '_link'] = $this->_urlBuilder->getUrl(
					'bannerslideradmin/banner/edit',
					['banner_id' => $item['banner_id']]
				);
				$item[$fieldName . '_orig_src'] = $url;

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