<?php


namespace RedboxDigital\Customer\Plugin\Magento\Sales\Model\Order;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Quote\Model\Quote\Address as QuoteAddress;


class CustomerManagement
{


	/**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Customer\Api\Data\RegionInterfaceFactory
     */
    protected $regionFactory;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;

    /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory
     * @param \Magento\Customer\Api\Data\RegionInterfaceFactory $regionFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Model\Customer $customerFactory,
        \Magento\Customer\Model\Address $addressFactory,
        \Magento\Customer\Api\Data\RegionInterfaceFactory $regionFactory,
        \Magento\Sales\Model\Order $orderRepository
    ) {
        $this->objectCopyService = $objectCopyService;
        $this->accountManagement = $accountManagement;
        $this->orderRepository = $orderRepository;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
        $this->regionFactory = $regionFactory;
    }
	
	
    public function aroundCreate(
        \Magento\Sales\Model\Order\CustomerManagement $subject,
        \Closure $proceed,
		$orderId
    ) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$result = $proceed($orderId);
		$order = $this->orderRepository->load($orderId);
		
		$customermodel = $this->customerFactory;
		$customerData = $customermodel->getDataModel();
		$customerData->setId($result->getId());
		$customerData->setCustomAttribute('linkedin_profile', $order->getLinkedinProfile());
		$customermodel->updateData($customerData);
		$customerResource = $objectManager->create ('\Magento\Customer\Model\ResourceModel\CustomerFactory')->create();
		$customerResource->saveAttribute($customermodel, 'linkedin_profile');
		 
		
		$customer = $this->customerFactory->load($result->getId());
		$customerAddressmodel = $this->addressFactory;
		$customerAddressData = $customerAddressmodel->getDataModel();
		
		$defaultid = $customer->getDefaultBilling();
		$order->getLinkedinProfile();
		$customerAddressData->setId($defaultid);
		$customerAddressData->setCustomerId($result->getId());
		$customerAddressData->setCustomAttribute('linkedin_profile', $order->getLinkedinProfile());
		$customerAddressmodel->updateData($customerAddressData);
		$customerAddressResource = $objectManager->create ('\Magento\Customer\Model\ResourceModel\AddressFactory')->create();
		$customerAddressResource->saveAttribute($customerAddressmodel, 'linkedin_profile');
		
		$defaultid = $customer->getDefaultShipping();
		$order->getShippingAddress()->getLinkedinProfile();
		$customerAddressData->setId($defaultid);
		$customerAddressData->setCustomerId($result->getId());
		$customerAddressData->setCustomAttribute('linkedin_profile', $order->getShippingAddress()->getLinkedinProfile());
		$customerAddressmodel->updateData($customerAddressData);
		$customerAddressResource = $objectManager->create ('\Magento\Customer\Model\ResourceModel\AddressFactory')->create();
		$customerAddressResource->saveAttribute($customerAddressmodel, 'linkedin_profile');
		
		
		//Your plugin code
		return $result;
    }
}