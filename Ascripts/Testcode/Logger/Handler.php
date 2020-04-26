<?php
/**
 * @Package Module: Ascripts_Testcode
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Testcode\Logger;

use Magento\Framework\Logger\Handler\Base;

class Handler extends Base
{
	/**
	 * @var string
	 */
	protected $fileName = '/var/log/testcode.log';

	/**
	 * @var int
	 */
	protected $loggerType = Logger::DEBUG;
}