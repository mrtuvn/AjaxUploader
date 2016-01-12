<?php

namespace VES\ImageGallery\Mode\ResourceModel\Image;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('VES\ImageGallery\Model\Image', 'VES\ImageGallery\Model\ResourceModel\Image');
    }
}