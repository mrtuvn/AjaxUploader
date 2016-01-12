<?php
namespace Tuna\BannerSlider\Block\Adminhtml\Banner\Helper\Renderer;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Framework\DataObject;

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_storeManager;

    protected $_bannerFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tuna\BannerSlider\Model\BannerFactory $bannerFactory,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_bannerFactory = $bannerFactory;
    }

    public function render(DataObject $row)
    {
        $storeViewId = $this->getRequest()->getParam('store');
        $banner = $this->_bannerFactory->create()->setStoreViewId($storeViewId)->load($row->getId());
        $srcImage = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $banner->getImage();

        return '<image width="150" height="50" src ="'.$srcImage.'" alt="'.$banner->getImage().'" >';
    }
}