<?php namespace EnjoyWorks\pg\KGAllat\VO;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : ApprovalVO.php
 * @project : Ew_Library
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/04/10     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

use EnjoyWorks\pg\KGAllat\KGAllat;

/*
 |---------------------------------------------------------------------------------------------------------------------
 | 요청 데이터 설정
 |---------------------------------------------------------------------------------------------------------------------
 | GET Parameter
 | allat_shop_id : 샵 아이디
 | allat_enc_data : 요청 데이터
 | allat_cross_key : 샵 크로스키
 |
 */

class EscrowConfirmVO extends BaseVO
{
    private $es_confirm_yn;
    private $es_reject;

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __converter(KGAllat $parent)
    {
        $vars = get_class_vars(self::class);
        foreach ($vars as $key=>$val){
            $this->{$key} = $parent->{$key};
        }
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
        $res .= "=============== 에스크로 확인 ===============================<br>";
        $res .= "결과코드              : ".$this->reply_cd."<br>";
        $res .= "결과메세지            : ".$this->reply_msg."<br>";
        $res .= "구매결정              : ".$this->es_confirm_yn."<br>";
        $res .= "구매거부사유          : ".$this->es_reject."<br>";
        return $res;
    }
}
