<?php namespace EnjoyWorks\pg\Iamport;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : IamportPayment.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class IamportPayment
{
    protected $response;
    protected $custom_data;

    public function __construct($response) {
        $this->response = $response;

        $this->custom_data = json_decode($response->custom_data);
    }

    public function __get($name) {
        if (isset($this->response->{$name})) {
            return $this->response->{$name};
        }
    }

    public function getCustomData($name=null) {
        if ( is_null($name) )	return $this->custom_data;

        return $this->custom_data->{$name};
    }
}