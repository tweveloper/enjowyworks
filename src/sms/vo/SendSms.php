<?php namespace EnjoyWorks\sms\vo;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : SendSms.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class SendSms extends SmsPropertyFun
{

    protected $data = array();
    protected $propertyArr = Array(
        'key',
        'userid',
        'msg',
        'receiver',
        'destination',
        'sender',
        'rdate',
        'rtime',
        'testmode_yn',
        'title',
        'image',
    );

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __set($name, $value)
    {
        if(!in_array($name, $this->propertyArr))throw new \Exception("잘못된 타입정보가 있습니다.");
        if($name == 'msg')return $this->data[$name] = stripslashes($value);
        if($name == 'receiver')return $this->data[$name] = str_replace("-","",$value);
        if($name == 'destination')return $this->data[$name] = str_replace("-","",$value);
        if($name == 'sender')return $this->data[$name] = str_replace("-","",$value);
        if($name == 'rdate')return $this->data[$name] =  substr($value, 0, 8);
        if($name == 'rtime')return $this->data[$name] =  substr($value, 0, 4);
        if($name == 'image'){
            if(!empty($value)) {
                if(file_exists($value)) {
                    $tmpFile = explode('/',$value);
                    $str_filename = $tmpFile[sizeof($tmpFile)-1];
                    $tmp_filetype = 'image/jpeg';
                    return $this->data[$name] = '@'.$value.';filename='.$str_filename. ';type='.$tmp_filetype;
                }
            }
        }
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
            && !empty($this->data['sender'])
            && !empty($this->data['receiver'])
            && !empty($this->data['msg'])
            ;
    }

    //##################################################################################################################
    //##
    //## >> Description
    //##
    //##################################################################################################################

    /*
     |------------------------------------------------------------------------------------------------------------------
     | SendSMS
     |------------------------------------------------------------------------------------------------------------------
     | key : API 인증용 API Ex_) 'DDF56JI564ERERF';
     | userid : 사용자ID Ex_) 'testID';
     | msg : 문자 메세지 Ex_) '%고객명%님. 안녕하세요. API TEST SEND';
     | receiver : 수신번호 Ex_) '01111111111,01111111112'
     | destination : 수신정보 Ex_) '01111111111|담당자,01111111112|홍길동' :: 수신인 %고객명% 치환
     | sender : 발신번호 Ex_) '01111111111'
     | rdate : 예약 일자 Ex_) 20161004
     | rtime : 예약 시간 Ex_) 1930
     | testmode_yn : 테스트 상태 Ex_) Y,N  Y : (실제문자 전송X , 자동취소(환불) 처리)
     | title : LMS, MMS 제목 Ex_) ''
     | image : MMS 이미지 Ex_) '/tmp/pic_57f358af08cf7_sms_.jpg'
     */
}