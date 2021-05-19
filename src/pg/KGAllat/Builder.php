<?php namespace EnjoyWorks\pg\KGAllat;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Builder.php
 * @project : Ew_Library
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/04/10     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
class Builder
{
    private $uri = [];

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __call( $method, $arguments )
    {
        if ( method_exists( $this, $method ) ) {
            return call_user_func_array( [ $this, $method ], $arguments );
        }
    }

    public static function __callStatic( $method, $arguments )
    {
        $instance = new static();
        if ( method_exists( $instance, $method ) ) {
            return call_user_func_array( [ $instance, $method ], $arguments );
        }
    }

    public function __get($name)
    {
        if(isset($this->uri[$name])){
            return $this->uri[$name];
        }else{
            return null;
        }
    }

    public function __set($name, $value)
    {
        $this->uri[$name] = $value;
        return $this;
    }

    //##################################################################################################################
    //##
    //## >> Method
    //##
    //##################################################################################################################
    /**
     * Method :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $key
     * @param $val
     * @return $this
     */
    private function add($key, $val)
    {
        $this->uri[$key] = $val;
        return $this;
    }

    /**
     * Method :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $key
     * @return array|mixed|null
     */
    private function get($key = '')
    {
        if(empty($key)){
            return $this->uri;
        }else{
            if(isset($this->uri[$key])){
                return $this->uri[$key];
            }else{
                return null;
            }
        }
    }

    /**
     * Method :
     * -----------------------------------------------------------------------------------------------------------------
     * @return string
     */
    private function make()
    {
        $res = [];
        if(empty($this->uri)) return "";
        foreach ($this->uri as $key=>$val){
            array_push($res, sprintf("%s=%s", (string)$key, (string)$val));
        }
        return join("&", $res);
    }
}