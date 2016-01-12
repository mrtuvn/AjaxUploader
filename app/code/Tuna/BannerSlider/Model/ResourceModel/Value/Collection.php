<?php


namespace Tuna\BannerSlider\Model\ResourceModel\Value;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Tuna\BannerSlider\Model\Value', 'Tuna\BannerSlider\Model\ResourceModel\Value');
    }
}