<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;

use Tuna\BannerSlider\Controller\Adminhtml\Banner;

class Edit extends Banner
{


    public function execute()
    {

        $id = $this->getRequest()->getParam('banner_id');
        $storeViewId = $this->getRequest()->getParam('store');
        //$model = $this->_bannerFactory->create(); //init data before edit

        /** @var $banner */
        $banner = $this->initBanner();
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Tuna_BannerSlider::bannerslider_banners');
        $resultPage->getConfig()->getTitle()->set((__('Banner')));

        if ($id) {
            $banner->setStoreViewId($storeViewId)->load($id);
            if (!$banner->getId()) {
                $this->messageManager->addError(__('This banner no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'bannerslideradmin/*/edit',
                    [
                        'banner_id' => $banner->getId(),
                        '_current'   => true
                    ]
                );

                return $resultRedirect;
            }
        }

        //$resultPage->getConfig()->getTitle()->append('New Banner 12312');
        //$data = $this->_getSession()->getData('bannerslideradmin_banner_data', true);
        $data = $this->_getSession()->getPostData(true);
        //$data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $banner->setData($data);
        }

        $this->_coreRegistry->register('banner', $banner);

        return $resultPage;
    }

}
