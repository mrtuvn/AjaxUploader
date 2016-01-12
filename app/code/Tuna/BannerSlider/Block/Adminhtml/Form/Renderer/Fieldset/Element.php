<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Catalog fieldset element renderer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Tuna\BannerSlider\Block\Adminhtml\Form\Renderer\Fieldset;

class Element extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element
{

    protected $_template = 'Tuna_BannerSlider::form/renderer/fieldset/element.phtml';


    public function getElementName()
    {
        return $this->getElement()->getName();
    }

    public function getElementStoreViewId()
    {
        return $this->getElement()->getStoreViewId();
    }


    public function canDisplayUseDefault()
    {
        return ($this->getRequest()->getParam('store') && $this->getElement()->getDateFormat() == NULL && $this->getElementName() != 'slider_id') ? TRUE : FALSE;
    }


    public function usedDefault()
    {
        return $this->getElementStoreViewId() ? false : true;
    }


    public function checkFieldDisable()
    {
        if (!$this->getElementStoreViewId() && $this->getElementName() != 'banner_id' && $this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }


    public function getScopeLabel()
    {
        if ($this->getElement()->getDateFormat() != null || $this->getElementName() == 'slider_id') {
            return '[GLOBAL]';
        }
        return '[STORE VIEW]';
    }


    public function getElementLabelHtml()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        if (!empty($label)) {
            $element->setLabel(__($label));
        }
        return $element->getLabelHtml();
    }


    public function getElementHtml()
    {
        return $this->getElement()->getElementHtml();
    }


    protected function _getDefaultStoreId()
    {
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }
}
