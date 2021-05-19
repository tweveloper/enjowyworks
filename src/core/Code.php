<?php namespace EnjoyWorks\core;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Code.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/14     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/


Class Code {

    use CodeFunction;
    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    function __get($name)
    {
        return $this->{$name};
    }

    //##################################################################################################################
    //##
    //## >> Method : Code
    //##
    //##################################################################################################################
    public static function all($type = RESULT_DATA_OBJECT)
    {
        $code = new self();
        $vars = get_object_vars($code);
        return $type == RESULT_DATA_OBJECT ? (object) $vars : $vars ;
    }

    public static function get($codeName)
    {
        $code = new self();
        $vars = get_object_vars($code);
        if(!isset($vars[$codeName]))return null;
        return (object)$vars[$codeName];
    }

    public static function val($codeName)
    {
        $code = new self();
        $vars = get_object_vars($code);
        if(!isset($vars[$codeName]))return null;
        return $vars[$codeName];
    }

    //##################################################################################################################
    //##
    //## >> Method : Code
    //##
    //##################################################################################################################
    /**
     * Code : is CodeName 유무
     * -----------------------------------------------------------------------------------------------------------------
     * @param $code_name
     * @return bool : 성공(true) , 실패(false)
     */
    public function isCodeName($code_name)
    {
        $variables = get_object_vars($this);
        return array_key_exists($code_name, $variables);
    }

    /**
     * Code : 입력한 키로 해당 키 바로 밑에 있는 데이터 가져오기
     * -----------------------------------------------------------------------------------------------------------------
     * Ex) 001001001 => [000][000][000] => [1차키][2차키][3차키]
     * Action
     *  getCodeKeyData('variableName', '001');
     * result
     *  Array(
     *      [1]=>[001001값]
     *      [2]=>[001002값]
     *      [3]=>[001003값]
     * )
     * @param $code_name
     * @param string $code_key : 코드 (단 "ALL" 입력시 모든 데이터)
     * @return array
     */
    public function getCodeKeyData($code_name, $code_key = ""){
        $res = array();
        $codeData = $this->{$code_name};
        foreach ($codeData as $key => $val){
            if(preg_match("/^".$code_key."\d{3}$/", $key) || strtoupper($code_key) == CODE_ALL_KEY){
                array_push($res, $val);
            }
        }
        return $res;
    }

    /**
     *  Code : 입력한 키로 해당 키 바로 밑에 있는 데이터 가져오기
     * -----------------------------------------------------------------------------------------------------------------
     * Ex) 001001001 => [000][000][000] => [1차키][2차키][3차키]
     * Action
     *  getCodeKeyData('variableName', '001');
     * result
     *  Array(
     *      [1]=>[001001값]
     *      [2]=>[001002값]
     *      [3]=>[001003값]
     * )
     * @param $code_name
     * @param string $code_key: 코드 (단 "ALL" 입력시 모든 데이터)
     * @return array|null
     */
    public function getCodeKeyRawData($code_name,  $code_key = "")
    {
        $res = array();
        $codeData = $this->{$code_name};
        if(empty($codeData)) return $res;
        foreach ($codeData as $key => $val){
            if(preg_match("/^".$code_key."\d{3}$/", $key) || strtolower($code_key) == "all") {
                preg_match_all("/^(\d{3})(\d{3})?(\d{3})?$/", $key, $match);
                $pattenData = array_map(function ($item) {
                    return $item[0];
                }, $match);
                $pattenData[4] = $val;
                array_push($res, $pattenData);
            }
        }
        return $res;
    }

    /**
     * Code : Depth 별 모든 Code Data 정보
     * -----------------------------------------------------------------------------------------------------------------
     * @param $code_name : 코드 변수 이름
     * @param $depth : Depth Name (1depth, 2depth, 3depth, etc) Default : NULL
     * @return array
     */
    public function getCodeDepthData($code_name, $depth = null)
    {
        $res = array();
        $codeData = $this->{$code_name};
        if(empty($codeData)) return $res;
        $res["1depth"] = array();
        $res["2depth"] = array();
        $res["3depth"] = array();
        $res["etc"] = array();

        foreach ($codeData as $key => $val){
            switch (strlen($key)){
                case 3:$res["1depth"][$key] = $val;break;
                case 6:$res["2depth"][$key] = $val;break;
                case 9:$res["3depth"][$key] = $val;break;
                default:$res["etc"][$key] = $val;break;
            }
        }
        return empty($depth) ? $res : $res[$depth];
    }

    /**
     * Code : 코드별 키값
     * -----------------------------------------------------------------------------------------------------------------
     * @param $code_name
     * @param $key
     * @param string $default
     * @return string
     */
    public function getCodeValue($code_name, $key, $default = "-")
    {
        $res = $default;
        $codeData = $this->{$code_name};
        if(empty($codeData)) return $res;
        if(empty($key)) return $res;
        if(!isset($codeData[$key])) return $res;
        return $codeData[$key];
    }
}