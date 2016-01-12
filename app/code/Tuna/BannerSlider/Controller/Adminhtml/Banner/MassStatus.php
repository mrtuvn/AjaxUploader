<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


use Tuna\BannerSlider\Controller\Adminhtml\Banner;

class MassStatus extends Banner
{

    public function execute()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($bannerIds) || empty($bannerIds)) {
            $this->messageManager->addError(__('Please select slider(s).'));
        } else {
            try {
                $bannerCollection = $this->_bannerCollectionFactory->create()
                    ->addFieldToFilter('banner_id', ['in' => $bannerIds]);

                foreach ($bannerCollection as $banner) {
                    $banner->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($bannerIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->_resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
