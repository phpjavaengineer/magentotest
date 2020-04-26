<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Logger;

use Magento\Framework\Logger\Handler\Base;


class Handler extends Base
{
	/**
	 * @var string
	 */
	protected $fileName = '/var/log/linelogin.log';

	/**
	 * @var int
	 */
	protected $loggerType = Logger::DEBUG;
}