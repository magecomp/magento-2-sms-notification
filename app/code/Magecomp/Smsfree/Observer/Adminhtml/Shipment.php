<?php
namespace Magecomp\Smsfree\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;

class Shipment implements ObserverInterface
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
            if($this->helperdata->isEnabled() && $this->helperdata->isShipmentEnabledForUser())
            {
                $shipment = $observer->getEvent()->getShipment();
                $order = $shipment->getOrder();
			    $mobilenumber = $order->getBillingAddress()->getTelephone();
                $this->helperapi->callApiUrl($mobilenumber,$this->helperdata->getShipmenTemplateForUser());
			}
		    return true;
	    }
	    catch(\Exception $e)
        {
		    return true;
	    }
    }
}