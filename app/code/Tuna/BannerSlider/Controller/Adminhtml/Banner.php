<?php

namespace Tuna\BannerSlider\Controller\Adminhtml;

use Tuna\BannerSlider\Controller\Adminhtml\AbstractAction;

abstract class Banner extends \Tuna\BannerSlider\Controller\Adminhtml\AbstractAction
{


    protected function initBanner()
    {
        $bannerId = (int) $this->getRequest()->getParam('banner_id');
        $banner   = $this->_bannerFactory->create();
        if ($bannerId) {
            $banner->load($bannerId);
        }

        //$this->_coreRegistry->register('banner', $banner); DUPLICATE
        return $banner;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tuna_BannerSlider::bannerslider_banners');
    }
}