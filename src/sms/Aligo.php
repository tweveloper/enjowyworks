<?php namespace EnjoyWorks\sms;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Aligo.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

use EnjoyWorks\core\ResultFunction;
use EnjoyWorks\sms\vo\RemainCount;
use EnjoyWorks\sms\vo\ResultList;
use EnjoyWorks\sms\vo\SendMass;
use EnjoyWorks\sms\vo\SendSms;

/* 메세지 타입 : 단문 */
define("MESSAGE_TYPE_SMS", "SMS");
/* 메세지 타입 : 장문 */
define("MESSAGE_TYPE_LMS", "LMS");
/* 메세지 타입 : 그림문자 */
define("MESSAGE_TYPE_MMS", "MMS");

class Aligo
{
    use ResultFunction;
    const API_ALIGO_URL = "https://apis.aligo.in/";
    const API_SEND_SMS  = Aligo::API_ALIGO_URL."send/";
    const API_SEND_MASS  = Aligo::API_ALIGO_URL."send_mass/";
    const API_SEND_LIST  = Aligo::API_ALIGO_URL."list/";
    const API_RESULT_LIST  = Aligo::API_ALIGO_URL."sms_list/";
    const API_REMAIN_COUNT  = Aligo::API_ALIGO_URL."remain/";
    const API_RESERVE_CANCEL  = Aligo::API_ALIGO_URL."cancel/";

    private $userID;
    private $apiKey;

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    public function __construct($user_id = null, $api_key = null)
    {
        if(!empty($user_id)){
            $this->userID = $user_id;
        }else if(function_exists('env') && !empty(env("ALIGO_USER_ID"))){
            $this->userID = env("ALIGO_USER_ID");
        }
        if(!empty($api_key)){
            $this->apiKey = $api_key;
        }else if(function_exists('env') && !empty(env("ALIGO_API_KEY"))){
            $this->apiKey = env("ALIGO_API_KEY");
        }
    }

    //##################################################################################################################
    //##
    //## >> Method : Auth
    //##
    //##################################################################################################################
    /**
     * Auth : API AUTH KEY
     * -----------------------------------------------------------------------------------------------------------------
     * @param $user_id: Aligo Site ID
     * @param $api_key: API KEY
     */
    public function setAuthKey($user_id, $api_key)
    {
       $this->userID = $user_id;
       $this->apiKey = $api_key;
    }

    //##################################################################################################################
    //##
    //## >> Method : Remote
    //##
    //##################################################################################################################
    /**
     * Remote : 문자 보내기
     * -----------------------------------------------------------------------------------------------------------------
     * @param SendSms $sms
     * @return array
     * @throws \Exception
     */
    public function sendSms(SendSms $sms){
        if(!empty($this->apiKey))$sms->key = $this->apiKey;
        if(!empty($this->userID))$sms->userid = $this->userID;
        if($sms->isEmpty())throw  new \Exception("SMS 정보가 비어 있습니다.");
        if(!$sms->isValidation())throw  new \Exception("필수 정보가 비어 있습니다.");
        $result = $this->remoteSend(self::API_SEND_SMS, $sms->toArray());
        if($result->result_code !== '1')throw  new \Exception($result->message);
        return $this->returnData($result);
    }

    /**
     * Remote : 대량 문자 보내기
     * -----------------------------------------------------------------------------------------------------------------
     * @param SendMass $sms
     * @return array
     * @throws \Exception
     */
    public function sendMass(SendMass $sms){
        if(!empty($this->apiKey))$sms->key = $this->apiKey;
        if(!empty($this->userID))$sms->userid = $this->userID;
        if($sms->isEmpty())throw  new \Exception("SMS 정보가 비어 있습니다.");
        if(!$sms->isValidation())throw  new \Exception("필수 정보가 비어 있습니다.");
        $result = $this->remoteSend(self::API_SEND_MASS, $sms->toArray());
        if($result->result_code !== '1')throw  new \Exception($result->message);
        return $this->returnData($result);
    }

    /**
     * Remote : 전송 내역 조회
     * -----------------------------------------------------------------------------------------------------------------
     * @param SendList $sms
     * @return array
     * @throws \Exception
     */
    public function sendList(SendList $sms){
        if(!empty($this->apiKey))$sms->key = $this->apiKey;
        if(!empty($this->userID))$sms->userid = $this->userID;
        if($sms->isEmpty())throw  new \Exception("SMS 정보가 비어 있습니다.");
        if(!$sms->isValidation())throw  new \Exception("필수 정보가 비어 있습니다.");
        $result = $this->remoteSend(self::API_SEND_LIST, $sms->toArray());
        if($result->result_code !== '1')throw  new \Exception($result->message);
        return $this->returnData($result);
    }

    /**
     * Remote : 전송결과조회(상세)
     * -----------------------------------------------------------------------------------------------------------------
     * @param ResultList $sms
     * @return array
     * @throws \Exception
     */
    public function resultList(ResultList $sms){
        if(!empty($this->apiKey))$sms->key = $this->apiKey;
        if(!empty($this->userID))$sms->userid = $this->userID;
        if($sms->isEmpty())throw  new \Exception("SMS 정보가 비어 있습니다.");
        if(!$sms->isValidation())throw  new \Exception("필수 정보가 비어 있습니다.");
        $result = $this->remoteSend(self::API_RESULT_LIST, $sms->toArray());
        if($result->result_code !== '1')throw  new \Exception($result->message);
        return $this->returnData($result);
    }

    /**
     * Remote : 발송가능건수
     * -----------------------------------------------------------------------------------------------------------------
     * @param RemainCount $sms
     * @return array
     * @throws \Exception
     */
    public function remainCount(RemainCount $sms){
        if(!empty($this->apiKey))$sms->key = $this->apiKey;
        if(!empty($this->userID))$sms->userid = $this->userID;
        if($sms->isEmpty())throw  new \Exception("SMS 정보가 비어 있습니다.");
        if(!$sms->isValidation())throw  new \Exception("필수 정보가 비어 있습니다.");
        $result = $this->remoteSend(self::API_REMAIN_COUNT, $sms->toArray());
        if($result->result_code !== '1')throw  new \Exception($result->message);
        return $this->returnData($result);
    }

    /**
     * Remote : 예약문자 취소
     * -----------------------------------------------------------------------------------------------------------------
     * @param ReserveCancel $sms
     * @return array
     * @throws \Exception
     */
    public function reserveCancel(ReserveCancel $sms){
        if(!empty($this->apiKey))$sms->key = $this->apiKey;
        if(!empty($this->userID))$sms->userid = $this->userID;
        if($sms->isEmpty())throw  new \Exception("SMS 정보가 비어 있습니다.");
        if(!$sms->isValidation())throw  new \Exception("필수 정보가 비어 있습니다.");
        $result = $this->remoteSend(self::API_RESERVE_CANCEL, $sms->toArray());
        if($result->result_code !== '1')throw  new \Exception($result->message);
        return $this->returnData($result);
    }

    //##################################################################################################################
    //##
    //## >> Function : Core
    //##
    //##################################################################################################################
    /**
     * Core : Remote Send CURL
     * -----------------------------------------------------------------------------------------------------------------
     * @param $send_url
     * @param $params = Array();
     * @return mixed: JSON
     */
    private function remoteSend($send_url, $params){
        $host_info = explode("/", $send_url);
        $port = $host_info[0] == 'https:' ? 443 : 80;

        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, $port);
        curl_setopt($oCurl, CURLOPT_URL, $send_url);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ret = curl_exec($oCurl);
        curl_close($oCurl);

        return json_decode($ret);
    }

}