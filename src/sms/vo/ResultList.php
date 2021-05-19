<?php namespace EnjoyWorks\sms\vo;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : ResultList.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class ResultList extends SmsPropertyFun
{

    protected $data = array();
    protected $propertyArr = Array(
        'key',
        'userid',
        'mid',
        'page',
        'page_size',
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
            && !empty($this->data['mid'])
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
     | mid : 메세지 고유ID Ex_) 2
     | page : 페이지번호 Ex_) 2
     | page_size : 페이지당 출력갯수 Ex_) 30~500
     */
}