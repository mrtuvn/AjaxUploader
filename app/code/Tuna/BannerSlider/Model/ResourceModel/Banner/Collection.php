<?php

namespace Tuna\BannerSlider\Model\ResourceModel\Banner;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_storeViewId = null;

    protected $_storeManager;

    protected $_addedTable = [];

    protected $_isLoadSliderTitle = FALSE;

    protected $_resource;

    protected function _construct()
    {
        $this->_init('Tuna\BannerSlider\Model\Banner', 'Tuna\BannerSlider\Model\ResourceModel\Banner');
    }


    public function __construct(
            \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
            \Psr\Log\LoggerInterface $logger,
            \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
            \Magento\Framework\Event\ManagerInterface $eventManager,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
            \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );

        $this->_storeManager = $storeManager;
        $this->_resource = $resource;

        if ($storeViewId = $this->_storeManager->getStore()->getId()) {
           $this->_storeViewId = $storeViewId;
        }
    }

    public function setIsLoadSliderTitle($isLoadSliderTitle)
    {
        $this->_isLoadSliderTitle = $isLoadSliderTitle;
        return $this;
    }


    public function isLoadSliderTitle()
    {
        return $this->_isLoadSliderTitle;
    }

    public function getConnection()
    {
        return $this->getResource()->getConnection();
    }

    protected function _beforeLoad()
    {
        if ($this->isLoadSliderTitle()) {
            $this->joinSliderTitle();
        }
        return parent::_beforeLoad();
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($storeViewId = $this->getStoreViewId()) {
            foreach ($this->_items as $item) {
                $item->setStoreViewId($storeViewId)->getStoreViewValue();
            }
        }
        return $this;
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


    public function joinSliderTitle()
    {
        $this->getSelect()->joinLeft(
            ['sliderTable' => $this->getTable('tuna_bannerslider_slider')],
            'main_table.slider_id = sliderTable.slider_id',
            ['title' => 'sliderTable.title', 'slider_status' => 'sliderTable.status']
        );
        return $this;
    }

    public function setOrderRandByBannerId()
    {
        $this->getSelect()->orderRand('main_table.banner_id');
        return $this;
    }

    protected function _implodeCondition($firstCondition, $secondCondition, $type)
    {
        return '(' . implode(') ' . $type . ' (', [$firstCondition, $secondCondition]) . ')';
    }




    public function addFieldToFilter($field, $condition = null)
    {
        $attributes = array(
            'name',
            'status',
            'click_url',
            'target',
            'image_alt',
            'maintable',
        );
        $storeViewId = $this->getStoreViewId();
        if (in_array($field, $attributes) && $storeViewId) {
            if (!in_array($field, $this->_addedTable)) {
                $sql = sprintf(
                    'main_table.banner_id = %s.banner_id AND %s.store_id = %s  AND %s.attribute_code = %s ',
                    $this->getConnection()->quoteTableAs($field),
                    $this->getConnection()->quoteTableAs($field),
                    $this->getConnection()->quote($storeViewId),
                    $this->getConnection()->quoteTableAs($field),
                    $this->getConnection()->quote($field)
                );
                $this->getSelect()
                    ->joinLeft(array($field => $this->getTable('tuna_bannerslider_value')), $sql, array());
                $this->_addedTable[] = $field;
            }
            $fieldNullCondition = $this->_translateCondition("$field.value", ['null' => TRUE]);
            $mainfieldCondition = $this->_translateCondition("main_table.$field", $condition);
            $fieldCondition = $this->_translateCondition("$field.value", $condition);
            $condition = $this->_implodeCondition(
                $this->_implodeCondition($fieldNullCondition, $mainfieldCondition, \Zend_Db_Select::SQL_AND),
                $fieldCondition,
                \Zend_Db_Select::SQL_OR
            );
            $this->_select->where($condition, NULL, \Magento\Framework\DB\Select::TYPE_CONDITION);
            return $this;
        }
        if ($field == 'store_id') {
            $field = 'main_table.banner_id';
        }
        return parent::addFieldToFilter($field, $condition);
    }




}
