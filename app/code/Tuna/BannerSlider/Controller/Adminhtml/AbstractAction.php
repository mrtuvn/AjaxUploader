<?php

namespace Tuna\BannerSlider\Controller\Adminhtml;



abstract class AbstractAction extends \Magento\Backend\App\Action
{

    protected $_jsHelper;

    protected $_storeManager;


    protected $_coreRegistry;


    protected $_resultForwardFactory;


    protected $_resultLayoutFactory;


    protected $_resultRedirectFactory;


    protected $_resultPageFactory;


    protected $_fileFactory;


    protected $_bannerFactory;


    protected $_sliderFactory;


    protected $_bannerCollectionFactory;


    protected $_sliderCollectionFactory;

    protected $_auth;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,

        \Tuna\BannerSlider\Model\BannerFactory $bannerFactory,
        \Tuna\BannerSlider\Model\SliderFactory $sliderFactory,
        \Tuna\BannerSlider\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory,
        \Tuna\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory
    ){
        parent::__construct($context);
        $this->_coreRegistry            = $registry;
        $this->_jsHelper                = $jsHelper;
        $this->_storeManager            = $storeManager;
        $this->_fileFactory             = $fileFactory;
        $this->_resultPageFactory       = $resultPageFactory;
        $this->_resultLayoutFactory     = $resultLayoutFactory;
        $this->_resultRedirectFactory   = $resultRedirectFactory;
        $this->_resultForwardFactory    = $resultForwardFactory;
        $this->_bannerFactory           = $bannerFactory;
        $this->_sliderFactory           = $sliderFactory;
        $this->_bannerCollectionFactory = $bannerCollectionFactory;
        $this->_sliderCollectionFactory = $sliderCollectionFactory;
    }


}