<?php namespace EnjoyWorks\core;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Code.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/01/24     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
trait CodeFunction
{
    /**
     * Code Func : 코드 Depth 별 코드 값을 누적해서 반환
     * -----------------------------------------------------------------------------------------------------------------
     * ex) Code Str = 001001001
     *
     * $code[0] = '001';
     * $code[1] = '001';
     * $code[2] = '001';
     *
     * =>
     *
     * $res[0] = '001';
     * $res[1] = '001001';
     * $res[2] = '001001001';
     *
     * @param $codeStr
     * @return array|bool
     */
    function codeCumulate($codeStr){
        if(empty($codeStr))return false;
        preg_match_all('/[0-9]{3}/', $codeStr, $matches, PREG_SET_ORDER);
        $tempData = array_map( function($res){return $res[0];}, $matches);
        $tempStr = "";
        $resData = array();
        foreach ($tempData as $val){
            $tempStr .= $val;
            array_push($resData, $tempStr);
        }
        return $resData;
    }
}