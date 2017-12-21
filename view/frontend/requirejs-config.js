var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'RedboxDigital_Customer/js/action/set-shipping-information-mixin': true
            },
			'Magento_Checkout/js/action/set-billing-address': {
                'RedboxDigital_Customer/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'RedboxDigital_Customer/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'RedboxDigital_Customer/js/action/set-billing-address-mixin': true
            },
			'Magento_Checkout/js/action/place-order': {
                'RedboxDigital_Customer/js/action/set-billing-address-mixin': true
            }
        }
    }
};