<?php
namespace Vendor\CustomAttribute\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SpecialAttribute implements ArgumentInterface
{
    private $registry;
    private $scopeConfig;

    const XML_PATH_FEATURE_TOGGLE = 'customattribute_section/general/feature_toggle';

    public function __construct(
        Registry $registry,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->registry = $registry;
        $this->scopeConfig = $scopeConfig;
    }

    public function getSpecialAttributeValue()
    {
        $isFeatureEnabled = $this->scopeConfig->isSetFlag(self::XML_PATH_FEATURE_TOGGLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if (!$isFeatureEnabled) {
            return null;
        }

        $product = $this->registry->registry('current_product');
        if ($product instanceof Product) {
            return $product->getData('special_attribute');
        }
        return null;
    }
}
