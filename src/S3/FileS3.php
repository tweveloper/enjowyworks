<?php namespace EnjoyWorks\S3;
/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  : FileS3.php
 * @project :
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/21     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/
use Faker\Provider\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use WGenial\S3ObjectsStreamZip\S3ObjectsStreamZip;

/*
 |----------------------------------------------------------------------------------------------------------------------
 | 설정 정의
 |----------------------------------------------------------------------------------------------------------------------
 | Path : S3의 디렉토리 경로를 기준으로 정의 하며
 | ...$path : 함수의 디렉토리 경로 정보를 기준으로 한다.
 | Ex) functionName(images, 2018, 02, 14 ,15185718811850.png)
 | $pathStr : S3의 디렉토리 경로를 문자로 표시한 문자열이다
 | Ex) /images/upload/2018/02/14/15185718811850.png
 |
 */

class FileS3
{
    const S3_ATTACH_DIR = "attach";
    const S3_IMAGE_DIR = "images";
    const S3_DATA_DIR = "data";

    use FileMimeType;
    private $rootPath = "";
    //##################################################################################################################
    //##
    //## >> Override
    //##
    //##################################################################################################################
    public function __construct()
    {
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
    //## >> Method : 3S Upload
    //##
    //##################################################################################################################
    /**
     * 3S Upload : Attach FIle Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $fileName
     * @param array ...$path
     * @return FileS3Exception | File Path
     */
    private function uploadAttachFile($file, $fileName, ...$path)
    {
        return $this->uploadFile($file, FileS3::S3_ATTACH_DIR, $fileName, ...$path);
    }

    /**
     * 3S Upload : Image FIle Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $fileName
     * @param array ...$path
     * @return FileS3Exception | File Path
     */
    private function uploadImageFile($file, $fileName, ...$path)
    {
        return $this->uploadFile($file, FileS3::S3_IMAGE_DIR, $fileName, ...$path);
    }

    /**
     * 3S Upload : Data FIle Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $fileName
     * @param array ...$path
     * @return FileS3Exception | File Path
     */
    private function uploadDataFile($file, $fileName, ...$path)
    {
        return $this->uploadFile($file, FileS3::S3_DATA_DIR, $fileName, ...$path);
    }

    /**
     * 3S Upload : Attach Stream Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $stream
     * @param $fileName
     * @param array ...$path
     * @return FileS3Exception | File Path
     */
    private function uploadAttachStream($stream, $fileName, ...$path)
    {
        return $this->uploadStream($stream, FileS3::S3_ATTACH_DIR, $fileName, ...$path);
    }

    /**
     * 3S Upload : Image Stream Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $stream
     * @param $fileName
     * @param array ...$path
     * @return FileS3Exception | File Path
     */
    private function uploadImageStream($stream, $fileName, ...$path)
    {
        return $this->uploadStream($stream, FileS3::S3_IMAGE_DIR, $fileName, ...$path);
    }

    /**
     * 3S Upload : Data Stream Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $stream
     * @param $fileName
     * @param array ...$path
     * @return FileS3Exception | File Path
     */
    private function uploadDataStream($stream, $fileName, ...$path)
    {
        return $this->uploadStream($stream, FileS3::S3_DATA_DIR, $fileName, ...$path);
    }

    /**
     * 3S Upload : FIle Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $path
     * @param $rootPath
     * @param $fileName
     * @param $file
     * @return FileResult
     * @throws FileS3Exception
     */
    private function uploadFile($file, $rootPath, $fileName, ...$path)
    {
        if(empty($file) || !($file instanceof UploadedFile)) throw  new FileS3Exception("파일이 없습니다.");
        if(empty($rootPath) && empty($this->rootPath)) throw  new FileS3Exception("Root Path 정보가 없습니다.");
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        $ex =  $file->getClientOriginalExtension();
        if(empty($fileName) || !is_string($fileName)){
            $fileName = sprintf("%s.%s", (string)currentTimeMillis(), $ex);
        }
        if(empty($rootPath) || !is_string($rootPath))$rootPath = $this->rootPath;

        $pathArray = array(sprintf("/%s", $rootPath));
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }

        $upload_success = Storage::disk('s3')->putFileAs(join('',$pathArray), $file, $fileName, 'public');
        if(empty($upload_success)) throw  new FileS3Exception("업로드에 실패 했습니다.");
        return new FileResult(sprintf("/%s", $upload_success), $fileName, $ex, $file->getSize());
    }

    /**
     * 3S Upload : Image Resize Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $path
     * @param $rootPath
     * @param $fileName
     * @param $width
     * @param $height
     * @return null
     * @throws FileS3Exception
     */
    private function uploadResizeImage($file, $rootPath, $fileName, $width, $height, ...$path)
    {
        if(empty($file) || !($file instanceof UploadedFile)) throw  new FileS3Exception("파일이 없습니다.");
        if(empty($rootPath) && empty($this->rootPath)) throw  new FileS3Exception("Root Path 정보가 없습니다.");
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        if(empty($width) || !(is_numeric($width))) throw  new FileS3Exception("조절할 폭을 숫자로 입력해 주세요.");
        if(empty($height) || !(is_numeric($height))) throw  new FileS3Exception("조절할 높이을 숫자로 입력해 주세요.");
        $ex =  $file->getClientOriginalExtension();
        if(empty($fileName) || !is_string($fileName)){
            $fileName = sprintf("%s.%s", (string)currentTimeMillis(), $ex);
        }
        if(empty($rootPath) || !is_string($rootPath))$rootPath = $this->rootPath;

        $pathArray = array(sprintf("/%s", $rootPath));
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }
        array_push($pathArray, sprintf("/%s", $fileName));

        //### Image Resize
        $tempFile = Image::make($file)->fit($width, $height, function($constraint){$constraint->upsize();})->stream();
        $upload_success = Storage::disk('s3')->put(join('',$pathArray), $tempFile->__toString(), 'public');
        if(empty($upload_success)) throw  new FileS3Exception("업로드에 실패 했습니다.");
        $size = Storage::disk('s3')->size(join('', $pathArray));
        return new FileResult(join('', $pathArray), $fileName, $ex, $size);
    }

    /**
     *  3S Upload : File Stream Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $path
     * @param $fileName
     * @param $rootPath
     * @param $stream
     * @return mixed
     * @throws FileS3Exception
     */
    private function uploadStream($stream, $rootPath, $fileName, ...$path)
    {
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        if(empty($fileName) || !is_string($fileName)) throw  new FileS3Exception("파일 이름 정보가 없습니다.");
        if(empty($rootPath) || !is_string($rootPath))$rootPath = $this->rootPath;

        $pathArray = array(sprintf("/%s", $rootPath));
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }
        array_push($pathArray, sprintf("/%s", $fileName));

        $upload_success = Storage::disk('s3')->put(join('',$pathArray), $stream,'public');
        if(empty($upload_success)) throw  new FileS3Exception("업로드에 실패 했습니다.");
        $size = Storage::disk('s3')->size(join('', $pathArray));
        return new FileResult(join('', $pathArray), $fileName, stringPathExtension($fileName), $size);
    }

    /**
     * 3S Upload : File Upload
     * -----------------------------------------------------------------------------------------------------------------
     * @param $file
     * @param $pathStr
     * @param $fileName
     * @return bool
     * @throws FileS3Exception
     */
    private function upload($file, $pathStr, $fileName = '')
    {
        if(empty($pathStr)) throw  new FileS3Exception("경로 정보가 없습니다.");
        $ex =  $file->getClientOriginalExtension();
        if(empty($fileName) || !is_string($fileName)){
            $fileName = sprintf("%s.%s", (string)currentTimeMillis(), $ex);
        }

        $upload_success = Storage::disk('s3')->putFileAs($pathStr, $file, $fileName, 'public');
        if(empty($upload_success)) throw  new FileS3Exception("업로드에 실패 했습니다.");
        return new FileResult(sprintf("/%s", $upload_success), $fileName, $ex, $file->getSize());
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Get File
    //##
    //##################################################################################################################
    /**
     * 3S Get File : 웹 파일
     * -----------------------------------------------------------------------------------------------------------------
     * @param $mimeType
     * @param $rootPath
     * @param array ...$path
     * @return \Illuminate\Http\Response
     */
    private function getWebFile($mimeType = 'data', $rootPath, ...$path)
    {
        if(empty($path))return response()->make(null, 404);
        if(empty($rootPath) || !is_string($rootPath))$rootPath = $this->rootPath;

        $pathArray = array(sprintf("/%s", $rootPath));
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }

        //### 파일 캐싱
        $minutes = 60 * 12 * 30;    // 30 일
        $file = Cache::remember(join('',$pathArray), $minutes, function() use ($pathArray)
        {
            return Storage::disk('s3')->get(join('',$pathArray));
        });
        return response()->make($file, 200, array('content-type' => $this->mimeType[$mimeType]));
    }

    /**
     * 3S Get File : Zip File
     * -----------------------------------------------------------------------------------------------------------------
     * @param $filePathStrings
     * @param string $zipFileName
     * @return \Illuminate\Http\Response
     */
    private function getZipFile($filePathStrings, $zipFileName = '')
    {
        if(!class_exists(S3ObjectsStreamZip::class))return response()->make(null, 404);
        try {
            /*
             |----------------------------------------------------------------------------------------------------------
             | S3ObjectsStreamZip 참고
             |----------------------------------------------------------------------------------------------------------
             | http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html#hardcoded-credentials
             | option
             | version : http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html#version
             | region  : http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html#region
             |
             */
            $zipStream = new S3ObjectsStreamZip(array(
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => array(
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                )
            ));

            $bucket = env('AWS_BUCKET');
            $addFiles = array();
            foreach ($filePathStrings as $val){
                $fileName = stringPathFileName($val);
                if(empty($fileName))$fileName = currentTimeMillis();
                array_push($addFiles, array('path' => substr($val, 1), 'name' => $fileName));
            }

            if(empty($zipFileName))$zipFileName = sprintf("%s_%s", env('APP_NAME'), date("YmdHis"));
            $zipName = $zipFileName.'.zip'; // required

            $zipStream->sendObjects($bucket, $addFiles, $zipName);
        }
        catch (InvalidParamsException $e) {
            echo $e->getMessage();
        }
        catch (S3Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 3S Get File : 썹네일 파일
     * -----------------------------------------------------------------------------------------------------------------
     * @param $width
     * @param $height
     * @param $pathStr
     * @return \Illuminate\Http\Response
     */
    private function getResizeCropImage($width, $height, $pathStr)
    {
        if(empty($width) || !is_numeric($width)
            || empty($height) || !is_numeric($height)
            || empty($pathStr) || !(is_string($pathStr))
        )return response()->make(null, 404);
        if(!class_exists(Image::class))return response()->make(null, 404);

        $minutes = 60 * 12 * 30;    // 30 일
        try {
            $imageUrl = url($pathStr);
            $img = Image::cache(function($image) use($width, $height, $imageUrl){
                $image->make($imageUrl)->fit($width, $height);
            }, $minutes, false);
        } catch (NotReadableException $e) {
            return response()->make(null, 404);
        }
        return response()->make($img, 200, array('content-type' => 'image/png'));
    }

    /**
     * 3S Get File : 파일
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathStr
     * @return mixed
     */
    private function getFIle($pathStr)
    {
        //### 파일 캐싱
        $minutes = 60 * 12 * 30;
        $file = Cache::remember($pathStr, $minutes, function() use ($pathStr)
        {
            return Storage::disk('s3')->get($pathStr);
        });

        return $file;
    }

    //##################################################################################################################
    //##
    //## >> Method : 3S Delete File
    //##
    //##################################################################################################################
    /**
     * 3S Delete File : 데이터 경로 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delDataDirectory(...$path)
    {
        return $this->delDirectory(FileS3::S3_IMAGE_DIR, $path);
    }

    /**
     * 3S Delete File : 이미지 경로 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delImageDirectory(...$path)
    {
        return $this->delDirectory(FileS3::S3_File_DIR, $path);
    }


    /**
     * 3S Delete File : 데이터 파일 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param $fileName
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delDataFile($fileName, ...$path)
    {
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        array_unshift($path, FileS3::S3_IMAGE_DIR);
        return $this->delFile($fileName, $path);
    }

    /**
     * 3S Delete File : 이미지 파일 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param $fileName
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delImageFile($fileName, ...$path)
    {
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        array_unshift($path, FileS3::S3_File_DIR);
        return $this->delFile($fileName, $path);
    }

    /**
     * 3S Delete File : Storage 경로 삭제 [...$path]
     * -----------------------------------------------------------------------------------------------------------------
     * @param $rootPath
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delDirectory($rootPath, ...$path)
    {
        if(empty($this->rootPath) && empty($rootPath)) throw  new FileS3Exception("ROOT Path 정보가 없습니다.");
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        if(empty($rootPath) || !is_string($rootPath))$rootPath = $this->rootPath;

        $pathArray = array(sprintf("/%s", $rootPath));
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }
        return Storage::disk('s3')->delete(join('',$pathArray));
    }

    /**
     * 3S Delete File : Storage 파일 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param $rootPath
     * @param $fileName
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delFile($rootPath, $fileName, ...$path)
    {
        if(empty($this->rootPath) && empty($rootPath)) throw  new FileS3Exception("ROOT Path 정보가 없습니다.");
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        if(empty($fileName) || !is_string($fileName)) throw  new FileS3Exception("파일 이름 정보가 없습니다.");
        if(empty($rootPath) || !is_string($rootPath))$rootPath = $this->rootPath;

        $pathArray = array(sprintf("/%s", $rootPath));
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }
        array_push($pathArray, sprintf("/%s", $fileName));
        return Storage::disk('s3')->delete(join('',$pathArray));
    }

    /**
     * 3S Delete File : Storage 경로 삭제 [...$path]
     * -----------------------------------------------------------------------------------------------------------------
     * @param array ...$path
     * @return bool
     * @throws FileS3Exception
     */
    private function delPath(...$path)
    {
        if(empty($path)) throw  new FileS3Exception("경로 정보가 없습니다.");
        $pathArray = array();
        foreach ($path as $val){
            if(is_array($val)){
                foreach ($val as $value){
                    array_push($pathArray, sprintf("/%s", (string)$value));
                }
            }else{
                array_push($pathArray, sprintf("/%s", (string)$val));
            }
        }
        return Storage::disk('s3')->delete(join('',$pathArray));
    }

    /**
     * 3S : Storage 파일 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param $url
     * @return bool
     * @throws FileS3Exception
     */
    private function delFileUrl($url)
    {
        if(empty($url)) throw new FileS3Exception("URL 정보가 없습니다.");
        $pattern = sprintf("/^.*\/(%s|%s|%s)\//i", FileS3::S3_IMAGE_DIR, FileS3::S3_ATTACH_DIR, FileS3::S3_DATA_DIR);
        if(!preg_match($pattern, $url)) throw  new FileS3Exception("잘못된 URL 형태입니다.");

        $path = "/".preg_replace('/^.*\/ew\//i', '', $url);
        return Storage::disk('s3')->delete($path);
    }

    /**
     * 3S : Storage 파일 삭제
     * -----------------------------------------------------------------------------------------------------------------
     * @param $pathStr
     * @return bool
     * @throws FileS3Exception
     */
    private function delete($pathStr)
    {
        if(empty($pathStr)) throw  new FileS3Exception("경로 정보가 없습니다.");
        return Storage::disk('s3')->delete($pathStr);
    }
}