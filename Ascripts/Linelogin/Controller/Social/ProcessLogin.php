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
use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
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
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\Data\CustomerInterface;

class ProcessLogin extends Action
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
     * @var CustomerRepositoryInterface
     */
    private $_customerRepository;
    /**
     * @var EncryptorInterface
     */
    private $_encryptor;
    /**
     * @var CustomerFactory
     */
    private $_customerInterfaceFactory;

    /**
     * ProcessLogin constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param CollectionFactory $customersCollection
     * @param Session $customerSession
     * @param AccountRedirect $accountRedirect
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper $escaper
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
        LineRepositoryModel $lineRepositoryModel,
        CustomerRepositoryInterface $customerRepository,
        EncryptorInterface $encryptor,
        CustomerInterface $customerInterfaceFactory
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
        $this->_customerRepository = $customerRepository;
        $this->_encryptor = $encryptor;
        $this->_customerInterfaceFactory = $customerInterfaceFactory;

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
        $params = $this->getRequest()->getParams();
        $profile = $this->_lineRepositoryModel->getProfile($params);
        $profile = json_decode($profile);

        /** when valid data from Line Messenger */
        if (!isset($profile->error)) {
            $customerName = $profile->displayName;
            $customerDetail = explode(' ', $customerName);
            $firstName = $customerDetail[0];
            $lastName = $customerDetail[0];
            //Social Validation
            $lineData = $this->getRequest()->getParams();
            $lineData['client_email'] = $this->_session->getClientEmail();
            $lineData['firstname'] = $firstName;
            $lineData['lastname'] = $lastName;
            /** if there is valid response */

            if ($lineData) {
                $search = $this->_customersCollection->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('email', $lineData['client_email'])
                    ->getFirstItem();
                $customer_id = (int)$search->getId();

                if ($customer_id) {
                    $this->_session->loginById($customer_id);
                    return $this->handleredirect();
                } else {
                    $search = $this->_customersCollection->create()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('email', $lineData['client_email'])
                        ->getFirstItem();
                    $customer_id = (int)$search->getId();

                    if ($customer_id) {
                        $customer = $this->_customerFactory->create()->load($customer_id);
                        $customer->setData('email', $lineData['client_email']);

                        try {
                            $customer->save();
                            $this->_session->loginById($customer->getId());
                            $this->messageManager->addSuccess(__('Your %social account is now Linked with your account', ['social' => $sfid[1]]));
                            echo '<script>window.opener.location.reload(true);window.close();</script>';
                            exit;
                        } catch (Exception $e) {
                            $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));

                            echo '<script>window.opener.location.reload(true);window.close();</script>';
                            exit;
                        }
                    }
                }
            }
            //Generating random password
            $password = bin2hex(openssl_random_pseudo_bytes(10));
            // Get Website ID
            $websiteId = $this->_storeManager->getWebsite()->getWebsiteId();
            // Instantiate object (this is the most important part)
            $customer = $this->_customerInterfaceFactory;
            $customer->setWebsiteId($websiteId);
            $email = $lineData['client_email'];

            $customer->setEmail($email);

            $customer->setFirstName($firstName);
            $customer->setLastName($lastName);
          //  $customer->setPassword($password);

            try {
                // Save data

                $passwordHash = $this->_encryptor->getHash($password, true);
                $this->_customerRepository->save($customer, $passwordHash);

                $search = $this->_customersCollection->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('email', $lineData['client_email'])
                    ->getFirstItem();
                $customer_id = (int)$search->getId();
                $customer = $this->_customerFactory->create()->load($customer_id);

                $this->_eventManager->dispatch(
                    'customer_register_success',
                    ['account_controller' => $this, 'customer' => $customer]
                );
                $customer->sendNewAccountEmail();
                $this->_session->loginById($customer->getId());

                echo '<script>window.opener.location.reload(true);window.close();</script>';
                exit;
            } catch (StateException $e) {
                $url = $this->urlModel->getUrl('customer/account/forgotpassword');
                // @codingStandardsIgnoreStart
                $message = __(
                    'There is already an account with this email address. If you are sure that it is your email address, <a href="%1">click here</a> to get your password and access your account.',
                    $url
                );
                // @codingStandardsIgnoreEnd
                $this->messageManager->addError($message);

                echo '<script>window.opener.location.reload(true);window.close();</script>';
                exit;
            } catch (InputException $e) {
                $this->messageManager->addError($this->_escaper->escapeHtml($e->getMessage()));
                foreach ($e->getErrors() as $error) {
                    $this->messageManager->addError($this->_escaper->escapeHtml($error->getMessage()));
                }
                echo '<script>window.opener.location.reload(true);window.close();</script>';
                exit;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));
            }
            echo '<script>window.opener.location.reload(true);window.close();</script>';
            exit;
        } else {
            $this->messageManager->addError(__('You attempted to do a forbidden action!.'));
            echo '<script>window.opener.location.reload(true);window.close();</script>';
            exit;
            // return $resultRedirect->setPath('/');
        }
    }

    /**
     * @return Forward|Redirect
     * @throws InputException
     * @throws FailureToSendException
     */
    private function handleredirect()
    {
        if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
            $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
            $metadata->setPath('/');
            $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
        }
        $redirectUrl = $this->_accountRedirect->getRedirectCookie();

        if (!$this->getScopeConfig()->getValue('customer/startup/redirect_dashboard') && $redirectUrl) {
            $this->accountRedirect->clearRedirectCookie();
            $resultRedirect = $this->_resultRedirectFactory->create();
            // URL is checked to be internal in $this->_redirect->success()
            $resultRedirect->setUrl($this->_redirect->success($redirectUrl));
            return $resultRedirect;
        }

        return $this->_accountRedirect->getRedirect();
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
