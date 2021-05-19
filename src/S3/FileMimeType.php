<?php namespace EnjoyWorks\S3;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : FileMimeType.php
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/28     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

trait FileMimeType
{
    /*
     |------------------------------------------------------------------------------------------------------------------ 
     | File MimeType 
     |------------------------------------------------------------------------------------------------------------------ 
     |
     | Ex) MIME Type
     | data: Application/octet-stream
     | xml: application/xml
     | pdf: application/pdf
     | xhtml: application/xhtml+xml
     | png: image/png
     | jpg: image/jpeg
     | gif: image/gif
     | bmp: image/bmp
     | webp: image/webp
     | text: text/plain
     | html: text/html
     | css: text/css
     | js: text/javascript
     | midi: audio/midi
     | mpeg: audio/mpeg
     | webm: audio/webm
     | ogg: audio/ogg
     | wav: audio/wav
     */
    protected $mimeType = array(
        "data"     => "Application/octet-stream",
        "xml"      => "application/xml",
        "pdf"      => "application/pdf",
        "xhtml"    => "application/xhtml+xml",
        "png"      => "image/png",
        "jpg"      => "image/jpeg",
        "gif"      => "image/gif",
        "bmp"      => "image/bmp",
        "webp"     => "image/webp",
        "text"     => "text/plain",
        "html"     => "text/html",
        "css"      => "text/css",
        "js"       => "text/javascript",
        "midi"     => "audio/midi",
        "mpeg"     => "audio/mpeg",
        "webm"     => "audio/webm",
        "ogg"      => "audio/ogg",
        "wav"      => "audio/wav"
    );
}