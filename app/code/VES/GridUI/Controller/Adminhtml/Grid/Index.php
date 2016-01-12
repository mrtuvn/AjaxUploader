<?php

namespace VES\GridUI\Controller\Adminhtml\Grid;


class Index extends \Magento\Backend\App\Action
{
	protected $resultPageFactory;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	/**
	 * show grid in backend view
	 *
	 * @return $this|\Magento\Framework\View\Result\Page
	 */
	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		//$resultPage = $this->_setActiveMenu('VES_GridUI::view'); //active menu in left menu admin
		//$resultPage->addBreadcrumb(__('Grid View'), __('Grid View'));
		//$resultPage->getConfig()->getTitle()->prepend(__('Grid View'));
		return $resultPage;
	}
}