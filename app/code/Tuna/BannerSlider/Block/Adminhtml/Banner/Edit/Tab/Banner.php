<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tuna\BannerSlider\Block\Adminhtml\Banner\Edit\Tab;

//use Magento\Customer\Controller\RegistryConstants;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Tuna\BannerSlider\Model\Status;

/**
 * Customer account form block
 */
class Banner extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    protected $wysiwygConfig; //add this fixed bug? have no idea why

    protected $_coreRegistry;

    protected $_objectFactory;

    protected $_valueCollectionFactory;

    protected $_sliderFactory;

    protected $_banner;

    protected $_systemStore;

    public function __construct(
        WysiwygConfig $wysiwygConfig,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Tuna\BannerSlider\Model\Banner $banner,
        \Tuna\BannerSlider\Model\ResourceModel\Value\CollectionFactory $valueCollectionFactory,
        \Tuna\BannerSlider\Model\SliderFactory $sliderFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->wysiwygConfig            = $wysiwygConfig;
        $this->_objectFactory           = $objectFactory;
        $this->_banner                  = $banner;
        $this->_valueCollectionFactory  = $valueCollectionFactory;
        $this->_sliderFactory           = $sliderFactory;
        $this->_systemStore             = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);

    }



    protected function _prepareForm()
    {
        $banner = $this->_coreRegistry->registry('banner');

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $timeFormat = $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT);

        $bannerAttributes = $this->_banner->getStoreAttributes();

        $bannerAttributesInStores = ['store_id' => ''];

        foreach ($bannerAttributes as $bannerAttribute) {
            $bannerAttributesInStores[$bannerAttribute.'_in_store'] = '';
        }

        $dataObj = $this->_objectFactory->create(
            ['data' => $bannerAttributesInStores]
        );


        if ($sliderId = $this->getRequest()->getParam('current_slider_id')) {
            $banner->setSliderId($sliderId);
        }



        $storeViewId = $this->getRequest()->getParam('store');

        $attributesInStore = $this->_valueCollectionFactory
            ->create()
            ->addFieldToFilter('banner_id', $banner->getId())
            ->addFieldToFilter('store_id', $storeViewId)
            ->getColumnValues('attribute_code');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix($this->_banner->getFormFieldHtmlIdPrefix()); //pass formPrefix: banner_

        // required class fieldset-wide here
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Banner Information'),
                'class' => 'fieldset-wide'
            ]
        );

        if ($banner->getId()) {
            $fieldset->addField(
                'banner_id',
                'hidden',
                [
                    'name' => 'banner_id'
                ]
            );
        }

        $elements = []; // list all field

        $elements['banner_name'] = $fieldset->addField(
            'banner_name',
            'text',
            [
                'name' => 'banner_name',
                'label' => __('Banner Name'),
                'title' => __('Banner Name'),
                'required' => true,
            ]
        );

        if ($this->_storeManager->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                [
                    'name'      => 'stores[]',
                    'value'     => $this->_storeManager->getStore(true)->getId()
                ]
            );
            $banner->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $elements['status'] = $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Banner Status'),
                'name' => 'status',
                'options' => Status::getAvailableStatuses(),
            ]
        );

        $slider = $this->_sliderFactory->create()->load($sliderId);

        if ($slider->getId()) {
            $elements['slider_id'] = $fieldset->addField(
                'slider_id',
                'select',
                [
                    'label' => __('Slider'),
                    'name' => 'slider_id',
                    'values' => [
                        [
                            'value' => $slider->getId(),
                            'label' => $slider->getTitle(),
                        ],
                    ],
                ]
            );
        } else {
            $elements['slider_id'] = $fieldset->addField(
                'slider_id',
                'select',
                [
                    'label' => __('Slider'),
                    'name' => 'slider_id',
                    'values' => $banner->getAvailableSlides(),
                ]
            );
        }

        $elements['image_alt'] = $fieldset->addField(
            'image_alt',
            'text',
            [
                'title' => __('Alt Text'),
                'label' => __('Alt Text'),
                'name' => 'image_alt',
                'note' => 'Used for SEO',
            ]
        );
        $elements['click_url'] = $fieldset->addField(
            'click_url',
            'text',
            [
                'title' => __('URL'),
                'label' => __('URL'),
                'name' => 'click_url',
            ]
        );


        $elements['image'] = $fieldset->addField(
            'image',
            'image',
            [
                'title' => __('Banner Image'),
                'label' => __('Banner Image'),
                'name' => 'image',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
                'required' => true
            ]
        );

        if($dataObj->hasData('start_time')) {
            $datetime = new \DateTime($dataObj->getData('start_time'));
            $dataObj->setData('start_time', $datetime->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone())));
        }
        if($dataObj->hasData('end_time')) {
            $datetime = new \DateTime($dataObj->getData('end_time'));
            $dataObj->setData('end_time', $datetime->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone())));
        }

        $style = 'color: #000;background-color: #fff; font-weight: bold; font-size: 13px;';

        $elements['start_time'] = $fieldset->addField(
            'start_time',
            'date',
            [
                'name' => 'start_time',
                'label' => __('Start at'),
                'title' => __('Start at'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => $this->_localeDate->getDateTimeFormat(\IntlDateFormatter::SHORT),
            ]
        );
        $elements['end_time'] = $fieldset->addField(
            'end_time',
            'date',
            [
                'name' => 'end_time',
                'label' => __('End at'),
                'title' => __('End at'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => $this->_localeDate->getDateTimeFormat(\IntlDateFormatter::SHORT)
            ]
        );

        $elements['target'] = $fieldset->addField(
            'target',
            'select',
            [
                'label' => __('Target'),
                'name' => 'target',
                'values' => [
                    [
                        'value' => \Tuna\BannerSlider\Model\Banner::BANNER_TARGET_SELF,
                        'label' => __('New Window with Browser Navigation'),
                    ],
                    [
                        'value' => \Tuna\BannerSlider\Model\Banner::BANNER_TARGET_PARENT,
                        'label' => __('Parent Window with Browser Navigation'),
                    ],
                    [
                        'value' => \Tuna\BannerSlider\Model\Banner::BANNER_TARGET_BLANK,
                        'label' => __('New Window without Browser Navigation'),
                    ],
                ],
            ]
        );


        foreach ($attributesInStore as $attribute) {
            if (isset($elements[$attribute])) {
                $elements[$attribute]->setStoreViewId($storeViewId);
            }
        }


        $form->addValues($dataObj->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getBanner()
    {
        return $this->_coreRegistry->registry('banner');
    }



    public function getTabLabel()
    {
        return __('Banner Information');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {

        return false;
    }



}
