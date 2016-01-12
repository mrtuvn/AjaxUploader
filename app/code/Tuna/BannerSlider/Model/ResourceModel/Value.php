<?php

namespace Tuna\BannerSlider\Model\ResourceModel;


class Value extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('tuna_bannerslider_value', 'value_id'); //init(tablename, id)
    }
}