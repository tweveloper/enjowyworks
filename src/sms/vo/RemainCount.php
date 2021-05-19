<?php namespace EnjoyWorks\sms\vo;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : RemainCount.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class RemainCount extends SmsPropertyFun
{

    protected $data = array();
    protected $propertyArr = Array(
        'key',
        'userid',
    );

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __set($name, $value)
    {
        if(!in_array($name, $this->propertyArr))throw new \Exception("잘못된 타입정보가 있습니다.");
        return $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if(!array_key_exists($name, $this->data))throw new \Exception("타입정보가 있습니다.");
        return $this->data[$name];
    }

    function __isset($name)
    {
        if(!array_key_exists($name, $this->data))throw new \Exception("타입정보가 있습니다.");
        return isset($this->data[$name]);
    }

    function __unset($name)
    {
        if(!array_key_exists($name, $this->data))throw new \Exception("타입정보가 있습니다.");
        unset($this->data[$name]);
    }


    //##################################################################################################################
    //##
    //## >> Function
    //##
    //##################################################################################################################

    public function toArray()
    {
        return $this->data;
    }

    public function isEmpty(){
        return empty($this->data);
    }

    public function isValidation(){
        return !empty($this->data['key'])
            && !empty($this->data['userid'])
            ;
    }

    //##################################################################################################################
    //##
    //## >> Description
    //##
    //##################################################################################################################

    /*
     |------------------------------------------------------------------------------------------------------------------
     | SendMass (최대 500개)
     |------------------------------------------------------------------------------------------------------------------
     | key : API 인증용 API Ex_) 'DDF56JI564ERERF';
     | userid : 사용자ID Ex_) 'testID';
     */
}