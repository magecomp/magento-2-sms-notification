<?php 
namespace Magecomp\Smsmsg91\Helper;

class Apicall extends \Magento\Framework\App\Helper\AbstractHelper
{
	const XML_MSG91_API_SENDERID = 'smsfree/smsgatways/msg91senderid';
    const XML_MSG91_API_AUTHKEY = 'smsfree/smsgatways/msg91authkey';
	const XML_MSG91_API_URL = 'smsfree/smsgatways/msg91apiurl';

	public function __construct(\Magento\Framework\App\Helper\Context $context)
	{
		parent::__construct($context);
	}

    public function getTitle() {
        return __("Msg91");
    }

    public function getApiSenderId(){
        return $this->scopeConfig->getValue(
            self::XML_MSG91_API_SENDERID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAuthKey()	{
        return $this->scopeConfig->getValue(
            self::XML_MSG91_API_AUTHKEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

	public function getApiUrl()	{
		return $this->scopeConfig->getValue(
            self::XML_MSG91_API_URL,
			 \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
	}

	public function validateSmsConfig()
    {
        return $this->getApiUrl() && $this->getAuthKey() && $this->getApiSenderId();
    }
	
	public function callApiUrl($mobilenumbers,$message)
	{
		$url = $this->getApiUrl();
		$authkey = $this->getAuthKey();
		$senderid = $this->getApiSenderId();

		$ch = curl_init();
		if (!$ch)
		{
			return "Couldn't initialize a cURL handle";
		}
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt ($ch, CURLOPT_POSTFIELDS,
		"authkey=$authkey&mobiles=$mobilenumbers&message=$message&sender=$senderid&route=4&country=0");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlresponse = curl_exec($ch); // execute
				
		return $curlresponse;
	}
}