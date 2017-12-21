<?php
namespace RedboxDigital\Customer\Observer;

class ModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{
    protected $logger;
    protected $quoteRepository;
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Sales\Model\Order $order */
			$order = $observer->getOrder();
			$quote = $this->quoteRepository->get($order->getQuoteId());
        
            try {
                $order->getBillingAddress()->setLinkedinProfile($quote->getBillingAddress()->getLinkedinProfile())->save();
				$order->setLinkedinProfile($quote->getBillingAddress()->getLinkedinProfile())->save();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        
            try {
                $order->getShippingAddress()->setLinkedinProfile($quote->getShippingAddress()->getLinkedinProfile())->save();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        
    }
}