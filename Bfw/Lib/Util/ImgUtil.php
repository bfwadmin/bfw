<?php
namespace Lib\Util;

/**
 * @author wangbo
 * 图像辅助类
 */
class ImgUtil
{

    public static function GrabImage($url, $filename = "")
    {
        if ($url == "") :
            return false;

        endif;

        if ($filename == "") {
            $ext = strrchr($url, ".");
            if ($ext != ".gif" && $ext != ".jpg") :
                return false;

            endif;
            $filename = date("dMYHis") . $ext;
        }
        if (! file_exists($filename)) {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
            $size = strlen($img);
            FileUtil::CreatDir(dirname($filename));
            $fp2 = @fopen($filename, "a");
            fwrite($fp2, $img);
            fclose($fp2);
            $img = null;
        }

        return $filename;
    }
}

?>