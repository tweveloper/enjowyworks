<?php namespace EnjoyWorks\push\Firebase\VO;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : ParamsDataVO.php
 * @project : Ew_Library
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/05/02     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
class HeadersVO
{
    private $headers = array();
    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __construct($headers = null)
    {
        if(!empty($headers))$this->headers = $headers;
        return $this;
    }

    public function __toString()
    {
        $res = "";
        $res .= "=============== Headers Data ===============================<br>";
        $res .= "Header                : ".json_encode($this->headers)."<br>";
        return $res;
    }

    //##################################################################################################################
    //##
    //## >> Method
    //##
    //##################################################################################################################
    /**
     * Core: API KEY
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $api_key
     * @return array
     */
    public function make($api_key = null){
        if(empty($api_key))$api_key = env('PUSH_FIREBASE_KEY');
        if(!empty($api_key)){
            $this->setApiKey($api_key);
        }
        $this->setHeaderData("Content-Type: application/json");
        return $this->headers;
    }

    //##################################################################################################################
    //##
    //## >> Method : Core
    //##
    //##################################################################################################################
    /**
     * Core: API KEY
     * -----------------------------------------------------------------------------------------------------------------
     * @param $api_key
     * @return $this
     */
    public function setApiKey($api_key)
    {
        foreach ($this->headers as $key => $value){
            if(preg_match('/^Authorization:key =.*/i', $value)){
                array_splice( $this->headers, $key, 1 );
            }
        }
        array_push($this->headers, sprintf("Authorization:key = %s", $api_key));
        return $this;
    }

    /**
     * Core: API KEY
     * -----------------------------------------------------------------------------------------------------------------
     * @param $header
     * @return $this
     */
    public function setHeaderData($header){
        if(in_array($header, $this->headers)){
            $key = array_search($header, $this->headers);
            array_splice( $array, $key, 1 );
        }
        array_push($this->headers, $header);
        return $this;
    }

}