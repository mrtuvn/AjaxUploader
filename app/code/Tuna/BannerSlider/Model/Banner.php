<?php
namespace Tuna\BannerSlider\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Logger\Monolog;
use Magento\Framework\Registry;

use Tuna\BannerSlider\Model\BannerFactory;
use Tuna\BannerSlider\Model\ValueFactory;



class Banner extends \Magento\Framework\Model\AbstractModel
{

    const BASE_MEDIA_PATH = 'tuna/bannerslider/images';

    const BANNER_TARGET_SELF = 0;
    const BANNER_TARGET_PARENT = 1;
    const BANNER_TARGET_BLANK = 2;


    protected $_coreRegistry;

    protected $_storeManager;

    protected $_storeViewId;

    protected $_bannerFactory;

    protected $_valueFactory;

    protected $_formFieldHtmlIdPrefix = 'banner_';

    protected $_valueCollectionFactory;

    protected $_sliderCollectionFactory;

    protected $_monolog;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Tuna\BannerSlider\Model\ResourceModel\Banner $resource,
        \Tuna\BannerSlider\Model\ResourceModel\Banner\Collection $resourceCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tuna\BannerSlider\Model\BannerFactory $bannerFactory,
        \Tuna\BannerSlider\Model\ValueFactory $valueFactory,
        \Tuna\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory,
        \Tuna\BannerSlider\Model\ResourceModel\Value\CollectionFactory $valueCollectionFactory,
        \Magento\Framework\Logger\Monolog $monolog,
        array $data = []

    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_bannerFactory = $bannerFactory;
        $this->_valueFactory = $valueFactory;
        $this->_valueCollectionFactory = $valueCollectionFactory;
        $this->_sliderCollectionFactory = $sliderCollectionFactory;
        //fix missing storemanager
        $this->_storeManager = $storeManager;
        $this->_monolog = $monolog;
        if ($storeViewId = $this->_storeManager->getStore()->getId()) {
            $this->_storeViewId = $storeViewId;
        }

    }


    public function getFormFieldHtmlIdPrefix()
    {
        return $this->_formFieldHtmlIdPrefix;
    }

    public function beforeSave()
    {
        if ($this->getStoreViewId()) {
            $defaultStore = $this->_bannerFactory->create()->setStoreViewId(null)->load($this->getId());
            $storeAttributes = $this->getStoreAttributes();
            $data = $this->getData();
            foreach ($storeAttributes as $attribute) {
                if (isset($data['use_default']) && isset($data['use_default'][$attribute])) {
                    $this->setData($attribute.'_in_store', false);
                } else {
                    $this->setData($attribute.'_in_store', true);
                    $this->setData($attribute.'_value', $this->getData($attribute));
                }
                $this->setData($attribute, $defaultStore->getData($attribute));
            }
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($storeViewId = $this->getStoreViewId()) {
            $storeAttributes = $this->getStoreAttributes();
            foreach ($storeAttributes as $attribute) {
                $attributeValue = $this->_valueFactory->create()
                    ->loadAttributeValue($this->getId(), $storeViewId, $attribute);
                if ($this->getData($attribute.'_in_store')) {
                    try {
                        if ($attribute == 'image' && $this->getData('delete_image')) {
                            $attributeValue->delete();
                        } else {
                            $attributeValue->setValue($this->getData($attribute.'_value'))->save();
                        }
                    } catch (\Exception $e) {
                        $this->_monolog->addError($e->getMessage());
                    }
                } elseif ($attributeValue && $attributeValue->getId()) {
                    try {
                        $attributeValue->delete();
                    } catch (\Exception $e) {
                        $this->_monolog->addError($e->getMessage());
                    }
                }
            }
        }
        return parent::afterSave();
    }

    public function getStoreAttributes()
    {
        return array(
            'banner',
            'status',
            'click_url',
            'target',
            'image_alt',
            'image',
        );
    }

    public function load($id, $field = null)
    {
        parent::load($id, $field);
        if ($this->getStoreViewId()) {
            $this->getStoreViewValue();
        }
        return $this;
    }

    public function getStoreViewValue($storeViewId = null)
    {
        if (!$storeViewId) {
            $storeViewId = $this->getStoreViewId();
        }
        if (!$storeViewId) {
            return $this;
        }
        $storeValues = $this->_valueCollectionFactory->create()
            ->addFieldToFilter('banner_id', $this->getId())
            ->addFieldToFilter('store_id', $storeViewId);
        foreach ($storeValues as $value) {
            $this->setData($value->getAttributeCode().'_in_store', true);
            $this->setData($value->getAttributeCode(), $value->getValue());
        }
        return $this;
    }

    public function getAvailableSlides()
    {
        $option[] = [
            'value' => '',
            'label' => __('-------- Please select a slider --------'),
        ];

        $sliderCollection = $this->_sliderCollectionFactory->create();
        foreach ($sliderCollection as $slider) {
            $option[] = [
                'value' => $slider->getId(),
                'label' => $slider->getTitle(),
            ];
        }

        return $option;

    }


    public function getTargetValue()
    {
        switch ($this->getTarget()) {
            case self::BANNER_TARGET_SELF:
                return '_self';
            case self::BANNER_TARGET_PARENT:
                return '_parent';
            default:
                return '_blank';
        }
    }


    public function getStoreViewId()
    {
        return $this->_storeViewId;
    }

    public function setStoreViewId($storeViewId)
    {
        $this->_storeViewId = $storeViewId;
        return $this;
    }
}