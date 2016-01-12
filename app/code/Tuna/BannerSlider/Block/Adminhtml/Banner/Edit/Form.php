<?php


namespace Tuna\BannerSlider\Block\Adminhtml\Banner\Edit;


class Form extends \Magento\Backend\Block\Widget\Form\Generic
{


    //only this
    protected function _prepareForm()
    {
        //id common for all form
        $form = $this->_formFactory->create(
            array(
                'data' => array(
                    'id'      => 'edit_form',
                    'action'  => $this->getData('action'),
                    'method'  => 'post',
                    'enctype' => 'multipart/form-data',
                ),
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
