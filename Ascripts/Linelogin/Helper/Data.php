<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Helper;

use Ascripts\Linelogin\Logger\Logger;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    protected $_logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Logger $logger
    ) {
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;

        parent::__construct($context);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $this->getStore()->getId()
        );
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * Check if Line Login has been enabled by the administrator
     * @return boolean
     */
    public function isLineEnabled()
    {
        return $this->getConfigValue('ascripts_linelogin/line/enabled');
    }

    /**
     * @return mixed
     */
    public function getLineChannelId()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_channel_id');
    }

    /**
     * @return mixed
     */
    public function getLineChannelSecret()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_channel_secret');
    }

    /**
     * @return mixed
     */
    public function getLineAssertionKey()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_assertion_key');
    }

    /**
     * @return mixed
     */
    public function getLineUserId()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_user_id');
    }

    /**
     * @return mixed
     */
    public function getLineCallbackUrl()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_callback_url');
    }

    /**
     * @return mixed
     */
    public function getLineAuthUrl()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_auth_url');
    }

    /**
     * @return mixed
     */
    public function getLineProfileUrl()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_profile_url');
    }

    /**
     * @return mixed
     */
    public function getLineProvokeUrl()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_provoke_url');
    }

    /**
     * @return mixed
     */
    public function getLineTokenUrl()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_token_url');
    }

    /**
     * @return mixed
     */
    public function getLineVerifyTokenUrl()
    {
        return $this->getConfigValue('ascripts_linelogin/line/api_verifytoken_url');
    }

}
