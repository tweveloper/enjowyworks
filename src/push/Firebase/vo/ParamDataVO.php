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
class ParamDataVO
{
    private $title;
    private $body;
    private $url;

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __construct($title = null, $body = null, $url = null)
    {
        if(!empty($title))$this->title = $title;
        if(!empty($body))$this->body = $body;
        if(!empty($url))$this->url = $url;
        return $this;
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function __toString()
    {
        $res = "";
        $res .= "=============== Param Data ===============================<br>";
        $res .= "Title                 : ".$this->title."<br>";
        $res .= "Body                  : ".$this->body."<br>";
        $res .= "Url                   : ".$this->url."<br>";
        return $res;
    }

    //##################################################################################################################
    //##
    //## >> Method
    //##
    //##################################################################################################################
    public function toArray()
    {
        $res = array();
        if(!empty($this->title))$res['title'] = $this->title;
        if(!empty($this->body))$res['body'] = $this->body;
        if(!empty($this->url))$res['url'] = $this->url;
        return $res;
    }
}