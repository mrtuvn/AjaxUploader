<?php

namespace Tuna\BannerSlider\Block\Adminhtml\Slider;

use Tuna\BannerSlider\Model\Status;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $_sliderCollectionFactory;


    protected $_bannersliderHelper;

    protected $pageLayoutBuilder; //MISS THIS

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Tuna\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory,
        \Tuna\BannerSlider\Helper\Data $bannersliderHelper,
        \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        array $data = []
    ) {
        $this->_sliderCollectionFactory = $sliderCollectionFactory;
        $this->_bannersliderHelper = $bannersliderHelper;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        parent::__construct($context, $backendHelper, $data);
    }


    protected function _construct()
    {
        parent::_construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slider_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }


    protected function _prepareCollection()
    {
        $collection = $this->_sliderCollectionFactory->create();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }


    protected function _prepareColumns()
    {
        $this->addColumn(
            'slider_id',
            [
                'header' => __('Slider ID'),
                'type' => 'number',
                'index' => 'slider_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'index' => 'position',
                'type' => 'options',
                'class' => 'xxx',
                'width' => '50px',
                'options' => $this->_bannersliderHelper->getAvailablePositions(),
            ]
        );

        $this->addColumn(
            'style_slide',
            [
                'header' => __('Slider\'s Mode'),
                'index' => 'style_slide',
                'class' => 'xxx',
                'width' => '50px',
                'type' => 'options',
                'options' => $this->_bannersliderHelper->getSliderModeAvailable(),
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => Status::getAvailableStatuses(),
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit',
                        ],
                        'field' => 'slider_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }


    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('slider');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('bannerslideradmin/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = Status::getAvailableStatuses();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('bannerslideradmin/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }


    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }


    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array('slider_id' => $row->getId())
        );
    }
}
