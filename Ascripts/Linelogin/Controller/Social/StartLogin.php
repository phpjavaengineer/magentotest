<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Controller\Social;

use Ascripts\Linelogin\Logger\Logger;
use Ascripts\Linelogin\Model\LineRepositoryModel;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;
use Magento\Framework\View\Result\Layout;
use Magento\Store\Model\StoreManagerInterface;

class StartLogin extends Action
{

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;
    /**
     * @var CollectionFactory
     */
    protected $_customersCollection;
    /**
     * @var Session
     */
    protected $_session;
    /**
     * @var Escaper
     */
    protected $_escaper;
    /**
     * @var AccountRedirect
     */
    protected $_accountRedirect;
    /**
     * @var Logger
     */
    protected $_logger;
    /**
     * @var LineRepositoryModel
     */
    protected $_lineRepositoryModel;
    /**
     * @var $_cookieMetadataFactory
     */
    private $_cookieMetadataFactory;
    /**
     * @var $_cookieMetadataManager
     */
    private $_cookieMetadataManager;

    /**
     * StartLogin constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param CollectionFactory $customersCollection
     * @param Session $customerSession
     * @param AccountRedirect $accountRedirect
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper $escaper
     * @param PhpCookieManager $phpCookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param Logger $logger
     * @param LineRepositoryModel $lineRepositoryModel
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        CollectionFactory $customersCollection,
        Session $customerSession,
        AccountRedirect $accountRedirect,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper,
        PhpCookieManager $phpCookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        Logger $logger,
        LineRepositoryModel $lineRepositoryModel
    ) {
        $this->_logger = $logger;
        $this->_storeManager = $storeManager;
        $this->_customerFactory = $customerFactory;
        $this->_customersCollection = $customersCollection;
        $this->_session = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->_accountRedirect = $accountRedirect;
        $this->_cookieMetadataManager = $phpCookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_lineRepositoryModel = $lineRepositoryModel;

        /** parent construct method */
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Forward|Redirect|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->_request->getParams();

        /** if alrady set email clearing it */
        if ($this->_session->getClientEmail() == $params['client_email']) {
            $this->_session->unsClientEmail();
        }
        $this->_session->setClientEmail($params['client_email']);
        $this->returnResponse();
    }

    /**
     * @return ResultInterface|Layout
     */
    private function returnResponse()
    {
        $response = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $response->setHeader('Content-type', 'text/plain');
        $email = $this->getRequest()->getParam('client_email');
        $csrf = $this->getRequest()->getParam('csrf');

        $response->setContents(
            json_encode(
                [
                    'client_email' => $email,
                    'csrf' => $csrf,
                ]
            )
        );
        return $response;
    }

    /**
     * @return PhpCookieManager
     */
    private function getCookieManager()
    {
        return $this->_cookieMetadataManager;
    }

    /**
     * @return CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        return $this->_cookieMetadataFactory;
    }

    /**
     * @return ScopeConfigInterface
     */
    private function getScopeConfig()
    {
        return $this->_scopeConfig;
    }
}
