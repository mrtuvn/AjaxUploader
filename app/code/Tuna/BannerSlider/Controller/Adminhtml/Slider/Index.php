<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;


class Index extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{

    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->_resultForwardFactory->create();
            $resultForward->forward('grid');

            return $resultForward;
        }

        $resultPage = $this->_resultPageFactory->create();

        /*
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(__('Sliders'), __('Sliders'));
        $this->_addBreadcrumb(__('Manage Sliders'), __('Manage Sliders'));

        return $resultPage;
    }
}
