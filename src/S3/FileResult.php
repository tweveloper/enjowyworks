<?php namespace EnjoyWorks\S3;
use Illuminate\Http\UploadedFile;

/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : FileResult.php
 * @project : Pro_Mamma
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/04/13     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
class FileResult
{
    private $filePath;
    private $fileName;
    private $fileExte;
    private $fileSize;
    //##################################################################################################################
    //##
    //## >> Construct
    //##
    //##################################################################################################################
    public function __construct($filePath, $fileName, $fileExte, $fileSize)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->fileExte = $fileExte;
        $this->fileSize = $fileSize;
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
        $res .= "=============== FIle Content ===============================<br>";
        $res .= "파일 경로             : ".$this->filePath."<br>";
        $res .= "파일 이름             : ".$this->fileName."<br>";
        $res .= "파일 확장자           : ".$this->fileExte."<br>";
        $res .= "파일 크기             : ".$this->fileSize."<br>";
        return $res;
    }
}