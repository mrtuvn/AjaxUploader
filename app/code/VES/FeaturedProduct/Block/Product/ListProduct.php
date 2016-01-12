<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace VES\FeaturedProduct\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;

// my additional files
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $_productRepository;

    protected $_searchCriteria;

    protected $_searchCriteriaGroup;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaInterface $searchCriteria,
        FilterGroup $searchCriteriaGroup,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_productRepository = $productRepository; // new injection
        $this->_searchCriteria  = $searchCriteria; // new injection
        $this->_searchCriteriaGroup = $searchCriteriaGroup; // new injection
        parent::__construct(
            $context,
            $data
        );
    }

    protected function _prepareLayout()
    {
        $this->setChild(
            'grid' ,
            $this->getLayout()->createBlock(
                'VES\FeaturedProduct\Block\Product\ListProduct',
                'featuredproduct.block.list'
            )
        );


        return parent::_prepareLayout();
    }

    /**
     * modify to get collection featured products
     *
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {

            $this->_searchCriteria->setFilterGroups([$this->_searchCriteriaGroup]);
            $productCollection =  $this->_productRepository->getList($this->_searchCriteria)->getItems(); //_productRepository->get('simple')

            $featuredProducts = $productCollection
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('price')
                ->addAttributeToFilter('is_featured', 1)
                ->addAttributeToFilter('status', 1)
                ->setPageSize($productCollection->count())
                ->setCurPage(1)
                ->load();

            $this->_productCollection = $featuredProducts;

        }

        return $this->_productCollection;
    }



    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }


    public function getMode()
    {
        return 'grid';
    }


    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getProductCollection();

        // use sortable parameters
        $orders = $this->getAvailableOrders();
        if ($orders) {
            $toolbar->setAvailableOrders($orders);
        }
        $sort = $this->getSortBy();
        if ($sort) {
            $toolbar->setDefaultOrder($sort);
        }
        $dir = $this->getDefaultDirection();
        if ($dir) {
            $toolbar->setDefaultDirection($dir);
        }
        $modes = $this->getModes();
        if ($modes) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $this->_getProductCollection()]
        );

        $this->_getProductCollection()->load();

        return parent::_beforeToHtml();
    }


    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();
        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
        return $block;
    }


    public function getAdditionalHtml()
    {
        return $this->getChildHtml('additional');
    }


    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }


    public function setCollection($collection)
    {
        $this->_productCollection = $collection;
        return $this;
    }



    public function getPriceBlockTemplate()
    {
        return $this->_getData('price_block_template');
    }


    protected function _getConfig()
    {
        return $this->_catalogConfig;
    }



    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

}
