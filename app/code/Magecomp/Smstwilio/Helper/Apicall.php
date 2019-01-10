<?php 
namespace Magecomp\Smstwilio\Helper;

class Apicall extends \Magento\Framework\App\Helper\AbstractHelper
{
	const XML_TWILIOSMS_ACCOUNTSID = 'smsfree/smsgatways/twiliosid';
	const XML_TWILIOSMS_AUTHTOKEN = 'smsfree/smsgatways/twiliotoken';
	const XML_TWILIOSMS_MOBILENUMBER = 'smsfree/smsgatways/twilionumber';

    protected $logger;

	public function __construct(\Magento\Framework\App\Helper\Context $context,
                                \Psr\Log\LoggerInterface $logger)
	{
        $this->logger = $logger;
		parent::__construct($context);
	}

    public function getTitle() {
        return __("Twilio");
    }

	public function getAccountsid()	{
		return $this->scopeConfig->getValue(
            self::XML_TWILIOSMS_ACCOUNTSID,
			 \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
	}

	public function getAuthtoken(){
		return $this->scopeConfig->getValue(
            self::XML_TWILIOSMS_AUTHTOKEN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
	}
	public function getMobileNumber(){
		return $this->scopeConfig->getValue(
            self::XML_TWILIOSMS_MOBILENUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
	}

    public function validateSmsConfig()
    {
        $twilioclassExist = class_exists('Twilio\Rest\Client');

        if(!$twilioclassExist) {
            $this->logger->error(__("Run 'composer require twilio/sdk' from CLI to use Twilio."));
        }

        return $twilioclassExist && $this->getAccountsid() && $this->getAuthtoken() && $this->getMobileNumber();
    }

    public function callApiUrl($mobilenumbers,$message)
    {
        try
        {
            $account_sid = $this->getAccountsid();
            $auth_token = $this->getAuthtoken();

            if (substr($mobilenumbers, 0, 1) !== '+') {
                $mobilenumbers = '+'.$mobilenumbers;
            }

            $client = new \Twilio\Rest\Client($account_sid, $auth_token);
            $returntwilio = $client->messages->create(
                $mobilenumbers,
                array(
                    'from' => $this->getMobileNumber(),
                    'body' => $message
                )
            );

            return true;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
}