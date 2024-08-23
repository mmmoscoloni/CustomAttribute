<?php

namespace Vendor\CustomAttribute\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCustomAttribute extends Command
{
    const CUSTOM_ATTRIBUTE_CODE = 'special_attribute';
    const INPUT_KEY_VALUE = 'value';

    private $productCollectionFactory;
    private $state;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        State $state
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->state = $state;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('customattribute:update')
            ->setDescription('Update custom attribute for all products')
            ->addArgument(
                self::INPUT_KEY_VALUE,
                InputArgument::REQUIRED,
                'New value for the custom attribute'
            );
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = $input->getArgument(self::INPUT_KEY_VALUE);
    
        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        } catch (LocalizedException $e) {
        }
    
        $objectManager = ObjectManager::getInstance();
        $scopeConfig = $objectManager->get(ScopeConfigInterface::class);
        $isFeatureEnabled = $scopeConfig->isSetFlag('customattribute_section/general/feature_toggle', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    
        if (!$isFeatureEnabled) {
            $output->writeln("<error>The special attribute feature is disabled.</error>");
            return Cli::RETURN_FAILURE;
        }
    
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect(self::CUSTOM_ATTRIBUTE_CODE);
    
        $count = 0;
        foreach ($productCollection as $product) {
            $product->setData(self::CUSTOM_ATTRIBUTE_CODE, $value);
            $product->save();
            $count++;
        }
    
        $output->writeln("<info>Updated {$count} products with the value '{$value}' for attribute '". self::CUSTOM_ATTRIBUTE_CODE . "'</info>");
    
        return Cli::RETURN_SUCCESS;
    }
    
}
