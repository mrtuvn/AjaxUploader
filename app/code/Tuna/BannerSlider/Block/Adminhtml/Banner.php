<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Banner block
 *
 * @author     mrtuvn
 */
namespace Tuna\BannerSlider\Block\Adminhtml;

class Banner extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct() {

        $this->_controller = "adminhtml_banner";
        $this->_blockGroup = 'Tuna_BannerSlider';
        $this->_headerText = __('Banners');
        $this->_addButtonLabel = __('Add New Banner');

        parent::_construct();
    }

}
