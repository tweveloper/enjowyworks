<?php namespace EnjoyWorks\pg\KGAllat;
/******************************  Comment  *****************************
File Name         : allatutil.php
File Description  : Allat Script API Utility Function(Class)
[ Notice ]
이 파일은 NewAllatPay를 사용하기 위한 Utility Function을 구현한
Source Code입니다. 이 파일에 내용을 임의로 수정하실 경우 기술지원을
받으실 수 없음을 알려드립니다. 이 파일 내용에 문제가 있을 경우,
아래 연락처로 문의 주시기 바랍니다.

TEL       : 02-3783-9990
EMAIL     : allatpay@allat.co.kr
Homepage  : www.allatpay.com
 ***********  Copyright Allat Corp. All Right Reserved  **************/

define("util_lang","PHP");
define("util_ver","1.0.7.1");

define("approval_uri",     "POST /servlet/AllatPay/pay/approval.jsp HTTP/1.0\r\n");
define("sanction_uri",     "POST /servlet/AllatPay/pay/sanction.jsp HTTP/1.0\r\n");
define("cancel_uri",       "POST /servlet/AllatPay/pay/cancel.jsp HTTP/1.0\r\n");
define("cashreg_uri",      "POST /servlet/AllatPay/pay/cash_registry.jsp HTTP/1.0\r\n");
define("cashapp_uri",      "POST /servlet/AllatPay/pay/cash_approval.jsp HTTP/1.0\r\n");
define("cashcan_uri",      "POST /servlet/AllatPay/pay/cash_cancel.jsp HTTP/1.0\r\n");
define("escrowchk_uri",    "POST /servlet/AllatPay/pay/escrow_check.jsp HTTP/1.0\r\n");
define("escrowret_uri",    "POST /servlet/AllatPay/pay/escrow_return.jsp HTTP/1.0\r\n");
define("escrowconfirm_uri","POST /servlet/AllatPay/pay/escrow_confirm.jsp HTTP/1.0\r\n");
define("certreg_uri",      "POST /servlet/AllatPay/pay/fix.jsp HTTP/1.0\r\n");
define("certcancel_uri",   "POST /servlet/AllatPay/pay/fix_cancel.jsp HTTP/1.0\r\n");
define("cardpointdc_uri",  "POST /servlet/AllatPay/pay/cardpointdc.jsp HTTP/1.0\r\n");
define("cardlist_uri",     "POST /servlet/AllatPay/nonactivex/nonre/nonre_cardlist.jsp HTTP/1.0\r\n");

define("c2c_approval_uri",     "POST /servlet/AllatPay/pay/c2c_approval.jsp HTTP/1.0\r\n");
define("c2c_cancel_uri",       "POST /servlet/AllatPay/pay/c2c_cancel.jsp HTTP/1.0\r\n");
define("c2c_sellerreg_uri",    "POST /servlet/AllatPay/pay/seller_registry.jsp HTTP/1.0\r\n");
define("c2c_productreg_uri",   "POST /servlet/AllatPay/pay/product_registry.jsp HTTP/1.0\r\n");
define("c2c_buyerchg_uri",     "POST /servlet/AllatPay/pay/buyer_change.jsp HTTP/1.0\r\n");
define("c2c_escrowchk_uri",    "POST /servlet/AllatPay/pay/c2c_escrow_check.jsp HTTP/1.0\r\n");
define("c2c_escrowconfirm_uri","POST /servlet/AllatPay/pay/c2c_escrow_confirm.jsp HTTP/1.0\r\n");
define("c2c_esrejectcheck_uri","POST /servlet/AllatPay/pay/c2c_reject_check.jsp HTTP/1.0\r\n");
define("c2c_expressreg_uri",   "POST /servlet/AllatPay/pay/c2c_express_reg.jsp HTTP/1.0\r\n");

define("allat_addr_ssl","ssl://tx.allatpay.com" );
define("allat_addr","tx.allatpay.com");
define("allat_host","tx.allatpay.com");

define("ALLAT_SSL_USE","SSL");
define("ALLAT_SSL_NONE","NOSSL");

use EnjoyWorks\pg\KGAllat\VO\ApprovalVO;
use EnjoyWorks\pg\KGAllat\VO\CancelVO;
use EnjoyWorks\pg\KGAllat\VO\CashApprovalVO;
use EnjoyWorks\pg\KGAllat\VO\CashCancelVO;
use EnjoyWorks\pg\KGAllat\VO\CashRegistryVO;
use EnjoyWorks\pg\KGAllat\VO\EscrowCheckVO;
use EnjoyWorks\pg\KGAllat\VO\EscrowConfirmVO;
use EnjoyWorks\pg\KGAllat\VO\EscrowReturnVO;
use EnjoyWorks\pg\KGAllat\VO\SanctionVO;

/*
 |---------------------------------------------------------------------------------------------------------------------
 | 사용방법
 |---------------------------------------------------------------------------------------------------------------------
 | $data = new ApprovalVO(); << ApprovalVO (요청 VO) Instance
 |
 | 방법 1) KGAllat Instance
 | $kgAllat = new KGAllat(); << KGAllat Instance
 | $result = $kgAllat->add('allat_shop_id','sample_01')->add('allat_amt', 1000)->add('allat_enc_data', '')
 | ->add('allat_cross_key', 'cf1d859a87de635070309b4770b3f1e0') << Get Parameter 설정
 | ->sendApprovalResVo($data, 'NOSSL'); << 통신 요청
 |
 | 방법 2) KGAllat Static
 | $result = KGAllat::add('allat_shop_id','sample_01')->add('allat_amt', 1000)->add('allat_enc_data', '')
 | ->add('allat_cross_key', 'cf1d859a87de635070309b4770b3f1e0') << Get Parameter 설정
 | ->sendApprovalResVo($data, 'NOSSL'); << 통신 요청
 |
 */
class KGAllat
{
    public $methods = [];
    public $builder;
    public $response;
    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################

    public function __construct()
    {
        $this->methods = get_class_methods($this);
    }

    public function __call( $method, $arguments )
    {
        if ( method_exists( $this, $method ) ) {
            return call_user_func_array( [ $this, $method ], $arguments );
        }

        if(!$this->builder instanceof Builder){
            $this->builder = new Builder();
        }
        if ( method_exists( $this->builder , $method ) ) {
            call_user_func_array( [ $this->builder , $method ], $arguments );
            return $this;
        }
    }

    public static function __callStatic( $method, $arguments )
    {
        $instance = new static();
        if ( method_exists( $instance, $method ) ) {
            return call_user_func_array( [ $instance, $method ], $arguments );
        }

        if(!$instance->builder instanceof Builder){
            $instance->builder = new Builder();
        }
        if ( method_exists( $instance->builder, $method ) ) {
            call_user_func_array( [ $instance->builder, $method ], $arguments );
            return $instance;
        }
    }

    public function __get($name)
    {
        if(empty($name))return null;
        $pattern = sprintf("/%s/", $name);
        if(!empty($this->response) && preg_match($pattern, $this->response)){
            return $this->getValue($name, $this->response);
        }else{
            return isset($this->{$name})?$this->{$name}:null;
        }
    }

    //##################################################################################################################
    //##
    //## >> Approval
    //##
    //##################################################################################################################

    /**
     * Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param ApprovalVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendApprovalResVo(ApprovalVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendApprovalRes($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendApprovalRes($ssl_flag = ALLAT_SSL_USE){
        if(!is_string($ssl_flag))$ssl_flag = ALLAT_SSL_USE;
        $atData = $this->builder->make();
        $atText = $this->ApprovalReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function ApprovalReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo( $at_data, allat_addr_ssl, approval_uri, allat_host, 443 );
        }else{
            $isEnc=$this->checkEnc( $at_data );
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo( $at_data, allat_addr, approval_uri, allat_host, 80 );
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }


    //##################################################################################################################
    //##
    //## >> Sanction
    //##
    //##################################################################################################################
    /**
     * Sanction :
     * -----------------------------------------------------------------------------------------------------------------
     * @param SanctionVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendSanctionReqVo(SanctionVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendSanctionReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Sanction :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendSanctionReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->SanctionReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Sanction :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function SanctionReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo( $at_data, allat_addr_ssl, sanction_uri, allat_host, 443 );
        }else{
            $isEnc=$this->checkEnc( $at_data );
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo( $at_data, allat_addr, sanction_uri, allat_host, 80 );
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Cancel
    //##
    //##################################################################################################################
    /**
     * Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param CancelVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCancelReqVo(CancelVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendCancelReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCancelReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CancelReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }


    /**
     * Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CancelReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo( $at_data, allat_addr_ssl, cancel_uri, allat_host, 443 );
        }else{
            $isEnc=$this->checkEnc( $at_data );
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo( $at_data, allat_addr, cancel_uri, allat_host, 80 );
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Cash Registry
    //##
    //##################################################################################################################
    /**
     * Cash Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param CashRegistryVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCashRegistryReqVo(CashRegistryVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendCashRegistryReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Cash Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCashRegistryReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CashRegistryReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Cash Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CashRegistryReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,cashreg_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,cashreg_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Cash Approval
    //##
    //##################################################################################################################
    /**
     * Cash Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param CashApprovalVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCashApprovalReqVo(CashApprovalVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendCashApprovalReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Cash Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCashApprovalReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CashApprovalReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Cash Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CashApprovalReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,cashapp_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,cashapp_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Cash Cancel
    //##
    //##################################################################################################################
    /**
     * Cash Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param CashCancelVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCashCancelReqVo(CashCancelVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendCashCancelReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Cash Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCashCancelReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CashCancelReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Cash Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CashCancelReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,cashcan_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,cashcan_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Escrow Check
    //##
    //##################################################################################################################
    /**
     * Escrow Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param EscrowCheckVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendEscrowCheckReqVo(EscrowCheckVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendEscrowCheckReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Escrow Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendEscrowCheckReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->EscrowCheckReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Escrow Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function EscrowCheckReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,escrowchk_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,escrowchk_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Escrow Return
    //##
    //##################################################################################################################
    /**
     * Escrow Return :
     * -----------------------------------------------------------------------------------------------------------------
     * @param EscrowReturnVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendEscrowReturnReqVo(EscrowReturnVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendEscrowReturnReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Escrow Return :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendEscrowReturnReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->EscrowReturnReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Escrow Return :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function EscrowReturnReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,escrowret_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,escrowret_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Escrow Confirm
    //##
    //##################################################################################################################
    /**
     * Escrow Confirm :
     * -----------------------------------------------------------------------------------------------------------------
     * @param EscrowConfirmVO $vo
     * @param string $ssl_flag
     * @return $this
     */
    private function sendEscrowConfirmReqVo(EscrowConfirmVO $vo, $ssl_flag = ALLAT_SSL_USE)
    {
        $this->sendEscrowConfirmReq($ssl_flag);
        return $vo->__converter($this);
    }

    /**
     * Escrow Confirm :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendEscrowConfirmReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->EscrowConfirmReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Escrow Confirm :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function EscrowConfirmReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,escrowconfirm_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,escrowconfirm_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Card Registry
    //##
    //##################################################################################################################
    /**
     * Card Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCardRegistryReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CardRegistryReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Card Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CardRegistryReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,certreg_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,certreg_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Card Cancel
    //##
    //##################################################################################################################
    /**
     * Card Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCardCancelReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CertCancelReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Card Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CardCancelReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,certcancel_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,certcancel_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Card Point DC
    //##
    //##################################################################################################################
    /**
     * Card Point DC :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCardPointdcReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CardPointdcReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Card Point DC :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CardPointdcReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,cardpointdc_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,cardpointdc_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Card List
    //##
    //##################################################################################################################
    /**
     * Card List :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendCardListReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->CardListReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * Card List :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function CardListReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,cardlist_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,cardlist_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Approval
    //##
    //##################################################################################################################
    /**
     * C2C Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CApprovalReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CApprovalReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Approval :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CApprovalReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_approval_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_approval_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Cancel
    //##
    //##################################################################################################################
    /**
     * C2C Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CCancelReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CCancellReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Cancel :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CCancelReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_cancel_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_cancel_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Seller Register
    //##
    //##################################################################################################################
    /**
     * C2C Seller Register :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CSellerRegReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CSellerRegReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Seller Register :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CSellerRegReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_sellerreg_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_sellerreg_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Product Register
    //##
    //##################################################################################################################
    /**
     * C2C Product Register :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CProductRegReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CProductRegReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Product Register :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CProductRegReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_productreg_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_productreg_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Buyer Check
    //##
    //##################################################################################################################
    /**
     * C2C Buyer Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CBuyerCheckReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CBuyerCheckReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Buyer Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CBuyerCheckReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_buyerchg_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_buyerchg_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Express Escrow Check
    //##
    //##################################################################################################################
    /**
     * C2C Express Escrow Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CEscrowCheckReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CEscrowCheckReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Express Escrow Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CEscrowCheckReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_escrowchk_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_escrowchk_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Express Escrow Confirm
    //##
    //##################################################################################################################
    /**
     * C2C Express Escrow Confirm :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CEscrowConfirmReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CEscrowConfirmReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Express Escrow Confirm :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CEscrowConfirmReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_escrowconfirm_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_escrowconfirm_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Express Reject Check
    //##
    //##################################################################################################################
    /**
     * C2C Express Reject Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CEsRejectCheckReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CEsRejectCheckReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Express Reject Check :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CEsRejectCheckReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_esrejectcheck_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_esrejectcheck_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> C2C Express Registry
    //##
    //##################################################################################################################
    /**
     * C2C Express Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $ssl_flag
     * @return $this
     */
    private function sendC2CExpressRegistryReq($ssl_flag = ALLAT_SSL_USE){
        $atData = $this->builder->make();
        $atText = $this->C2CExpressRegistryReq($atData, $ssl_flag);
        $this->response = $atText;
        return $this;
    }

    /**
     * C2C Express Registry :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $at_data
     * @param $ssl_flag
     * @return string
     */
    private function C2CExpressRegistryReq($at_data,$ssl_flag)
    {
        $ret_txt="reply_cd=0299\n";

        if( strcmp($ssl_flag,"SSL")==0 ){
            $ret_txt=$this->SendRepo($at_data,allat_addr_ssl,c2c_expressreg_uri,allat_host,443);
        }else{
            $isEnc=$this->checkEnc($at_data);
            if( $isEnc ){ //암호화 됨
                $ret_txt=$this->SendRepo($at_data,allat_addr,c2c_expressreg_uri,allat_host,80);
            }else{
                return "reply_cd=0230\nreply_msg=암호화 오류\n";
            }
        }
        return $ret_txt;
    }

    //##################################################################################################################
    //##
    //## >> Core
    //##
    //##################################################################################################################
    /**
     * Core :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $srp_data
     * @param $srp_addr
     * @param $srp_url
     * @param $srp_host
     * @param $srp_port
     * @return string
     */
    private function SendRepo($srp_data,$srp_addr,$srp_url,$srp_host,$srp_port)
    {
        $ret_txt=$this->SendReq($srp_data,$srp_addr,$srp_url,$srp_host,$srp_port);
        return $ret_txt;
    }

    /**
     * Core :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $req_data
     * @param $req_addr
     * @param $req_url
     * @param $req_host
     * @param $req_port
     * @return string
     */
    private function SendReq($req_data,$req_addr,$req_url,$req_host,$req_port)
    {
        $resp_txt="reply_cd=0299\n";
        $dateNtime=date('YmdHis');
        $util_ver="&allat_opt_lang=".util_lang."&allat_opt_ver=".util_ver;
        $req_data=$req_data."&allat_apply_ymdhms=".$dateNtime;
        $send_data=$req_data.$util_ver;
        $at_sock = @fsockopen($req_addr,$req_port,$errno,$errstr);
        //warning message disable '@'
        if($at_sock){
            fwrite($at_sock, $req_url );
            fwrite($at_sock, "Host: ".$req_host.":".$req_port."\r\n" );
            fwrite($at_sock, "Content-type: application/x-www-form-urlencoded\r\n");
            fwrite($at_sock, "Content-length: ".strlen($send_data)."\r\n");
            fwrite($at_sock, "Accept: */*\r\n");
            fwrite($at_sock, "\r\n");
            fwrite($at_sock, $send_data."\r\n");
            fwrite($at_sock, "\r\n");
            $resp_txt=$this->convertSock($at_sock);
        }else{
            $resp_txt="reply_cd=0212\n"."reply_msg=Socket Connect Error:".$errstr."\n";
        }
        fclose($at_sock);
        return $resp_txt;
    }

    /**
     * Core :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $csock
     * @return string
     */
    private function convertSock($csock)
    {
        while(!feof($csock)){
            $headers=fgets($csock,4096);
            if($headers=="\r\n"){
                break;
            }
        }
        $bodys = "";
        while(!feof($csock)){
            $bodys.=fgets($csock,4096);
        }

        $charset = mb_detect_encoding($bodys, "EUC-KR, UTF-8, ASCII");
        if($charset != "UTF-8")$bodys = iconv($charset, "UTF-8", $bodys);

        $isError=$this->getValue("reply_cd",$bodys);
        if($isError==""||$isError==null){
            $temp_msg=strip_tags($bodys);
            $re_msg=$this->getValue("reply_msg",$bodys);
            $error_msg="reply_cd=0251\n"."reply_msg=".trim($re_msg).trim($temp_msg)."\n";
            return $error_msg;
        }else{
            return $bodys;
        }
    }


    //##################################################################################################################
    //##
    //## >> Function
    //##
    //##################################################################################################################

    /**
     * Function :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $nameVal
     * @param $textVal
     * @return mixed
     */
    private function getValue($nameVal,$textVal)
    {
        $temp = explode("\n",trim($textVal));
        for($i=0;$i<sizeof($temp);$i++){
            $retVal=explode("=",trim($temp[$i]));
            if( $retVal[0]== $nameVal ){
                $returnVal=$retVal[1];
            }
        }
        return $returnVal;
    }

    /**
     * Function :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $srcstr
     * @return bool
     */
    private function checkEnc($srcstr)
    {
        $posno=strpos($srcstr,"allat_enc_data=");

        if($posno === false){
            return false;
        }
        if(substr($srcstr,$posno+strlen("allat_enc_data=")+5,1)!="1"){
            return false;
        }
        return true;
    }

    /**
     * Function :
     * -----------------------------------------------------------------------------------------------------------------
     * @param $retData
     * @param $insKey
     * @param $insValue
     * @return string
     */
    private function setValue($retData,$insKey,$insValue)
    {
        if(strlen($retData) == 0){
            $tmpData="00000010".$insKey."".$insValue."";
        }else{
            $tmpData=$retData.$insKey."".$insValue."";
        }
        return $tmpData;
    }

    /**
     * Function :
     * -----------------------------------------------------------------------------------------------------------------
     */
    private function receive()
    {
        $result_cd  = $_POST["allat_result_cd"];
        $result_msg = $_POST["allat_result_msg"];
        $enc_data   = $_POST["allat_enc_data"];

        //#### 결과값 Return
        echo("<script>");
        echo("if(window.opener != undefined) {");
        echo("	opener.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');");
        echo("	window.close();");
        echo("} else {");
        echo("	parent.result_submit('".$result_cd."','".$result_msg."','".$enc_data."');");
        echo("}");
        echo("</script>");
    }
}
?>
