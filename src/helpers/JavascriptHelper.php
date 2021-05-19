<?php
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : JavascriptHelper.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/01/24     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

if(!function_exists("js")):

    /**
     * 자바스크립트 관련 helper 모음.
     * -----------------------------------------------------------------------------------------------------------------
     * @author gabia
     * @since version 1.0 - 2009. 7. 7.
     */

    function js($content) {
        return "
        <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\n
        <script type=\"text/javascript\" charset=\"utf-8\">".$content."</script>
        ";
    }

endif;


if(!function_exists("alert")):

    /**
     * 자바스크립트 메세시 창 호출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $msg
     */
    function alert($msg) {
        echo js("alert('$msg')");
    }

endif;

if(!function_exists("pageRedirect")):

    /**
     * 자바스트립트 페이지 재호출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $url
     * @param string $msg
     * @param string $target
     */
    function pageRedirect($url, $msg = '', $target = 'self') {
        if ($msg) {
            alert($msg);
        }
        echo js($target . ".document.location.replace('$url')");
    }

endif;

if(!function_exists("pageLocation")):

    /**
     * 자바스크립트 페이지 호출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $url
     * @param string $msg
     * @param string $target
     */
    function pageLocation($url, $msg = '', $target = 'self') {
        if ($msg) {
            alert($msg);
        }
        echo js($target . ".document.location.href='$url'");
    }

endif;

if(!function_exists("pageBack")):

    /**
     * 자바스크립트 이전페이지 호출
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $msg
     */
    function pageBack($msg = '') {
        if ($msg) {
            alert($msg);
        }
        echo js("history.back();");
        exit;
    }

endif;

if(!function_exists("pageReload")):

    /**
     * 자바스크립트 페이지 ReLoad
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $msg
     * @param string $target
     */
    function pageReload($msg = '', $target = 'self') {
        if ($msg) {
            alert($msg);
        }
        echo js($target . ".document.location.reload();");
        if($target=='parent' || $target=='top') echo js("document.location.href='about:blank';");
    }

endif;

if(!function_exists("pageClose")):

    /**
     * 자바스크립트 페이지 닫기
     * -----------------------------------------------------------------------------------------------------------------
     * @param string $msg
     */
    function pageClose($msg = '') {
        if ($msg) {
            alert($msg);
        }
        echo js("self.close();");
    }

endif;

if(!function_exists("openerRedirect")):

    /**
     * 자바스크립트 팝업 페이지 이동
     * -----------------------------------------------------------------------------------------------------------------
     * @param $url
     * @param string $msg
     */
    function openerRedirect($url, $msg = '') {
        if ($msg) {
            alert($msg);
        }
        echo js("opener.document.location.replace('$url')");
    }

endif;

if(!function_exists("openDialogAlert")):

    /**
     * 자바스크립트 팝업 얼럿 호출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $msg
     * @param $width
     * @param $height
     * @param string $target
     * @param string $callback
     * @param array $options
     */
    function openDialogAlert($msg,$width,$height,$target = 'self',$callback='',$options=array()) {
        $CI =& get_instance();
        if($CI->mobileMode || $CI->storemobileMode){
            $msg = str_replace(array("<br />","<br/>","<br>"),"",$msg);
            $msg = strip_tags($msg);
        }

        if (strpos($_SERVER['HTTP_USER_AGENT'], "Firefox") !== false) {
            if (strpos($callback, "location.reload()") !== false) $callback = str_replace("location.reload()","location.reload(true)",$callback);
        }
        echo("<script type='text/javascript'>");
        echo("{$target}.loadingStop('body',true);");
        echo("{$target}.loadingStop();");
        echo("{$target}.openDialogAlert('{$msg}','{$width}','{$height}',function(){{$callback}},".json_encode($options).");");
        echo("</script>");
    }

endif;

if(!function_exists("openDialogConfirm")):

    /**
     * 자바스크립트 팝업 확인 얼럿 호출
     * -----------------------------------------------------------------------------------------------------------------
     * @param $msg
     * @param $width
     * @param $height
     * @param string $target
     * @param string $yesCallback
     * @param string $noCallback
     */
    function openDialogConfirm($msg,$width,$height,$target = 'self',$yesCallback='',$noCallback='') {
        $CI =& get_instance();
        if($CI->mobileMode || $CI->storemobileMode){
            $msg = str_replace(array("<br />","<br/>","<br>"),"",$msg);
            $msg = strip_tags($msg);
        }
        echo("<script type='text/javascript'>");
        echo("{$target}.loadingStop();");
        echo("{$target}.openDialogConfirm('{$msg}','{$width}','{$height}',function(){{$yesCallback}},function(){{$noCallback}});");
        echo("</script>");
    }

endif;