<?php namespace EnjoyWorks\push\Firebase;
use EnjoyWorks\push\Firebase\VO\ParamDataVO;
use EnjoyWorks\push\Firebase\VO\ParamNotificationVO;
use EnjoyWorks\push\Firebase\VO\ParamsVO;
use EnjoyWorks\push\Firebase\VO\ParamToVO;

/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : FirebaseFunc.php
 * @project : Pro_Mamma
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/05/02     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
class FireBaseFunc
{
    private $fireBase;

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    public function __construct()
    {
        if(empty($this->fireBase))$this->fireBase = new FireBase();
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
    //## >> Method : SendPush
    //##
    //##################################################################################################################
    /**
     * SendPush : Send Push
     * -----------------------------------------------------------------------------------------------------------------
     * @param $user_device_key
     * @param $title
     * @param string $body
     * @param string $url
     * @return mixed
     */
    private function sendPush($user_device_key, $title, $body = '', $url = '')
    {
        $to = new ParamToVO(preg_replace("/^(iOS:|ANDROID:)/", "", $user_device_key));
        $data = new ParamDataVO($title, empty($body) ? $title : $body, $url);
        if(preg_match("/^iOS:/", $user_device_key)){
            $notification = new ParamNotificationVO($title, empty($body) ? $title : $body, $url);
        }else{
            $notification = null;
        }
        $params = new ParamsVO($to, $data, $notification);
        $res = $this->fireBase->setParamsVO($params)->send();
        return json_decode($res);
    }

    /**
     * SendPush : CallBack Push
     * -----------------------------------------------------------------------------------------------------------------
     * @param $callback
     * @param $user_device_key
     * @param $title
     * @param string $body
     * @param string $url
     */
    private function sendPushCallBack($callback, $user_device_key, $title, $body = '', $url = '')
    {
        $res = $this->sendPush($user_device_key, $title, $body, $url);
        $callback($res);
    }
}