<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;

use Tuna\BannerSlider\Controller\Adminhtml\Banner;


class NewAction extends Banner
{

    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
