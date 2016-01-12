<?php

namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;


use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

use Tuna\BannerSlider\Controller\Adminhtml\Banner;
use Tuna\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;


class MassDelete extends Banner
{
    protected $_filter;
    protected $_collectionFactory;
    const ID_FIELD = 'banner_id';

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        array $data = []
    ){
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $bannerIds = $this->getRequest()->getParam('banner_id');
        if (!is_array($bannerIds) || empty($bannerIds)) {
            $this->messageManager->addError(__('Please select banner(s).'));
        } else {
            $bannerCollection = $this->_collectionFactory->create()
                ->addFieldToFilter('banner_id', ['in' => $bannerIds]);
            try {
                foreach ($bannerCollection as $banner) {
                    $banner->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($bannerIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->_resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }


    protected function deleteAll()
    {

        $collection = $this->_objectManager->get($this->_collectionFactory);
        $this->setSuccessMessage($this->delete($collection));
    }

    protected function excludedDelete(array $excluded)
    {

        $collection = $this->_objectManager->get($this->_collectionFactory);
        $collection->addFieldToFilter(static::ID_FIELD, ['nin' => $excluded]);
        $this->setSuccessMessage($this->delete($collection));
    }

    protected function selectedDelete(array $selected)
    {

        $collection = $this->_objectManager->get($this->_collectionFactory);
        $collection->addFieldToFilter(static::ID_FIELD, ['in' => $selected]);
        $this->setSuccessMessage($this->delete($collection));
    }

    protected function setSuccessMessage($count)
    {
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));
    }
}
