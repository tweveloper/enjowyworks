<?php namespace EnjoyWorks\push\Firebase\VO;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Params.php
 * @project : Ew_Library
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/05/02     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
class ParamsVO
{
    private $to;
    private $data;
    private $notification;

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __construct(ParamToVO $to = null, ParamDataVO $data = null, ParamNotificationVO $notification = null)
    {
        if(!empty($to))$this->to = $to;
        if(!empty($data))$this->data = $data;
        if(!empty($notification))$this->notification = $notification;
        return $this;
    }

    public function __set($name, $value)
    {
        switch ($name){
            case 'to':
                if(is_object($value) && ParamToVO::class == get_class($value)){
                    $this->{$name} = $value;
                }
                break;
            case 'data':
                if(is_object($value) && ParamDataVO::class == get_class($value)){
                    $this->{$name} = (array)$value;
                }
                break;
            case 'notification':
                if(is_object($value) && ParamNotificationVO::class == get_class($value)){
                    $this->{$name} = $value;
                }
                break;
        }
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function __toString()
    {
        $res = "";
        $res .= "=============== 취소 내역 ===============================<br>";
        $res .= "DeviceID              : ".$this->to."<br>";
        $res .= "PushData              : ".$this->data."<br>";
        $res .= "Notification          : ".$this->notification."<br>";
        return $res;
    }

}