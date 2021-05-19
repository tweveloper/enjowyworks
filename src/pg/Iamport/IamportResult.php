<?php namespace EnjoyWorks\pg\Iamport;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : IamportResult.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/27     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

class IamportResult
{
    public $success = false;
    public $data;
    public $error;

    public function __construct($success=false, $data=null, $error=null) {
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }
}