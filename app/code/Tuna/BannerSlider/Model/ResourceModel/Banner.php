<?php

namespace Tuna\BannerSlider\Model\ResourceModel;


class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('tuna_bannerslider_banner', 'banner_id'); //init(tablename, id)
    }
}