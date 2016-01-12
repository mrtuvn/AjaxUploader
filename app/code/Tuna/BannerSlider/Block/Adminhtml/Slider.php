<?php

/**
 * Banner block
 *
 * @author     mrtuvn
 */
namespace Tuna\BannerSlider\Block\Adminhtml;

class Slider extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct() {

        $this->_controller      = "adminhtml_slider";
        $this->_blockGroup      = 'Tuna_BannerSlider';
        $this->_headerText      = __('Sliders');
        $this->_addButtonLabel  = __('Add New Slider');

        parent::_construct();
    }

}
