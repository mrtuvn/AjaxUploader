<?php

namespace Tuna\BannerSlider\Model\ResourceModel;


class Slider extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('tuna_bannerslider_slider', 'slider_id'); //init(tablename, id)
    }
}