<?php

namespace Ascripts\Testlogs\Plugin;

use Magento\Framework\App\RequestInterface;

class ProductPlugin
{
    /*
    public function beforeSetName(\Magento\Catalog\Model\Product $subject, $name)
    {
        // logging to test override
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug('Model Override Test before');

        return $name;
    }

    public function afterGetName(\Magento\Catalog\Model\Product $subject, $result)
    {
        // logging to test override
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug('Model Override Test after');

        return $result;
    }
*/
    public  function afterMatch(RequestInterface $request){
var_dump('router');
    }
}
?>