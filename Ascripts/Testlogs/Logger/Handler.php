<?php
/**
 * @Package Module: Ascripts_Testlogs
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Testlogs\Logger;

use Magento\Framework\Logger\Handler\Base;

class Handler extends Base
{
	/**
	 * @var string
	 */
	protected $fileName = '/var/log/routerlogs.log';

	/**
	 * @var int
	 */
	protected $loggerType = Logger::DEBUG;
}