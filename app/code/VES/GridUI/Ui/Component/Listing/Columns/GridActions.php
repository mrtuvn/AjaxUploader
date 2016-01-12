<?php

namespace VES\GridUI\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;


class GridActions extends Column
{
	const GRID_URL_PATH_EDIT   = 'gridui/grid/edit';
	const GRID_URL_PATH_DELETE = 'gridui/grid/delete';

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
				if (isset($item['grid_id'])) {
					$item['name']['edit'] = [
						'herf' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_EDIT, ['grid_id' => $item['grid_id']]),
						'label' => __('Edit')
					];
					$item[$name]['delete'] = [
						'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_DELETE, ['grid_id' => $item['grid_id']]),
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
