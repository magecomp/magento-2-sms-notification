<?php 
namespace Magecomp\Smsbulksms\Helper;

class Apicall extends \Magento\Framework\App\Helper\AbstractHelper
{
	const XML_BULKSMS_API_USERNAME = 'smsfree/smsgatways/bulksmsusername';
	const XML_BULKSMS_API_PASSWORD = 'smsfree/smsgatways/bulksmspassword';
	const XML_BULKSMS_API_URL = 'smsfree/smsgatways/bulksmsapiurl';

	public function __construct(\Magento\Framework\App\Helper\Context $context)
	{
		parent::__construct($context);
	}

    public function getTitle() {
        return __("Bulksms");
    }

    public function getApiUsername(){
        return $this->scopeConfig->getValue(
            self::XML_BULKSMS_API_USERNAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getApiPassword(){
        return $this->scopeConfig->getValue(
            self::XML_BULKSMS_API_PASSWORD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

	public function getApiUrl()	{
		return $this->scopeConfig->getValue(
            self::XML_BULKSMS_API_URL,
			 \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
	}

    public function validateSmsConfig()
    {
        return $this->getApiUsername() && $this->getApiPassword() && $this->getApiUrl();
    }

    public function callApiUrl($mobilenumbers,$message)
    {

        $url = $this->getApiUrl();
        $user = $this->getApiUsername();
        $password = $this->getApiPassword();

        $ch = curl_init();
        if (!$ch){
            die("Couldn't initialize a cURL handle");
        }
        $ret = curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,
            "username=$user&password=$password&message=$message&msisdn=$mobilenumbers");
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curlresponse = curl_exec($ch); // execute

        return $curlresponse;
    }
}