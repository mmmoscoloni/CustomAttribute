<?php

namespace Vendor\CustomAttribute\Console\Command;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Area;
use Magento\Eav\Setup\EavSetupFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableFeatureToggleCommand extends Command
{
    const FEATURE_TOGGLE_PATH = 'customattribute_section/general/feature_toggle';

    private $configWriter;
    private $state;
    private $eavSetupFactory;

    public function __construct(
        WriterInterface $configWriter,
        State $state,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->configWriter = $configWriter;
        $this->state = $state;
        $this->eavSetupFactory = $eavSetupFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('customattribute:feature-toggle:disable')
            ->setDescription('Disable the special attribute feature');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
        }
    
        $this->configWriter->save(self::FEATURE_TOGGLE_PATH, 0);
    
        $this->runUpgrade(0, $output);
    
        $output->writeln('<info>Feature toggle disabled successfully.</info>');
    
        return Cli::RETURN_SUCCESS;
    }
    
    private function runUpgrade($enable, OutputInterface $output)
    {
        $eavSetup = $this->eavSetupFactory->create();
    
        $output->writeln('<info>Running attribute update...</info>');
        $output->writeln('<info>Setting is_visible_on_front to ' . ($enable ? '1' : '0') . '</info>');
    
        $eavSetup->updateAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'special_attribute',
            'is_visible_on_front',
            $enable ? 1 : 0
        );
    
        $output->writeln('<info>Attribute update completed.</info>');
    }    
}
