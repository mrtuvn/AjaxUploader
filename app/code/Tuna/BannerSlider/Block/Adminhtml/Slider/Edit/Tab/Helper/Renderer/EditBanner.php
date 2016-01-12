<?php


namespace Tuna\BannerSlider\Block\Adminhtml\Slider\Edit\Tab\Helper\Renderer;


class EditBanner extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_storeManager;


    protected $_bannerFactory;


    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tuna\BannerSlider\Model\BannerFactory $bannerFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager  = $storeManager;
        $this->_bannerFactory = $bannerFactory;
    }


    public function render(\Magento\Framework\DataObject $row)
    {
        return '<a href="' . $this->getUrl('*/banner/edit', ['_current' => FALSE, 'banner_id' => $row->getId()]) . '" target="_blank">Edit</a> ';
    }
}
