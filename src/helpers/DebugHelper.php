<?php
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : DebugHelper.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/14     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description

 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

//######################################################################################################################
//##
//## >> Function : Debug
//##
//######################################################################################################################

if(!function_exists("code")):

    /**
     * Debug : Debug Code Tag
     * -----------------------------------------------------------------------------------------------------------------
     */
    function code(){
        global $code;
        if($code){return;}else{$code = true;}
        echo'<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>'.PHP_EOL;
        echo'<link rel="stylesheet" href="https://jmblog.github.io/color-themes-for-google-code-prettify/themes/tranquil-heart.css"/>'.PHP_EOL;
        echo'<script>addEventListener("load", function(event) { console.log(PR.loadStylesheetsFallingBack);PR.prettyPrint(); },false);</script>'.PHP_EOL;
    }

endif;

if(!function_exists('testP')):

    /**
     * Debug : Debug Pre Tag
     * -----------------------------------------------------------------------------------------------------------------
     * @param $val
     * @param bool $exit
     * @param $default
     * @param bool $isCode
     */
    function testP($val = null, $exit = false, $default = 'NULL', $isCode = true){
        if(function_exists("code") && $isCode)code();
        if(empty($val))$val = $default;
        echo'<pre class="prettyprint linenums">'.PHP_EOL;print_r($val);echo'</pre>'.PHP_EOL;
        if(!empty($exit))exit($exit === true?"":$exit);
    }

endif;

if(!function_exists('testV')):

    /**
     * Debug : Debug Pre Tag
     * -----------------------------------------------------------------------------------------------------------------
     * @param $val
     * @param bool $exit
     * @param $default
     * @param bool $isCode
     */
    function testV($val = null, $exit = false, $default = 'NULL', $isCode = true){
        if(function_exists("code") && $isCode)code();
        echo'<pre class="prettyprint linenums">';var_dump($val);echo'</pre>'.PHP_EOL;
        if(!empty($exit))exit($exit === true?"":$exit);
    }

endif;

if(!function_exists('testC')):

    /**
     * Debug : Debug Console
     * -----------------------------------------------------------------------------------------------------------------
     * @param $val
     * @param bool $exit
     * @param $default
     */
    function testC($val = null, $exit = false, $default = 'NULL'){
        if(empty($val))$val = $default;
        echo"<script>".PHP_EOL;
        echo"var json  = ".json_encode($val, JSON_PRETTY_PRINT).";".PHP_EOL;
        echo"console.log(json);".PHP_EOL;
        echo"</script>".PHP_EOL;
        if(!empty($exit))exit($exit === true?"":$exit);
    }

endif;

if(!function_exists('testL')):

    /**
     * Debug : Debug File Log
     * -----------------------------------------------------------------------------------------------------------------
     * @param $val
     * @param bool $exit
     * @param $default
     */
    function testL($val = null, $exit = false, $default = 'NULL'){
        if(empty($val))$val = $default;
        $log_dir = storage_path("logs");
        $log_file = fopen($log_dir."/logP.log", "a");
        $str = json_encode($val);
        $date = date("Y-m-d H:i:s");
        fwrite($log_file, $date." : ".$str ."\r\n");
        fclose($log_file);
        if(!empty($exit))exit($exit === true?"":$exit);
    }

endif;