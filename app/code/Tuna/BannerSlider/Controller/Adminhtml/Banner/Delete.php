<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;

use Tuna\BannerSlider\Controller\Adminhtml\Banner;

class Delete extends Banner
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->_resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('banner');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Tuna\BannerSlider\Model\Banner');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted banner.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['banner_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a banner to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
