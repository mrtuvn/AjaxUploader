<?php

namespace Tuna\BannerSlider\Block\Adminhtml\Slider\Grid\Renderer;
use Tuna\BannerSlider\Block\Adminhtml\Slider\Grid\Renderer\Action\UrlBuilder;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $actionUrlBuilder;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        UrlBuilder $actionUrlBuilder,
        array $data = []
    ) {
        $this->actionUrlBuilder = $actionUrlBuilder;
        parent::__construct($context, $data);
    }


    public function render(\Magento\Framework\DataObject $row)
    {
        $href = $this->actionUrlBuilder->getUrl(
            $row->getIdentifier(),
            $row->getData('_first_store_id'),
            $row->getStoreCode()
        );
        return '<a href="' . $href . '" target="_blank">' . __('Preview') . '</a>';
    }
}
