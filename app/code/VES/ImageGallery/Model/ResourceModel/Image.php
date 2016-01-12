<?php

namespace VES\ImageGallery\Model\ResourceModel;


class Image extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	protected function _construct()
	{
		$this->_init('ves_imagegallery_image', 'image_id'); //init(tablename, id)
	}
}