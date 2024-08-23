<?php

namespace Vendor\CustomAttribute\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'special_attribute',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Special Custom Attribute',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => 'special value',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
            ]
        );

        $attributeSetId = $eavSetup->getDefaultAttributeSetId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(\Magento\Catalog\Model\Product::ENTITY, $attributeSetId);
        $eavSetup->addAttributeToSet(
            \Magento\Catalog\Model\Product::ENTITY,
            $attributeSetId,
            $attributeGroupId,
            'special_attribute'
        );
    }
}
