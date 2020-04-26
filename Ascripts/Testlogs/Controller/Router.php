<?php

namespace Ascripts\Testlogs\Controller;

use Ascripts\Testlogs\Logger\Logger;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\RouterList;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $_actionFactory;
    /**
     * @var ResponseInterface
     */
    protected $_response;
    /**
     * @var Logger
     */
    protected $_logger;
    /**
     * @var RouterList
     */
    protected $_routerList;
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Router constructor.
     * @param ActionFactory $actionFactory
     * @param ResponseInterface $response
     * @param Logger $logger
     * @param RouterList $routerList
     * @param RequestInterface $request
     */
    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        Logger $logger,
        RouterList $routerList,
        RequestInterface $request
    ) {
        $this->_actionFactory = $actionFactory;
        $this->_response = $response;
        $this->_logger = $logger;
        $this->_routerList = $routerList;
        $this->_request = $request;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|void
     */
    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');

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

        /** adding params and router to logger */
        $this->_logger->addLog('Current router is: ' . $classRouter, Logger::LOG_INFO);
    }
}
