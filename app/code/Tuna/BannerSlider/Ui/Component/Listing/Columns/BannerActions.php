<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tuna\BannerSlider\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class ProductActions
 */
class BannerActions extends Column
{
	const BANNER_URL_PATH_EDIT = 'bannerslideradmin/banner/edit';
	const BANNER_URL_PATH_DELETE = 'bannerslideradmin/banner/delete';

	protected $urlBuilder;


	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		UrlInterface $urlBuilder,
		array $components = [],
		array $data = []
	) {
		$this->urlBuilder = $urlBuilder;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}


	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			//Edit or delete
			foreach ($dataSource['data']['items'] as $item)
			{
				$name = $this->getData('name');
				if (isset($item['banner_id'])) {
					$item['name']['edit'] = [
					'herf' => $this->urlBuilder->getUrl(self::BANNER_URL_PATH_EDIT, ['banner_id' => $item['banner_id']]),
					'label' => __('Edit')
					];
					$item[$name]['delete'] = [
						'href' => $this->urlBuilder->getUrl(self::BANNER_URL_PATH_DELETE, ['banner_id' => $item['banner_id']]),
						'label' => __('Delete'),
						'confirm' => [
							'title' => __('Delete "${ $.$data.title }"'),
							'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
						]
					];
				}
			}
		}

		return $dataSource;
	}
}
