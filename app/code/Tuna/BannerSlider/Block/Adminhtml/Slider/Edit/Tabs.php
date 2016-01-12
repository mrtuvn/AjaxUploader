<?php


namespace Tuna\BannerSlider\Block\Adminhtml\Slider\Edit;


class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('slider_edit_tabs'); //init tab id for js tab. Name see in layout
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Slider Information'));
    }
}
