<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tuna\BannerSlider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
//use Magento\Cms\Api\BlockRepositoryInterface as BlockRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Cms\Api\Data\BlockInterface;
use Tuna\BannerSlider\Controller\Adminhtml\Banner;
use Tuna\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;

class InlineEdit extends Banner
{

    protected $bannerCollectionFactory;

    protected $jsonFactory;

    public function __construct(
        Context $context,
        CollectionFactory $bannerCollectionFactory,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory   = $jsonFactory;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
    }


    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $blockId) {

                    $block = $this->bannerCollectionFactory->getById($blockId);
                    try {
                        $block->setData(array_merge($block->getData(), $postItems[$blockId]));
                        $this->bannerCollectionFactory->save($block);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithBlockId(
                            $block,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }


    /*protected function getErrorWithBlockId(BlockInterface $block, $errorText)
    {
        return '[Block ID: ' . $block->getId() . '] ' . $errorText;
    }*/
}
