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
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class ViewModelRand extends Action
{
    /**
     * @const: AUTHOR
     */
    const AUTHOR = 'Ashfaq Ahmed';
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
     * ViewModelRand constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     * @param PageFactory $pageFactory
     * @param Logger $logger
     */
    public function __construct
    (
        Context $context,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        PageFactory $pageFactory,
        Logger $logger

    ) {

        /** assigning variables to Main Class Objects */

        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->_pageFactory = $pageFactory;

        /** @var _logger created own logger to log */
        $this->_logger = $logger;


        return parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
      $params = $this->getRequest()->getParams();

          return $this->_pageFactory->create();
    }


}
