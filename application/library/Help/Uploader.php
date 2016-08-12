<?php

/**
 * Created by PhpStorm.
 * User: Ellipsis
 * Date: 2015/6/26
 * Time: 10:48
 */
namespace Help;

class Uploader
{

    private $fileField; //文件域名
    private $file; //文件上传对象
    private $base64; //文件上传对象
    private $config; //配置信息
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,
    private $size; //压缩文件尺寸,
    private $width;
    private $height;
    private $stateMap = array(//上传状态映射表
        "SUCCESS",
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确"
    );

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct($config, $fileField = 'pic', $size = array(), $type = "upload")
    {
        $this->size = $size;
        $this->fileField = $fileField;
        $this->config = $config;
        $this->type = $type;
        if ($type == "remote") {
            $this->saveRemote();
        } else if ($type == "base64") {
            $this->upBase64();
        } else {
            $this->upFile();
        }

        $this->stateMap['ERROR_TYPE_NOT_ALLOWED'] = @iconv('unicode', 'utf-8//IGNORE', $this->stateMap['ERROR_TYPE_NOT_ALLOWED']);
    }

    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upFile()
    {
        $file = $this->file = @$_FILES[$this->fileField];
        if (!$file) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMPFILE");
            return;
        }

        $this->oriName = $file['name'];
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        $imginfo = getimagesize($file['tmp_name']);

        $this->width = $imginfo[0];
        $this->height = $imginfo[1];

        //压缩
        if (!empty($this->size)) {

            $resize = $this->setSize($this->width, $this->height);
            if ($this->width != $resize[0] OR $this->height != $resize[1]) {//压缩
                $this->make_thumb($file['tmp_name'], $this->filePath, $resize[0], $resize[1]);
                unlink($file['tmp_name']);
                if (!file_exists($this->filePath)) {//压缩失败
                    $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
                }
                $this->stateInfo = $this->stateMap[0];
                return;
            }
        }

        //移动文件
        if (!(move_uploaded_file($file["tmp_name"], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 处理base64编码的图片上传
     * @return mixed
     */
    private function upBase64()
    {
        $base64Data = $_POST[$this->fileField];
        $img = base64_decode($base64Data);

        $this->oriName = $this->config['oriName'];
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 拉取远程图片
     * @return mixed
     */
    private function saveRemote()
    {
        $imgUrl = htmlspecialchars($this->fileField);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);

        //http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_LINK");
            return;
        }
        //获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = $this->getStateInfo("ERROR_DEAD_LINK");
            return;
        }
        //格式验证(扩展名验证和Content-Type验证)
        if (@stristr($heads['Content-Type'], "image") == false) {
            $this->stateInfo = $this->getStateInfo("ERROR_HTTP_CONTENTTYPE");
            return;
        }

        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $this->oriName = $m ? $m[1] : "";
        $this->fileSize = strlen($img);
        if ($this->getFileExt() == "") {
            if (@stristr("png", $heads['Content-Type'])) {
                $this->fileType = 'png';
                $this->fullName = $this->getFullName() . ".png";
            } elseif (@stristr("gif", $heads['Content-Type'])) {
                $this->fileType = 'gif';
                $this->fullName = $this->getFullName() . ".gif";
            } else {
                $this->fileType = 'jpg';
                $this->fullName = $this->getFullName() . ".jpg";
            }
        } else {
            $this->fileType = $this->getFileExt();
            $this->fullName = $this->getFullName();
        }
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo($errCode)
    {
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower(strrchr($this->oriName, '.'));
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName()
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $this->config["pathFormat"];
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);

        //过滤文件名的非法自负,并替换文件名
        $oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);

        //替换随机字符串
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        $ext = $this->getFileExt();
        return $format . $ext;
    }

    /**
     * 获取文件名
     * @return string
     */
    private function getFileName()
    {
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 获取文件完整路径
     * @return string
     */
    private function getFilePath()
    {
        $fullname = $this->fullName;
        $rootPath = $_SERVER['DOCUMENT_ROOT'];

        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }

        return $rootPath . $fullname;
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        return in_array($this->getFileExt(), $this->config["allowFiles"]);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function checkSize()
    {
        return $this->fileSize <= ($this->config["maxSize"]);
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {
        return array(
            "state" => $this->stateInfo,
            "url" => $this->fullName,
            "title" => $this->fileName,
            "original" => $this->oriName,
            "type" => $this->fileType,
            "size" => $this->fileSize,
            "height" => $this->height,
            "width" => $this->width
        );
    }

    /**
     * Set thumb image width and height
     */
    function setSize($imgw, $imgh)
    {

        if (empty($this->size['width'])) {//指定最大高度，宽度自适应
            $newHeight = $this->size['height'] > $imgh ? $imgh : $this->size['height'];
            $newWidth = round(($newHeight * $imgw) / $imgh);
            return array($newWidth, $newHeight);
        }
        if (empty($this->size['height'])) {//指定最大宽度，高度自适应
            $newWidth = $this->size['width'] > $imgw ? $imgw : $this->size['width'];
            $newHeight = round(($newWidth * $imgh) / $imgw);
            return array($newWidth, $newHeight);
        }

        if ($imgw > $this->size['width'] OR $imgh > $this->size['height']) {
            if ($imgw * $this->size['height'] > $imgh * $this->size['width']) {
                $newWidth = $this->size['width'];
                $newHeight = round(($newWidth * $imgh) / $imgw);
            } else {
                $newHeight = $this->size['height'];
                $newWidth = round(($newHeight * $imgw) / $imgh);
            }
        } else {
            $newHeight = $imgh;
            $newWidth = $imgw;
        }
        return array($newWidth, $newHeight);
    }

    /**
     * 生成缩略图辅助函数
     * @param  $source_file 图片文件名
     * @param  $dest_file 另存文件名
     * @param  $dest_width  图片保存宽度
     * @param  $dest_height 图片保存高度
     * @param  $rate 图片保存品质
     * @return boolean
     */
    function make_thumb($source_file, $dest_file, $dest_width, $dest_height, $rate = 100)
    {
        $data = GetImageSize($source_file);

        switch ($data[2]) {
            case 1:
                $im = @ImageCreateFromGIF($source_file);
                break;
            case 2:
                $im = @ImageCreateFromJPEG($source_file);
                break;
            case 3:
                $im = @ImageCreateFromPNG($source_file);
                break;
        }

        if (!$im)
            return false;

        $src_w = ImageSX($im);
        $src_h = ImageSY($im);
        $dst_x = 0;
        $dst_y = 0;

        if ($src_w * $dest_height > $src_h * $dest_width) {
            $fdst_h = round($src_h * $dest_width / $src_w);
            $dst_y = floor(($dest_height - $fdst_h) / 2);
            $fdst_w = $dest_width;
        } else {
            $fdst_w = round($src_w * $dest_height / $src_h);
            $dst_x = floor(($dest_width - $fdst_w) / 2);
            $fdst_h = $dest_height;
        }

        $ni = ImageCreateTrueColor($dest_width, $dest_height);
        $dst_x = ($dst_x < 0) ? 0 : $dst_x;
        $dst_y = ($dst_x < 0) ? 0 : $dst_y;
        $dst_x = ($dst_x > ($dest_width / 2)) ? floor($dest_width / 2) : $dst_x;
        $dst_y = ($dst_y > ($dest_height / 2)) ? floor($dest_height / s) : $dst_y;
        $white = ImageColorAllocate($ni, 255, 255, 255);
        $black = ImageColorAllocate($ni, 0, 0, 0);
        imagefilledrectangle($ni, 0, 0, $dest_width, $dest_height, $white); ## 填充背景色
        ImageCopyResized($ni, $im, $dst_x, $dst_y, 0, 0, $fdst_w, $fdst_h, $src_w, $src_h);
        ImageJpeg($ni, $dest_file, $rate);
        imagedestroy($im);
        imagedestroy($ni);
    }

}
