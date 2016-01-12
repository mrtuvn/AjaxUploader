<?php

namespace Tuna\BannerSlider\Model\ResourceModel\Slider;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Tuna\BannerSlider\Model\Slider', 'Tuna\BannerSlider\Model\ResourceModel\Slider');
    }
}