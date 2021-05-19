<?php namespace EnjoyWorks\sms\vo;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : SendMass.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class SendList extends SmsPropertyFun
{

    protected $data = array();
    protected $propertyArr = Array(
        'key',
        'userid',
        'page',
        'page_size',
        'start_date',
        'limit_day',
    );

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __set($name, $value)
    {
        if(!in_array($name, $this->propertyArr))throw new \Exception("잘못된 타입정보가 있습니다.");
        if($name == 'start_date')return $this->data[$name] =  substr($value, 0, 8);
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
     | page : 발신번호 Ex_) 2
     | page_size : 페이지당 출력갯수 Ex_) 30~500
     | start_date : 조회시작일자 Ex_) 20171227
     | limit_day : 조회마감일자 Ex_) 7;
     */
}