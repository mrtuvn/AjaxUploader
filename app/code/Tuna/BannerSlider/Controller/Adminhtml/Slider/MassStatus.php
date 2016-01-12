<?php



namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


class MassStatus extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{

    public function execute()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($sliderIds) || empty($sliderIds)) {
            $this->messageManager->addError(__('Please select slider(s).'));
        } else {
            try {
                $sliderCollection = $this->_sliderCollectionFactory->create()
                    ->addFieldToFilter('slider_id', ['in' => $sliderIds]);

                foreach ($sliderCollection as $slider) {
                    $slider->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($sliderIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->_resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
