<?php namespace EnjoyWorks\S3;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : FileFunc.php
 * @project :
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/04/12     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
class FileFunc
{
    private $fileS3;

    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    public function __construct()
    {
        if(empty($this->fileS3))$this->fileS3 = new FileS3();
    }

    public function __call( $method, $arguments )
    {
        if ( method_exists( $this, $method ) ) {
            return call_user_func_array( [ $this, $method ], $arguments );
        }
    }

    public static function __callStatic( $method, $arguments )
    {
        $instance = new static();
        if ( method_exists( $instance, $method ) ) {
            return call_user_func_array( [ $instance, $method ], $arguments );
        }
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Upload Post
    //##
    //##################################################################################################################

    /**
     * 3S Post : Storage File Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $type
     * @return FileS3Exception|\Faker\Provider\File
     */
    private function uploadPost($file, $type = '')
    {
        $ex =  $file->getClientOriginalExtension();
        $fileName = sprintf("%s.%s", (string)currentTimeMillis(), $ex);
        if((preg_match("/(bmp|gif|png|jpe?g)/i", $ex) && empty($type)) || $type == FileS3::S3_IMAGE_DIR){
            return $this->fileS3->uploadImageFile($file, $fileName, date('Y'), date('m'), date('d'));
        }else if(empty($type) || $type == FileS3::S3_ATTACH_DIR){
            return $this->fileS3->uploadAttachFile($file, $fileName, date('Y'), date('m'), date('d'));
        }
    }

    /**
     * 3S Post : Storage Images Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $files
     * @param $callback
     */
    private function uploadsPost($files, $callback)
    {
        $result = array();
        foreach ($files as $file){
            $res = $this->upload($file);
            array_push($result, $res);
        }
        $callback($res);
    }

    /**
     * 3S Post : Storage Resize Image Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $width
     * @param $height
     * @param string $type
     * @return null
     * @throws FileS3Exception
     */
    private function uploadResizePost($file, $width, $height, $type = '')
    {
        $ex =  $file->getClientOriginalExtension();
        $fileName = sprintf("%s.%s", (string)currentTimeMillis(), $ex);
        if(!preg_match("/(bmp|gif|png|jpe?g)/i", $ex)) throw  new FileS3Exception("이미지 파일만 가능 합니다.");
        if($type == FileS3::S3_ATTACH_DIR){
            return $this->fileS3->uploadResizeImage($file, FileS3::S3_ATTACH_DIR, $fileName, $width, $height, date('Y'), date('m'), date('d'));
        } else {
            return $this->fileS3->uploadResizeImage($file, FileS3::S3_IMAGE_DIR, $fileName, $width, $height, date('Y'), date('m'), date('d'));
        }
    }

    /**
     * 3S Post : Storage Resize Images Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $files
     * @param $callback
     */
    private function uploadsResizePost($files, $callback)
    {
        $result = array();
        foreach ($files as $file){
            $res = $this->uploadResizePost($file);
            array_push($result, $res);
        }
        $callback($res);
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Upload Stream
    //##
    //##################################################################################################################
    /**
     * 3S Stream : Image Stream Data Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $stream
     * @param $fileName
     * @return FileS3Exception|\Faker\Provider\File
     * @throws FileS3Exception
     */
    private function uploadStream($stream, $fileName)
    {
        return $this->fileS3->uploadImageStream($stream, $fileName, date('Y'), date('m'), date('d'));
    }

    /**
     * 3S Stream : Image Stream Data Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $stream
     * @param $fileName
     * @return FileS3Exception|\Faker\Provider\File
     * @throws FileS3Exception
     */
    private function uploadImageBase64($stream, $fileName)
    {
        if(!preg_match("/^data:image\//i", $stream)) throw  new FileS3Exception("이미지 파일만 가능 합니다.");
        return $this->fileS3->uploadImageStream($stream, $fileName, date('Y'), date('m'), date('d'));
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S SummerNote
    //##
    //##################################################################################################################
    /**
     * 3S SummerNote : Storage Image Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $detail
     * @return string
     */
    private function uploadSummernote($detail)
    {
        $dom = new \DOMDocument();
        if(isset($detail)) {
            libxml_use_internal_errors(true);
            $dom->loadHtml(mb_convert_encoding($detail, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');
            foreach ($images as $k => $img) {
                $data = $img->getAttribute('src');
                if (count(explode(';', $data)) <= 1) continue;
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $image_name = currentTimeMillis() . $k . '.png';
                $this->fileS3->uploadImageStream($data, $image_name, date("Y"), date("m"), date("d"));

                $image_url = sprintf("/ew/data/%s/%s", date("Y/m/d"), $image_name);
                $img->removeAttribute('src');
                $img->setAttribute('src', $image_url);
            }
        }
        return  $dom->saveHTML();
    }

    /**
     * 3S SummerNote : Storage Image Delete
     * -----------------------------------------------------------------------------------------------------------------
     * @param $content
     */
    private function deleteSummernote($content)
    {
        $dom = new \DomDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHtml(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');
        foreach($images as $k => $img){
            $data = $img->getAttribute('src');

            $pos = strpos($data, UPLOAD_URL);
            if($pos !== false){
                $url = substr($data, strlen(UPLOAD_URL));
                $this->fileS3->delFileUrl($url);
            }
        }
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Image
    //##
    //##################################################################################################################
    /**
     * 3S Image : Storage Image
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathString
     * @return \Illuminate\Http\Response
     */
    private function image($pathString)
    {
        $ex = stringPathExtension($pathString);
        if(preg_match("/(bmp|gif|png|jpe?g)/i", $ex)){
            return $this->fileS3->getWebFile($ex, FileS3::S3_IMAGE_DIR, explodeEmpty("/", $pathString));
        }else{
            return response()->make(null, 404);
        }
    }

    /**
     * 3S Image : Storage Image
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathString
     * @return \Illuminate\Http\Response
     */
    private function imageOriginal($pathString)
    {
        $ex = stringPathExtension($pathString);
        if(preg_match("/(bmp|gif|png|jpe?g)/i", $ex)){
            return $this->fileS3->getWebFile($ex, FileS3::S3_IMAGE_DIR, explodeEmpty("/", $pathString));
        }else{
            return response()->make(null, 404);
        }
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S ZipFIle
    //##
    //##################################################################################################################
    /**
     * 3S ZipFIle : Storage Zip File
     * -----------------------------------------------------------------------------------------------------------------
     * @param $filePathStrings
     * @return \Illuminate\Http\Response
     */
    private function zipFIle($filePathStrings)
    {
        if(is_array($filePathStrings)){
            return $this->fileS3->getZipFIle($filePathStrings, currentTimeMillis());
        }else if(is_string($filePathStrings)){
            return $this->fileS3->getZipFIle(array($filePathStrings), currentTimeMillis());
        }
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Thumbnail
    //##
    //##################################################################################################################
    /**
     * 3S Thumbnail : Storage Thumbnail
     * -----------------------------------------------------------------------------------------------------------------
     * @param $width
     * @param $height
     * @param $base64_path
     * @return \Illuminate\Http\Response
     */
    private function thumbnail($width, $height, $base64_path)
    {
        if(empty($base64_path) || empty($pathStr = base64_decode($base64_path))) return response()->make(null, 404);
        return $this->fileS3->getResizeCropImage($width, $height, $pathStr);
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Delete
    //##
    //##################################################################################################################
    /**
     * 3S Post : Storage File Delete
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathString
     * @return bool
     */
    private function deletePath($pathString)
    {
        return $this->fileS3->delFileUrl($pathString);
    }

}