<?php

namespace Tuna\BannerSlider\Model;

class Value extends \Magento\Framework\Model\AbstractModel
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,

        \Tuna\BannerSlider\Model\ResourceModel\Value $resource,
        \Tuna\BannerSlider\Model\ResourceModel\Value\Collection $resourceCollection,
        array $data = []
    ) {
        $this->_registry = $registry;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

    }

    public function loadAttributeValue($bannerId, $storeViewId, $attributeCode)
    {
        $attributeValue = $this->getResourceCollection()
            ->addFieldToFilter('banner_id', $bannerId)
            ->addFieldToFilter('store_id', $storeViewId)
            ->addFieldToFilter('attribute_code', $attributeCode)
            ->setPageSize(1)->setCurPage(1)
            ->getFirstItem();
        $this->setData('banner_id', $bannerId)
            ->setData('store_id', $storeViewId)
            ->setData('attribute_code', $attributeCode);
        if ($attributeValue->getId()) {
            $this->addData($attributeValue->getData())
                ->setId($attributeValue->getId());
        }
        return $this;
    }
}