<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;

use Magento\Framework\App\Filesystem\DirectoryList;


class ExportXml extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{
    public function execute()
    {
        $fileName = 'sliders.xml';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Tuna\BannerSlider\Block\Adminhtml\Slider\Grid')->getXml();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
