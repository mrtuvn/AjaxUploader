<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tuna\BannerSlider\Block\Adminhtml\Banner\Edit;

use Magento\Backend\Block\Widget\Tabs as WigetTabs;


class Tabs extends WigetTabs
{


    protected function _construct()
    {
        parent::_construct();
        $this->setId('slider_edit_tabs'); //name in layout tab declare xml
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Banner Information'));
    }


}
