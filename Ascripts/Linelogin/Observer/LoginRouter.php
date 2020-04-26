<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Observer;

use Ascripts\Linelogin\Logger\Logger;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterList;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Page\Config;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;

class LoginRouter implements ObserverInterface
{
    /**
     * @var UrlFactory
     */
    protected $_urlFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var FilterManager
     */
    protected $_filter;
    /**
     * @var SidResolverInterface
     */
    protected $_sidResolver;
    /**
     * @var ActionFactory
     */
    protected $_actionFactory;
    /**
     * @var UrlFinderInterface
     */
    protected $_urlFinder;
    /**
     * @var SessionManagerInterface
     */
    protected $_sessionManager;
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var Config
     */
    protected $_pageConfig;
    /**
     * @var RouterList
     */
    protected $_routerList;
    /**
     * @var Logger
     */
    protected $_logger;
    /**
     * @var Session
     */
    protected $_session;

    /**
     * Redirect constructor.
     * @param UrlFactory $urlFactory
     * @param StoreManagerInterface $storeManager
     * @param FilterManager $filter
     * @param SidResolverInterface $sidResolver
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(
        UrlFactory $urlFactory,
        StoreManagerInterface $storeManager,
        FilterManager $filter,
        SidResolverInterface $sidResolver,
        SessionManagerInterface $sessionManager,
        Session $customerSession,
        UrlFinderInterface $urlFinder,
        ActionFactory $actionFactory,
        RequestInterface $request,
        Config $pageConfig,
        RouterList $routerList,
        Logger $logger
    ) {
        $this->_urlFactory = $urlFactory;
        $this->_storeManager = $storeManager;
        $this->_urlFinder = $urlFinder;
        $this->_request = $request;
        $this->_actionFactory = $actionFactory;
        $this->_pageConfig = $pageConfig;
        $this->_logger = $logger;
        $this->_session = $customerSession;

        $this->_routerList = $routerList;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        /** @var fetching parameters $currentRouter */
        $currentRouter = $this->_routerList->current()->match($this->_request);
        /** @var  $moduleName */
        $moduleName = $this->_request->getModuleName();
        /** @var  $actionName */
        $actionName = $this->_request->getActionName();
        /** @var  $routeName */
        $routeName = $this->_request->getRouteName();

        /** @var  $classRouter */
        $classRouter = get_class($currentRouter);

        /** if alrady set email clearing it */

        if ($this->_session->isLoggedIn() && $this->_session->getClientEmail()) {
            $this->_session->unsClientEmail();
            echo '<script>window.opener.location.reload(true);window.close();</script>';
            exit;
        }


    }
}
