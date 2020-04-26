<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Api;

interface LineRepositoryInterface
{
    /**
     * @param $testId
     * @return mixed
     */
    public function getById($testId);

    /**
     * @param $scope
     * @return mixed
     */
    public function getLink($scope);

    /**
     * @param $scope
     * @return mixed
     */
    public function scope($scope);

    /**
     * @param $token
     * @return mixed
     */
    public function refresh($token);

    /**
     * @param $url
     * @param $header
     * @param $type
     * @param null $data
     * @return mixed
     */
    public function sendCURL($url, $header, $type, $data = null);
}
