<?php
namespace Magecomp\Smsfree\Observer;
 
use Magento\Framework\Event\ObserverInterface;

class Orderplaceafter implements ObserverInterface
{
    protected $objectManager;
	protected $helperdata;
    protected $helperapi;

	public function __construct(
	    \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magecomp\Smsfree\Helper\Data $helperdata,
        \Magecomp\Smsfree\Helper\Apicall $helperapi)
    {
        $this->objectManager = $objectManager;
        $this->helperdata = $helperdata;
        $this->helperapi = $helperapi;
    }
 
	public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try
        {
            if($this->helperdata->isEnabled() && $this->helperdata->isOrderPlaceForUserEnabled())
            {
                $order_id = $observer->getData('order_ids');
                $order = $this->objectManager->create('Magento\Sales\Model\Order')->load($order_id[0]);
                $order_information = $order->loadByIncrementId($order_id[0]);
                $this->helperapi->callApiUrl($order_information->getBillingAddress()->getTelephone(),$this->helperdata->getOrderPlaceTemplateForUser());
            }
		    return true;
	    }
	    catch(\Exception $e)
        {
		    return true;
	    }
   }
}