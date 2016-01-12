<?php

namespace VES\ImageGallery\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Image extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{

	const STATUS_ENABLED    = 1;
	const STATUS_DISABLED   = 0;

	const CACHE_TAG = 'ves_image';


	protected function _construct()
	{
		$this->_init('VES\ImageGallery\Model\ResourceModel\Image');
	}

	public function getAvailableStatuses()
	{
		return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}
}