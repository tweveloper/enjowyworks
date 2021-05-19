<?php namespace EnjoyWorks\pg\Iamport;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : IamportRequestException.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class IamportRequestException extends \Exception
{
    protected $response;
    public function __construct($response) {
        $this->response = $response;
        parent::__construct($response->message, $response->code);
    }
}