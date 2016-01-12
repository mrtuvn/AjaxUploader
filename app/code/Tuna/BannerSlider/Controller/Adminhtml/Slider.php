<?php

namespace Tuna\BannerSlider\Controller\Adminhtml;

use Tuna\BannerSlider\Controller\Adminhtml\AbstractAction;

abstract class Slider extends AbstractAction
{

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tuna_BannerSlider::bannerslider_sliders');
    }
}