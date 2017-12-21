<?php


namespace RedboxDigital\Customer\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;

class UpgradeData implements UpgradeDataInterface
{


	public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }
	
	
    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
		$customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
		
        if (version_compare($context->getVersion(), "1.0.1", "<")) {
				$customerSetup->addAttribute(\Magento\Customer\Model\Indexer\Address\AttributeProvider::ENTITY, 'linkedin_profile', [
					'label' => 'Linkedin Profile',
					'input' => 'text',
					'type' => 'varchar',
					'source' => '',
					'required' => false,
					'position' => 334,
					'visible' => true,
					'system' => false,
					'is_used_in_grid' => false,
					'is_visible_in_grid' => false,
					'is_filterable_in_grid' => false,
					'is_searchable_in_grid' => false,
					'backend' => ''
				]);
        }
		
		
		if (version_compare($context->getVersion(), "1.0.2", "<")) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$salesSetup = $objectManager->create('Magento\Sales\Setup\SalesSetup');
			
			$salesSetup->addAttribute('order', 'linkedin_profile', ['type' =>'varchar']);
			$quoteSetup = $objectManager->create('Magento\Quote\Setup\QuoteSetup');
			
			$quoteSetup->addAttribute('quote', 'linkedin_profile', ['type' =>'varchar']);
        }
		
		
		if (version_compare($context->getVersion(), "1.0.3", "<")) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$salesSetup = $objectManager->create('Magento\Sales\Setup\SalesSetup');
			
			$salesSetup->addAttribute('order_address', 'linkedin_profile', ['type' =>'varchar']);
			$quoteSetup = $objectManager->create('Magento\Quote\Setup\QuoteSetup');
			
			$quoteSetup->addAttribute('quote_address', 'linkedin_profile', ['type' =>'varchar']);
        }
		
		
		if (version_compare($context->getVersion(), "1.0.4", "<")) {
			$attribute = $customerSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Indexer\Address\AttributeProvider::ENTITY, 'linkedin_profile');
			$used_in_forms[]="adminhtml_customer_address";
			$used_in_forms[]="customer_address_edit";
			$used_in_forms[]="customer_register_address";
			$attribute->setData("used_in_forms", $used_in_forms)
				->setData("is_used_for_customer_segment", true)
				->setData("is_system", 0)
				->setData("is_user_defined", 1)
				->setData("is_visible", 1)
				->setData("sort_order", 100);
			$attribute->save();
				
		}
		
        $setup->endSetup();
    }
}