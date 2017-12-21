<?php


namespace RedboxDigital\Customer\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;

class InstallData implements InstallDataInterface
{

    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'linkedin_profile', [
            'type' => 'varchar',
            'label' => 'Linkedin Profile',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
			'is_unique' => true,
            'backend' => ''
        ]);

        
       $attribute = $customerSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'linkedin_profile');
			$used_in_forms[]="adminhtml_customer";
			$used_in_forms[]="checkout_register";
			$used_in_forms[]="customer_account_create";
			$used_in_forms[]="customer_account_edit";
			$used_in_forms[]="adminhtml_checkout";
			$attribute->setData("used_in_forms", $used_in_forms)
				->setData("is_used_for_customer_segment", true)
				->setData("is_system", 0)
				->setData("is_user_defined", 1)
				->setData("is_visible", 1)
				->setData("sort_order", 100);
			$attribute->save();
    }
}
