<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;

class Preview extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{

    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
