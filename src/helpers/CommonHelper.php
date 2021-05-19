<?php
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : DebugHelper.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/14     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description

 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

//######################################################################################################################
//##
//## >> Function : Functions
//##
//######################################################################################################################


if(!function_exists('replaceByte')):

    /**
     * Function : Byte 용량 표시
     * -----------------------------------------------------------------------------------------------------------------
     * @param $byte
     * @return int|string
     */
    function replaceByte($byte){

    if ($byte >= 1000000000)return ($byte / 1000000000).number_format($byte, 2, ',', ' '); + ' GB';
    if ($byte >= 1000000)return ($byte / 1000000).number_format($byte, 2, ',', ' ')(2) + ' MB';
    return ($byte / 1000).toFixed(2) + ' KB';
}

endif;

if(!function_exists('randomNumber')):

    /**
     * Function : 임의숫자를 생성
     * -----------------------------------------------------------------------------------------------------------------
     * @param int $digit
     * @return string
     */
    function randomNumber($digit = 4){
        $res = "";
        for ($i = 0; $i < $digit; $i++) {
            if($i == 0){
                $res .= mt_rand(1, 9);
            }else{
                $res .= mt_rand(0, 9);
            }
        }
        return $res;
    }

endif;

if(!function_exists('randomAlphabetNumber')):
    /**
     * Function : 임의숫자+알파벳를 생성
     * -----------------------------------------------------------------------------------------------------------------
     * @param $length
     * @return string
     */
    function randomAlphabetNumber($length) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rendom_str = "";
        $loopNum = $length;
        while ($loopNum--) {
            $rendom_str .= $characters[mt_rand(0, strlen($characters))];
        }
        return $rendom_str;
    }
endif;

if(!function_exists('currentTimeMillis')):
    /**
     * Function : Current Time Millis
     * -----------------------------------------------------------------------------------------------------------------
     * @return float
     */
    function currentTimeMillis() {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float)$usec + (float)$sec) * 1000);
    }
endif;

if(!function_exists('stringPathExtension')):
    /**
     * Function : 문자 경로에서 확장자 추출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathStr
     * @return string
     */
    function stringPathExtension($pathStr) {
        $pattern = "/\.(bmp|gif|png|jpe?g|webp|xmt?m?l|pdf|text|html|css|js|midi|mpeg|webm|ogg|wav)$/i";
        if(preg_match($pattern, $pathStr, $matches) && count($matches) > 1){
            $res = $matches[1];
        }else if(preg_match("/\/.*\.(.*)$/i", $pathStr, $matches) && count($matches) > 1){
            $res = $matches[1];
        }else{
            $res = "";
        }
        return $res;
    }
endif;

if(!function_exists('stringPathFileName')):
    /**
     * Function : 문자 경로에서 확장자 추출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathStr
     * @return string
     */
    function stringPathFileName($pathStr) {
        if(preg_match("/.*\/(.*\..*)$/i", $pathStr, $matches) && count($matches) > 1){
            $res = $matches[1];
        }else{
            $res = "";
        }
        return $res;
    }
endif;

if(!function_exists('explodeEmpty')):
    /**
     * Function : Explode 함수의 결과 값중 비어있는 값을 제거하고 반환
     * -----------------------------------------------------------------------------------------------------------------
     * @param $delimiter
     * @param $string
     * @param int $limit
     * @return array
     */
    function explodeEmpty($delimiter, $string, $limit = PHP_INT_MAX)
    {
        $res = array();
        $explode = explode($delimiter, $string, $limit);
        foreach ($explode as $value){
            if(!empty($value)){
                array_push($res, $value);
            }
        }
        return $res;
    }
endif;