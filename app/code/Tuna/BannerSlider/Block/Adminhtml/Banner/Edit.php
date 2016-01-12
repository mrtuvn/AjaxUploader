<?php

/**
 * @author : mrtuvn
 *
 */

namespace Tuna\BannerSlider\Block\Adminhtml\Banner;

use Magento\Framework\Registry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    //fixed function
    protected function _construct()
    {
        $this->_objectId = 'banner_id';
        $this->_blockGroup = 'Tuna_BannerSlider'; //declare before controller
        $this->_controller = 'adminhtml_banner';

        parent::_construct();

        if ($this->getRequest()->getParam('current_slider_id')) {
            $this->buttonList->remove('save');
            $this->buttonList->remove('delete');

            $this->buttonList->remove('back');
            $this->buttonList->add(
                'close_window',
                [
                    'label' => __('Close Window'),
                    'onclick' => 'window.close();',
                ],
                10
            );

            $this->buttonList->add(
                'save_and_continue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'onclick' => 'customsaveAndContinueEdit()',
                ],
                10
            );

            $this->buttonList->add(
                'save_and_close',
                [
                    'label' => __('Save and Close'),
                    'class' => 'save_and_close',
                    'onclick' => 'saveAndCloseWindow()',
                ],
                10
            );

            $this->_formScripts[] = "
				require(['jquery'], function($){
					$(document).ready(function(){
						var input = $('<input class=\"custom-button-submit\" type=\"submit\" hidden=\"true\" />');
						$(edit_form).append(input);

						window.customsaveAndContinueEdit = function (){
							edit_form.action = '".$this->getSaveAndContinueUrl()."';
							$('.custom-button-submit').trigger('click');

				        }

			    		window.saveAndCloseWindow = function (){
			    			edit_form.action = '".$this->getSaveAndCloseWindowUrl()."';
							$('.custom-button-submit').trigger('click');
			            }
					});
				});
			";

            if ($bannerId = $this->getRequest()->getParam('banner_id')) {
                $this->_formScripts[] = '
					window.banner_id = '.$bannerId.';
				';
            }
        } else {
            $this->buttonList->add(
                'save_and_continue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ],
                ],
                10
            );
        }

        if ($this->getRequest()->getParam('saveandclose')) {
            $this->_formScripts[] = 'window.close();';
        }

    }


    protected function getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}',
                'store' => $this->getRequest()->getParam('store'),
                'banner_id' => $this->getRequest()->getParam('banner_id'),
                'current_slider_id' => $this->getRequest()->getParam('current_slider_id'),
            ]
        );
    }

    protected function getSaveAndCloseWindowUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}',
                'store' => $this->getRequest()->getParam('store'),
                'banner_id' => $this->getRequest()->getParam('banner_id'),
                'current_slider_id' => $this->getRequest()->getParam('current_slider_id'),
                'saveandclose' => 1,
            ]
        );
    }



}
