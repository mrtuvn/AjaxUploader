<?php

namespace VES\FeaturedProduct\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class InstallSchema implements InstallSchemaInterface
{

    protected $groupCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory
     */
    protected $attributeFactory;

    /**
     * @var \Magento\Eav\Model\Entity\Type
     */
    protected $eavEntitiyType;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    public function __construct(
        \Magento\Eav\Model\Entity\Type $eavEntitiyType,
        \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory $attributeFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $groupCollectionFactory,
        MessageManagerInterface $messageManager
    ) {
        $this->attributeFactory         = $attributeFactory;
        $this->eavEntitiyType           = $eavEntitiyType;
        $this->groupCollectionFactory   = $groupCollectionFactory;
        $this->messageManager           = $messageManager;
    }

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $attributeSetCollection=$this->eavEntitiyType->loadByCode('catalog_product')->getAttributeSetCollection();
        $entityTypeId=$this->eavEntitiyType->loadByCode('catalog_product')->getId();

        $model = $this->attributeFactory->create();
        $data['attribute_code'] = 'is_featured';
        $data['is_user_defined'] =1;
        $data['frontend_input'] = 'boolean';
        $data += ['is_filterable' => 0, 'is_filterable_in_search' => 0, 'apply_to' => []];
        $data['backend_type'] = $model->getBackendTypeByInput($data['frontend_input']);
        $data['default_value'] = 0;
        $data['frontend_label']='Is Featured';
        $model->addData($data);
        $model->setEntityTypeId($entityTypeId);
        $model->setIsUserDefined(1);
        try {
            foreach($attributeSetCollection as $attributeSet)
            {
                $attributeSetId=$attributeSet->getId();
                $groupCollection=$this->groupCollectionFactory->create()->setAttributeSetFilter($attributeSetId)->load();
                $groupCode='product-details';
                $attributeGroupId=null;
                foreach ($groupCollection as $group) {
                    if ($group->getAttributeGroupCode() == $groupCode) {
                        $attributeGroupId = $group->getAttributeGroupId();
                        break;
                    }
                }
                $model->setAttributeSetId($attributeSetId);
                $model->setAttributeGroupId($attributeGroupId);
                $model->save();
            }

        } catch (\Exception $e) {
            echo $this->messageManager->addError($e->getMessage());

        }

        $installer->endSetup();
    }
}
