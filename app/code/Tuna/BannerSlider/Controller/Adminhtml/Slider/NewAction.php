<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


class NewAction extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
