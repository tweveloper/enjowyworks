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
 | allat_amt : 요청 금액
 | allat_enc_data : 요청 데이터
 | allat_cross_key : 샵 크로스키
 |
 */

class ApprovalVO extends BaseVO
{
    private $order_no;
    private $amt;
    private $pay_type;
    private $approval_ymdhms;
    private $seq_no;
    private $approval_no;
    private $card_id;
    private $card_nm;
    private $sell_mm;
    private $zerofee_yn;
    private $cert_yn;
    private $contract_yn;
    private $save_amt;
    private $card_pointdc_amt;
    private $bank_id;
    private $bank_nm;
    private $cash_bill_no;
    private $escrow_yn;
    private $account_no;
    private $account_nm;
    private $income_account_nm;
    private $income_limit_ymd;
    private $income_expect_ymd;
    private $cash_yn;
    private $hp_id;
    private $ticket_id;
    private $ticket_pay_type;
    private $ticket_nm;
    private $partcancel_yn;

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
        $res .= "=============== 결제 내역 ===============================<br>";
        $res .= "결과코드              : ".$this->reply_cd."<br>";
        $res .= "결과메세지            : ".$this->reply_msg."<br>";
        $res .= "주문번호              : ".$this->order_no."<br>";
        $res .= "승인금액              : ".$this->amt."<br>";
        $res .= "지불수단              : ".$this->pay_type."<br>";
        $res .= "승인일시              : ".$this->approval_ymdhms."<br>";
        $res .= "거래일련번호          : ".$this->seq_no."<br>";
        $res .= "에스크로 적용 여부    : ".$this->escrow_yn."<br>";
        $res .= "=============== 신용 카드 ===============================<br>";
        $res .= "승인번호              : ".$this->approval_no."<br>";
        $res .= "카드ID                : ".$this->card_id."<br>";
        $res .= "카드명                : ".$this->card_nm."<br>";
        $res .= "할부개월              : ".$this->sell_mm."<br>";
        $res .= "무이자여부            : ".$this->zerofee_yn."<br>";   //무이자(Y),일시불(N)
        $res .= "인증여부              : ".$this->cert_yn."<br>";      //인증(Y),미인증(N)
        $res .= "직가맹여부            : ".$this->contract_yn."<br>";  //3자가맹점(Y),대표가맹점(N)
        $res .= "세이브 결제 금액      : ".$this->save_amt."<br>";
        $res .= "포인트할인 결제 금액  : ".$this->card_pointdc_amt."<br>";
        $res .= "=============== 계좌 이체 / 가상계좌 ====================<br>";
        $res .= "은행ID                : ".$this->bank_id."<br>";
        $res .= "은행명                : ".$this->bank_nm."<br>";
        $res .= "현금영수증 일련 번호  : ".$this->cash_bill_no."<br>";
        $res .= "=============== 가상계좌 ================================<br>";
        $res .= "계좌번호              : ".$this->account_no."<br>";
        $res .= "입금계좌명            : ".$this->account_nm."<br>";
        $res .= "입금자명              : ".$this->income_account_nm."<br>";
        $res .= "입금기한일            : ".$this->income_limit_ymd."<br>";
        $res .= "입금예정일            : ".$this->income_expect_ymd."<br>";
        $res .= "현금영수증신청 여부   : ".$this->cash_yn."<br>";
        $res .= "=============== 휴대폰 결제 =============================<br>";
        $res .= "이동통신사구분        : ".$this->hp_id."<br>";
        $res .= "=============== 상품권 결제 =============================<br>";
        $res .= "상품권 ID             : ".$this->ticket_id."<br>";
        $res .= "상품권 이름           : ".$this->ticket_pay_type."<br>";
        $res .= "결제구분              : ".$this->ticket_nm."<br>";
        $res .= "부분취소가능여부      : ".$this->partcancel_yn."<br>";
        return $res;
    }
}
/*
    [신용카드 전표출력 예제]

    결제가 정상적으로 완료되면 아래의 소스를 이용하여, 고객에게 신용카드 전표를 보여줄 수 있습니다.
    전표 출력시 상점아이디와 주문번호를 설정하시기 바랍니다.

    var urls ="http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id=상점아이디&order_no=주문번호";
    window.open(urls,"app","width=410,height=650,scrollbars=0");

    현금영수증 전표 또는 거래확인서 출력에 대한 문의는 올앳페이 사이트의 1:1상담을 이용하시거나
    02) 3788-9990 으로 전화 주시기 바랍니다.

    전표출력 페이지는 저희 올앳 홈페이지의 일부로써, 홈페이지 개편 등의 이유로 인하여 페이지 변경 또는 URL 변경이 있을 수
    있습니다. 홈페이지 개편에 관한 공지가 있을 경우, 전표출력 URL을 확인하시기 바랍니다.
*/
