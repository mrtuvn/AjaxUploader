<?php


namespace Tuna\BannerSlider\Controller\Adminhtml\Slider;

use Magento\Framework\App\Filesystem\DirectoryList;


class ExportCsv extends \Tuna\BannerSlider\Controller\Adminhtml\Slider
{
    public function execute()
    {
        $fileName = 'sliders.csv';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Tuna\BannerSlider\Block\Adminhtml\Slider\Grid')->getCsv();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
