<?php
/**
 * @Package Module: Ascripts_Testcode
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Testcode\Controller\Index;

use Ascripts\Testcode\Logger\Logger;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class SaveCustomer extends Action
{
    /**
     * @const: AUTHOR
     */
    const AUTHOR = 'Ashfaq Ahmed';
    /**
     * @var $_customerId
     */
    protected $_customerId;
    /**
     * @var Filesystem
     */
    protected $_filesystem;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    /**
     * @var Logger
     */
    protected $_logger;
    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;
    /**
     * @var Customer
     */
    protected $_customer;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * SaveCustomer constructor.
     * @param Context $context
     * @param CustomerFactory $customerFactory
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     * @param PageFactory $pageFactory
     * @param Logger $logger
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        CustomerFactory $customerFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        PageFactory $pageFactory,
        Logger $logger,
        CustomerRepositoryInterface $customerRepository
    ) {

        /** assigning variables to Main Class Objects */

        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->_pageFactory = $pageFactory;

        /** @var _logger created own logger to log */
        $this->_logger = $logger;
        /** @var _customerFactory for loading customer */
        $this->_customerFactory = $customerFactory;
        /** @var _customerRepository for saving customer data */
        $this->_customerRepository = $customerRepository;

        return parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws InputMismatchException
     */
    public function execute()
    {
        /** asuming customer Id for testing base only, rather we can fetch customer Id from collection of customer object*/
        $this->_customerId = 1;
        /** @var loaded a customer by its ID $customer */
        $customer = $this->loadCustomer($this->_customerId);

        $params = $this->getRequest()->getParams();

        /** @var  $totalCustomers  getting total customers */
        $totalCustomers = count($this->getCustomers());

        /** @var $str */

        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);

        $str = '<h2>Test Tasks</h2>';
        $str .= '<ul><li><a href="' . $baseUrl . 'testcode/index/viewmodelrand/' . '"> Render View Model</li>';
        $str .= '<li><a href="' . $baseUrl . 'testcode/index/savecustomer/' . '"> Save Customer</a> </li></ul>';

        $str .= __('<center><h2 style="font-weight: bold;color:green">Task # 1: Renaming Customer via Controller</h2><h4>Author: ' . self::AUTHOR . '</h4><hr/></center>');
        $str .= __('<h3 style="font-weight: bold;color:red">1- Load a customer, change the name and save again. Test using a Controller. </h3>');
        $str .= __('<p>Total Customer: <b>(' . $totalCustomers . ')</b></p>');

        $firstName = isset($params['first_name']) ? $params['first_name'] : '';
        $lastName = isset($params['last_name']) ? $params['last_name'] : '';

        $str .= __('<form action="' . $baseUrl . 'testcode/index/savecustomer/" enctype="application/x-www-form-urlencoded" method="get" >');
        $str .= __('<input type="text" placeholder="First Name" value="' . $firstName . '" name="first_name" />');
        $str .= __('<input type="text" placeholder="Last Name" value="' . $lastName . '"  name="last_name" />');
        $str .= __('<button type="submit" >Change Customer Name</button>');
        $str .= __('</form>');

        $str .= __('<p>Before Saving Customer Name</p>');
        $str .= __('<h4>Customer Name: ' . $customer->getName() . '</h4>');
        $str .= __('<p>After Saving Customer Name</p>');

        /** saving customer */
        $currentCustomer = $this->_customerRepository->getById($this->_customerId);

        /** new parameters */
        $customerNewFirstName = 'Ashfaq';
        $customerNewLastName = 'Ahmed';
        if ($firstName) {
            $customerNewFirstName = $firstName;
        }
        if ($lastName) {
            $customerNewLastName = $lastName;
        }

        $currentCustomer->setFirstname($customerNewFirstName);
        $currentCustomer->setLastname($customerNewLastName);

        /** saving customer  */
        $this->_customerRepository->save($currentCustomer);
        $this->_logger->addLog('Customer New parametrs updated', Logger::LOG_INFO, [$customerNewFirstName, $customerNewLastName]);

        /** @var $customer loading new customer detail again to show updated customer */
        $customer = $this->loadCustomer($this->_customerId);
        $str .= __('<h4>Customer New Name: ' . $customer->getName() . '</h4>');
        echo $str;

        echo '<br/>Exiting only to show you the controller functionality without Loading layout';

        /** exiting it only becuase only to show you the functioaality with controller without layout */
        exit;
        //  return $this->_pageFactory->create();
    }

    /**
     * @param int|null $customrId
     * @return Customer|bool|Customer
     */
    public function loadCustomer(int $customrId = null)
    {
        /** if customer id is not valid parameter function will return false */
        if (!$customrId) {
            $this->_logger->addLog(__('Error occured: during loading customer, please provide valid customer Id'), Logger::LOG_CRITITAL, [$customrId]);
            return false;
        }
        /** @var  $customer */
        $customer = $this->_customerFactory->create();
        $this->_customer = $customer->load($customrId);
        /** adding logs to logger file */
        $this->_logger->addLog(__('Customer loaded'), Logger::LOG_INFO, $this->_customer->getData());

        return $this->_customer;
    }

    /**
     * @return AbstractDb|AbstractCollection|null
     */
    public function getCustomers()
    {
        /** loading all customers */
        /** @var  $customerFactory */
        $customerFactory = $this->_customerFactory->create();
        $customers = $customerFactory
            ->getCollection()
            ->addFieldToSelect('*')
            ->load();
        return $customers;
    }
}
