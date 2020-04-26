<?php
/**
 * @Package Module: Ascripts_Linelogin
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Linelogin\Model;

use Ascripts\Linelogin\Api\LineRepositoryInterface;
use Ascripts\Linelogin\Helper\Data;
use Ascripts\Linelogin\Logger\Logger;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class LineRepositoryModel extends AbstractModel implements LineRepositoryInterface
{

    /**
     * @var null Default Line Developer ClientID and ClientSecret
     */
    protected $_CLIENT_ID = null;
    /**
     * @var null
     */
    protected $_CLIENT_SECRET = null;

    /**
     * @var null  Default Callback redirect link
     */
    protected $_REDIRECT_URL = null;

    /**
     * @var bool
     */
    protected $_VERIFYHOST = false;

    /**
     * @var bool
     */
    protected $_VERIFYPEER = false;

    // API DEFAULTS
    /**
     * @var null
     */
    protected $_AUTH_URL = null;
    /**
     * @var null
     */
    protected $_PROFILE_URL = null;
    /**
     * @var null
     */
    protected $_REVOKE_URL = null;
    /**
     * @var null
     */
    protected $_TOKEN_URL = null;
    /**
     * @var null
     */
    protected $_VERIFYTOKEN_URL = null;

    /**
     * @var Logger
     */
    protected $_logger;
    /**
     * @var Data
     */
    protected $_dataHelper;
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var
     */
    protected $_header;

    /**
     * LineRepositoryModel constructor.
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param Session $customerSession
     * @param Logger $logger
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Session $customerSession,
        Logger $logger,
        Data $dataHelper,
        array $data = []
    ) {
        $this->_logger = $logger;
        $this->_dataHelper = $dataHelper;
        $this->_customerSession = $customerSession;

        /** @var header $header */
        $header = ['Content-Type: application/x-www-form-urlencoded'];
        $this->_header = $header;

        /** assigning URL Vars from config */
        $this->_assignVarsFromconfig();

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @function: _assignVarsFromconfig
     */
    private function _assignVarsFromconfig()
    {
        $this->_CLIENT_ID = $this->_dataHelper->getLineChannelId();
        $this->_CLIENT_SECRET = $this->_dataHelper->getLineChannelSecret();
        $this->_REDIRECT_URL = $this->_dataHelper->getLineCallbackUrl();
        $this->_AUTH_URL = $this->_dataHelper->getLineAuthUrl();
        $this->_PROFILE_URL = $this->_dataHelper->getLineProfileUrl();
        $this->_REVOKE_URL = $this->_dataHelper->getLineProvokeUrl();
        $this->_TOKEN_URL = $this->_dataHelper->getLineTokenUrl();
        $this->_VERIFYTOKEN_URL = $this->_dataHelper->getLineVerifyTokenUrl();
    }

    /**
     * @param $loginId
     */
    public function getById($loginId)
    {
        /** getLoginId */
    }

    /**
     * @param null $params
     * @return bool|string
     */
    public function processLogin($params = null){
        $link = $this->getLink('profile');
        $response = $this->sendCURL($link, $this->_header,'type');
        return $response;
    }

    /**
     * @param array $params
     * @return bool|string
     */
    public function getProfile($params=[]){
       $code = $params['code'];
       $state = $params['state'];

       $tokenResponse = json_decode($this->token($code, $state));

/** check if errors */
       if(count((array)$tokenResponse) > 0 && !isset($tokenResponse->error)){
            $access_token = $tokenResponse->access_token;
           $profile = $this->profile($access_token);
           return $profile;
       }else{
           return $tokenResponse;
       }

    }

    /**
     * @param $scope
     * @return string
     */
    public function getLink($scope)
    {
        $state = hash('sha256', microtime(true) . rand() . $_SERVER['REMOTE_ADDR']);
        $this->_customerSession->setState($state);
        $link = $this->_AUTH_URL . '?response_type=code&client_id=' . $this->_CLIENT_ID . '&redirect_uri=' . $this->_REDIRECT_URL . '&scope=' . $this->scope($scope) . '&state=' . $this->_customerSession->getState();
        return $link;
    }

    /**
     * @param $scope
     * @return string
     */
    public function scope($scope)
    {

        $list = [
            'open_id',
            'profile',
            'email'
        ];

        $ret = 'profile';
        $scope = decbin($scope);

        while (strlen($scope) < 3) {
            $scope = '0' . $scope;
        }

        $scope = strrev($scope);

        foreach ($list as $key => $value) {
            if ($scope[$key] == 1) {
                if (isset($ret)) {
                    $ret = $ret . '%20' . $value;
                } else {
                    $ret = $value;
                }
            }
        }

        return $ret;
    }

    /**
     * @param $token
     * @return bool|string
     */
    public function refresh($token)
    {
        $header = ['Content-Type: application/x-www-form-urlencoded'];
        $data = [
            "grant_type" => "refresh_token",
            "refresh_token" => $token,
            "client_id" => $this->_CLIENT_ID,
            "client_secret" => $this->_CLIENT_SECRET
        ];

        $response = $this->sendCURL($this->_TOKEN_URL, $header, 'POST', $data);

        return $response;
    }

    /**
     * @param $url
     * @param $header
     * @param $type
     * @param null $data
     * @return bool|string
     */
    public function sendCURL($url, $header, $type, $data = null)
    {
        $request = curl_init();
        $this->_logger->addInfo('Request started with params', [$url]);

        if ($header != null) {
            curl_setopt($request, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_SSL_VERIFYHOST, $this->_VERIFYHOST);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, $this->_VERIFYPEER);

        if (strtoupper($type) === 'POST') {
            curl_setopt($request, CURLOPT_POST, true);
            curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($request, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($request);
        $this->_logger->addInfo('Resonse we got ',  [$response]);

        return $response;
    }

    /**
     * @param $code
     * @param $state
     * @return bool|string
     */
    public function token($code, $state)
    {
        if ($this->_customerSession->getState() != $state) {
          //  return false;
        }

        $header = ['Content-Type: application/x-www-form-urlencoded'];
        $data = [
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $this->_REDIRECT_URL,
            "client_id" => $this->_CLIENT_ID,
            "client_secret" => $this->_CLIENT_SECRET
        ];

        $response = $this->sendCURL($this->_TOKEN_URL, $header, 'POST', $data);
        return $response;
    }

    /**
     * @param $token
     * @return bool|string
     */
    public function profile($token)
    {
        $header = ['Authorization: Bearer ' . $token];
        $response = $this->sendCURL($this->_PROFILE_URL, $header, 'GET');
        return $response;
    }

    /**
     * @param $token
     * @return bool|string
     */
    public function verify($token)
    {
        $url = $this->_VERIFYTOKEN_URL . '?access_token=' . $token;
        $response = $this->sendCURL($url, null, 'GET');
        return $response;
    }
}
