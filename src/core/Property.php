<?php namespace EnjoyWorks\core;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : Property.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/14     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class Property {
    protected $data = array();
    protected $propertyArr = array();

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    function __construct($property = array())
    {
        if(!empty($property)){
            $this->propertyArr = $property;
        }
    }

    function __set($name, $value)
    {
        if(!empty($property)){
            if(!in_array($name, $this->propertyArr))return null;
        }
        $this->data[$name] = $value;
    }

    function __get($name)
    {
        if(!array_key_exists($name, $this->data))return null;
        return $this->data[$name];
    }

    function __isset($name)
    {
        if(!array_key_exists($name, $this->data))return null;
        return isset($this->data[$name]);
    }

    function __unset($name)
    {
        if(!array_key_exists($name, $this->data))return null;
        unset($this->data[$name]);
    }

    function __toString()
    {
        return json_encode($this->data);
    }
    //##################################################################################################################
    //##
    //## >> Method
    //##
    //##################################################################################################################
    public function toJson()
    {
        return json_encode($this->data);
    }

    public function toArray()
    {
        return $this->data;
    }

    public function toObject()
    {
        return (object)$this->data;
    }
}