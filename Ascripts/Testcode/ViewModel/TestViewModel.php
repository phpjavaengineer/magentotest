<?php
/**
 * @Package Module: Ascripts_Testcode
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Testcode\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class TestViewModel implements ArgumentInterface
{
    /**
     * TestViewModel constructor.
     */
    public function __construct()
    {
        /** Main construcor we can add further Class Injections and create Objects here */
    }

    /**
     * @return string
     */
    public function getStringFromViewModel()
    {
        $str = 'String: I am from View Model';
        return $str;
    }
}
