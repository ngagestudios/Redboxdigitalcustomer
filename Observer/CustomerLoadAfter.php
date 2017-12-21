<?php
namespace RedboxDigital\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;

class CustomerLoadAfter implements ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
 
		$customer = $observer->getCustomer();
		$extensionAttributes = $customer->getExtensionAttributes();
		if ($extensionAttributes === null) {
			$extensionAttributes = $this->getCustomerExtensionDependency();
		}
		$attr = $customer->getData('linkedin_profile');
		$extensionAttributes->setLinkedinProfile($attr);
	    $customer->setExtensionAttributes($extensionAttributes);
 
	}
 
	private function getCustomerExtensionDependency()
	{
		$orderExtension = \Magento\Framework\App\ObjectManager::getInstance()->get(
			'\Magento\Customer\Api\Data\CustomerExtension'
		);
		return $orderExtension;
	}
 
}