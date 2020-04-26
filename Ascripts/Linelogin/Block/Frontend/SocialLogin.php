<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Block\Frontend;

use Ascripts\Linelogin\Helper\Data;
use Ascripts\Linelogin\Logger\Logger;
use Ascripts\Linelogin\Model\LineRepositoryModel;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class SocialLogin extends Template
{

    /**
     * @var Data
     */
    protected $_hlp;
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var Logger
     */
    protected $_logger;
    /**
     * @var Magento\Framework\Data\Form\FormKey
     */
    protected $_formKey;
    protected $_lineRepositoryModel;

    /**
     * SocialLogin constructor.
     * @param Context $context
     * @param Data $helper
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        Session $customerSession,
        Logger $logger,
        FormKey $formKey,
        LineRepositoryModel $lineRepositoryModel,
        array $data = []
    ) {
        $this->_hlp = $helper;
        $this->_logger = $logger;
        $this->_formKey = $formKey;
        $this->_lineRepositoryModel = $lineRepositoryModel;

        $this->_customerSession = $customerSession;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getLineLoginLink()
    {
        $link = $this->_lineRepositoryModel->getLink('profile');
        return $link;
    }

    /**
     * Check wether the current user is Logged in or not
     * @return boolean
     */
    public function isUserLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }

    /**
     * @return Magento\Framework\Data\Form\FormKey|FormKey
     */
    public function getFormKey()
    {
        return $this->_formKey->getFormKey();
    }

    /**
     * Check if Line Login has been enabled by the administrator
     * @return boolean
     */
    public function isLineEnabled()
    {
        return $this->_hlp->isLineEnabled();
    }

    /**
     * @return mixed
     */
    public function getLineChannelId()
    {
        return $this->_hlp->getLineChannelId();
    }

    /**
     * @return mixed
     */
    public function getLineChannelSecret()
    {
        return $this->_hlp->getLineChannelSecret();
    }

    /**
     * @return mixed
     */
    public function getLineAssertionKey()
    {
        return $this->_hlp->getLineAssertionKey();
    }

    /**
     * @return mixed
     */
    public function getLineUserId()
    {
        return $this->_hlp->getLineUserId();
    }

    /**
     * @return mixed
     */
    public function getLineCallbackUrl()
    {
        return $this->_hlp->getLineCallbackUrl();
    }

    /**
     * @return mixed
     */
    public function getLineAuthUrl()
    {
        return $this->_hlp->getLineAuthUrl();
    }

    /**
     * @return mixed
     */
    public function getLineProfileUrl()
    {
        return $this->_hlp->getLineProfileUrl();
    }

    /**
     * @return mixed
     */
    public function getLineProvokeUrl()
    {
        return $this->_hlp->getLineProvokeUrl();
    }

    /**
     * @return mixed
     */
    public function getLineTokenUrl()
    {
        return $this->_hlp->getLineTokenUrl();
    }

    /**
     * @return mixed
     */
    public function getLineVerifyTokenUrl()
    {
        return $this->_hlp->getLineVerifyTokenUrl();
    }
}
