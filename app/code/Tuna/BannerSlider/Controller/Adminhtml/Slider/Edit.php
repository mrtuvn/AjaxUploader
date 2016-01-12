<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


class Edit extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{

    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();

        $id = $this->getRequest()->getParam('slider_id');
        $model = $this->_sliderFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This slider no longer exists.'));

                $resultRedirect = $this->_resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('slider', $model);

        return $resultPage;
    }
}
