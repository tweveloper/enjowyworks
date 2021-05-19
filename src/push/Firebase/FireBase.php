<?php namespace EnjoyWorks\push\Firebase;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Firebase.php
 * @project : Ew_Library
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/05/02     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
use EnjoyWorks\push\Firebase\VO\HeadersVO;
use EnjoyWorks\push\Firebase\VO\ParamsVO;

class FireBase
{
    const API_URL = "https://fcm.googleapis.com/fcm/send";

    private $headers;
    private $apiKey;
    private $deviceToken;
    private $params;
    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    public function __construct($apiKey = null, $deviceToken = null)
    {
        if(!empty($apiKey)){
            $this->apiKey = $apiKey;
        }
        if(!empty($deviceToken)){
            $this->deviceToken = $deviceToken;
        }
    }

    public function __call( $method, $arguments )
    {
        if ( method_exists( $this, $method ) ) {
            return call_user_func_array( [ $this, $method ], $arguments );
        }
    }

    public static function __callStatic( $method, $arguments )
    {
        $instance = new static();
        if ( method_exists( $instance, $method ) ) {
            return call_user_func_array( [ $instance, $method ], $arguments );
        }
    }

    //##################################################################################################################
    //##
    //## >> Method : Params
    //##
    //##################################################################################################################
    /**
     * Params : SET Params VO
     * -----------------------------------------------------------------------------------------------------------------
     * @param ParamsVO $paramsVO
     */

    /**
     * @param ParamsVO $paramsVO
     * @return $this
     */
    private function setParamsVO(ParamsVO $paramsVO)
    {
        if(!empty($paramsVO)){
            $to = $paramsVO->to;
            $data = $paramsVO->data;
            $notification = $paramsVO->notification;

            $this->setParamsData(empty($to) ? null : $to->deviceID,
                empty($data) ? null : $data->toArray(),
                empty($notification) ? null : $notification->toArray());
        }
        return $this;
    }

    /**
     * Params : SET Params Data
     * -----------------------------------------------------------------------------------------------------------------
     * @param $device_token
     * @param null $param_data
     * @param null $param_notification
     * @return $this
     */
    private function setParamsData($device_token, $param_data = null, $param_notification = null)
    {
        $this->setParamTo($device_token);
        if(!empty($param_data)){
            $this->setParamData($param_data);
        }
        if(!empty($param_notification)){
            $this->setParamNotification($param_notification);
        }
        return $this;
    }

    /**
     * Params : SET Params To
     * -----------------------------------------------------------------------------------------------------------------
     * @param $to
     * @return $this
     */
    private function setParamTo($to)
    {
        if(empty($this->params) || !is_array($this->params))$this->params = array();
        $this->params['to'] = $to;
        return $this;
    }

    /**
     * Params : SET Params Data
     * -----------------------------------------------------------------------------------------------------------------
     * @param $data Array
     * @return $this
     */
    private function setParamData($data)
    {
        if(empty($this->params) || !is_array($this->params))$this->params = array();
        $this->params['data'] = $data;
        return $this;
    }

    /**
     * Params : SET Params Notification
     * -----------------------------------------------------------------------------------------------------------------
     * @param $notification Array
     * @return $this
     */
    private function setParamNotification($notification)
    {
        if(empty($this->params) || !is_array($this->params))$this->params = array();
        $this->params['notification'] = $notification;
        return $this;
    }

    //##################################################################################################################
    //##
    //## >> Function : Core
    //##
    //##################################################################################################################
    /**
     * Core : SET CURL Headers
     * -----------------------------------------------------------------------------------------------------------------
     * @param $headers
     * @return $this
     */
    private function setHeaders($headers)
    {
        if(is_array($headers)){
            $this->headers = $headers;
        }
        return $this;
    }

    /**
     * Core : SET PUSH Device Token
     * -----------------------------------------------------------------------------------------------------------------
     * @param $device_token
     * @return $this
     */
    private function setDeviceToken($device_token)
    {
        if(is_string($device_token)){
            $this->deviceToken = $device_token;
        }
        return $this;
    }

    /**
     * Core : SET API Key
     * -----------------------------------------------------------------------------------------------------------------
     * @param $key
     * @return $this
     */
    private function setApiKey($key)
    {
        if(is_string($key)){
            $this->apiKey = $key;
        }
        return $this;
    }

    /**
     * Core : SET Params
     * -----------------------------------------------------------------------------------------------------------------
     * @param $params
     * @return $this
     */
    private function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Core : Push Send
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $headers
     * @param string $params
     * @return mixed
     */
    private function send($headers = '', $params = '')
    {
        if(empty($headers)){
            if(empty($this->headers)){
                $headers = new HeadersVO();
                $headers = $headers->make();
            }else{
                $headers = $this->headers;
            }
        }
        if(empty($params))$params = $this->params;
        return $this->remoteSend($headers, json_encode($params));
    }

    /**
     * Core : Remote Send CURL
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $headers
     * @param string $params
     * @return mixed
     */
    private function remoteSend($headers = '', $params = '')
    {
        $send_url = self::API_URL;
        $host_info = explode("/", $send_url);
        $port = $host_info[0] == 'https:' ? 443 : 80;

        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, $port);
        curl_setopt($oCurl, CURLOPT_URL, $send_url);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        if(!empty($headers)){
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);
        }
        if(!empty($params)) {
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $params);
        }
        $ret = curl_exec($oCurl);
        if ($ret === FALSE) {
            die('Curl failed: ' . curl_error($ret));
        }
        curl_close($oCurl);
        return $ret;
    }

}