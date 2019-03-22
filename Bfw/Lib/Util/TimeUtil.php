<?php
namespace Lib\Util;
class TimeUtil
{

    /**
     * 获取毫秒
     * @param string $format
     * @param string $utimestamp
     * @return string
     */
   public static  function udate($format = 'u', $utimestamp = null)
    {
        if (is_null($utimestamp))
            $utimestamp = microtime(true);
        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);
        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }
    public static  function microtime(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

?>