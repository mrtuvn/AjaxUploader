<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;

use Tuna\BannerSlider\Controller\Adminhtml\Banner;

class AbstractMassStatus extends Banner
{
	public function execute()
	{
		$bannerIds = $this->getRequest()->getParam('banner');
	}
}