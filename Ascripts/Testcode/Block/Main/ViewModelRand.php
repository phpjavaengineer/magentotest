<?php
/**
 * @Package Module: Ascripts_Testcode
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Testcode\Block\Main;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class ViewModelRand extends Template
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var ProductFactory
     */
    protected $_productModel;

    /**
     * ViewModelRand constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ProductFactory $productModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ProductFactory $productModel,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_productModel = $productModel;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getStringFromBlock(){
        $str = 'String I am from Block';
        return $str;
    }
}
