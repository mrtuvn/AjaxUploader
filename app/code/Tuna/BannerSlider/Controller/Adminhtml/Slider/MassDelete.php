<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


class MassDelete extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{

    public function execute()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        if (!is_array($sliderIds) || empty($sliderIds)) {
            $this->messageManager->addError(__('Please select slider(s).'));
        } else {
            $sliderCollection = $this->_sliderCollectionFactory->create()
                ->addFieldToFilter('slider_id', ['in' => $sliderIds]);
            try {
                foreach ($sliderCollection as $slider) {
                    $slider->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($sliderIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->_resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
