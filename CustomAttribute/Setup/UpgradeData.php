<?php

namespace Vendor\CustomAttribute\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;
    private $scopeConfig;

    public function __construct(EavSetupFactory $eavSetupFactory, ScopeConfigInterface $scopeConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $isFeatureEnabled = $this->scopeConfig->isSetFlag(
            'customattribute_section/general/feature_toggle',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $eavSetup->updateAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'special_attribute',
            'is_visible_on_front',
            $isFeatureEnabled ? 1 : 0
        );

        $setup->endSetup();
    }
}
