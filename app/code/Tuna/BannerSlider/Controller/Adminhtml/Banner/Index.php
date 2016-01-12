<?php
/**
 * @author: mrtuvn
 */
namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;


class Index extends \Tuna\BannerSlider\Controller\Adminhtml\Banner
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->_resultForwardFactory->create();
            $resultForward->forward('grid');
            return $resultForward;
        }
        $resultPage = $this->_resultPageFactory->create();
        $this->_addBreadcrumb(__('Banners'), __('Banners'));
        $this->_addBreadcrumb(__('Manage Banners'), __('Manage Banners'));

        //$resultPage->setActiveMenu('Tuna_BannerSlider::bannerslider_banners');
        //$resultPage->getConfig()->getTitle()->prepend(__('Banner Managers'));
        return $resultPage;
    }
}