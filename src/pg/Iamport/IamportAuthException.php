<?php namespace EnjoyWorks\pg\Iamport;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : IamportAuthException.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class IamportAuthException extends \Exception
{
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }
}