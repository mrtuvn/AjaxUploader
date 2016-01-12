<?php


namespace VES\GridUI\Model\ResourceModel;


class Grid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('ves_gridui_grid', 'grid_id'); //init(tablename, id)
	}
}