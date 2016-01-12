<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


class Grid extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{
    
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();

        return $resultLayout;
    }
}
