<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;

class Delete extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{
    public function execute()
    {
        $sliderId = $this->getRequest()->getParam('slider_id');
        try {
            $slider = $this->_sliderFactory->create()->setId($sliderId);
            $slider->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->_resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
