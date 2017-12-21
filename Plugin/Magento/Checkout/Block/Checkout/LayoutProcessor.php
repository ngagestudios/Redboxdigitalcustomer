<?php


namespace RedboxDigital\Customer\Plugin\Magento\Checkout\Block\Checkout;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Helper\Address as AddressHelper;
use Magento\Customer\Model\Session;
use Magento\Directory\Helper\Data as DirectoryHelper;

class LayoutProcessor
{

	/**
     * @var AddressHelper
     */
    private $addressHelper;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    private $customer;

    /**
     * @var \Magento\Directory\Helper\Data
     */
    private $directoryHelper;

    /**
     * List of codes of countries that must be shown on the top of country list
     *
     * @var array
     */
    private $topCountryCodes;
	
	public function __construct(
        AddressHelper $addressHelper,
        Session $customerSession,
        CustomerRepository $customerRepository,
        DirectoryHelper $directoryHelper
    ) {
        $this->addressHelper = $addressHelper;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->directoryHelper = $directoryHelper;
        $this->topCountryCodes = $directoryHelper->getTopCountryCodes();
    }
	
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        $result
    ) {

		
		if(isset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress'])){
				
			$result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['linkedin_profile'] = [
				'component' => 'Magento_Ui/js/form/element/abstract',
				'config' => [
					'customScope' => 'shippingAddress.custom_attributes',
					'template' => 'ui/form/field',
					'elementTmpl' => 'ui/form/element/input',
					'options' => [],
					'id' => 'linkedin-profile'
				],
				'dataScope' => 'shippingAddress.custom_attributes.linkedin_profile',
				'label' => 'Linkedin Profile',
				'provider' => 'checkoutProvider',
				'visible' => true,
				'validation' => [
					'required-entry' => 1,
					'max_text_length' => 250,
					'min_text_length' => 1,
					'validate-url' => true
				],
				'sortOrder' => 41,
				'id' => 'linkedin-profile'
			];
	 
			if ($this->customerSession->isLoggedIn()) {
				 $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['linkedin_profile']['value'] =   $this->customerRepository->getById($this->customerSession->getCustomerId())->getExtensionAttributes()->getLinkedinProfile();
			}
		
		}
		
		if(isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children'])){
			$paymentForms = $result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'];
			foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
				$paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }



                $result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children']['linkedin_profile'] = [
					'component' => 'Magento_Ui/js/form/element/abstract',
					'config' => [
						'customScope' => 'billingAddress'.$paymentMethodCode.'.custom_attributes',
						'template' => 'ui/form/field',
						'elementTmpl' => 'ui/form/element/input',
						'options' => [],
						'id' => 'linkedin-profile'
					],
					'dataScope' => 'billingAddress'.$paymentMethodCode.'.custom_attributes.linkedin_profile',
					'label' => 'Linkedin Profile',
					'provider' => 'checkoutProvider',
					'visible' => true,
					'validation' => [
						'required-entry' => 1,
						'max_text_length' => 250,
						'min_text_length' => 1,
						'validate-url' => true
					],
					'sortOrder' => 41,
					'id' => 'linkedin-profile'
				];
				
				
				if ($this->customerSession->isLoggedIn()) {
					  $result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children']['linkedin_profile']['value'] =   $this->customerRepository->getById($this->customerSession->getCustomerId())->getExtensionAttributes()->getLinkedinProfile();
				}
			}
			
			
		
		}
 
        return $result;
    }
}
