<?php
namespace Lib\Util;

/**
 *
 * @author wangbo
 *         字符辅助类
 */
class StringUtil
{

    /**
     * 拼音字符转换图
     *
     * @var array
     */
    private static $_aMaps = array(
        'a' => - 20319,
        'ai' => - 20317,
        'an' => - 20304,
        'ang' => - 20295,
        'ao' => - 20292,
        'ba' => - 20283,
        'bai' => - 20265,
        'ban' => - 20257,
        'bang' => - 20242,
        'bao' => - 20230,
        'bei' => - 20051,
        'ben' => - 20036,
        'beng' => - 20032,
        'bi' => - 20026,
        'bian' => - 20002,
        'biao' => - 19990,
        'bie' => - 19986,
        'bin' => - 19982,
        'bing' => - 19976,
        'bo' => - 19805,
        'bu' => - 19784,
        'ca' => - 19775,
        'cai' => - 19774,
        'can' => - 19763,
        'cang' => - 19756,
        'cao' => - 19751,
        'ce' => - 19746,
        'ceng' => - 19741,
        'cha' => - 19739,
        'chai' => - 19728,
        'chan' => - 19725,
        'chang' => - 19715,
        'chao' => - 19540,
        'che' => - 19531,
        'chen' => - 19525,
        'cheng' => - 19515,
        'chi' => - 19500,
        'chong' => - 19484,
        'chou' => - 19479,
        'chu' => - 19467,
        'chuai' => - 19289,
        'chuan' => - 19288,
        'chuang' => - 19281,
        'chui' => - 19275,
        'chun' => - 19270,
        'chuo' => - 19263,
        'ci' => - 19261,
        'cong' => - 19249,
        'cou' => - 19243,
        'cu' => - 19242,
        'cuan' => - 19238,
        'cui' => - 19235,
        'cun' => - 19227,
        'cuo' => - 19224,
        'da' => - 19218,
        'dai' => - 19212,
        'dan' => - 19038,
        'dang' => - 19023,
        'dao' => - 19018,
        'de' => - 19006,
        'deng' => - 19003,
        'di' => - 18996,
        'dian' => - 18977,
        'diao' => - 18961,
        'die' => - 18952,
        'ding' => - 18783,
        'diu' => - 18774,
        'dong' => - 18773,
        'dou' => - 18763,
        'du' => - 18756,
        'duan' => - 18741,
        'dui' => - 18735,
        'dun' => - 18731,
        'duo' => - 18722,
        'e' => - 18710,
        'en' => - 18697,
        'er' => - 18696,
        'fa' => - 18526,
        'fan' => - 18518,
        'fang' => - 18501,
        'fei' => - 18490,
        'fen' => - 18478,
        'feng' => - 18463,
        'fo' => - 18448,
        'fou' => - 18447,
        'fu' => - 18446,
        'ga' => - 18239,
        'gai' => - 18237,
        'gan' => - 18231,
        'gang' => - 18220,
        'gao' => - 18211,
        'ge' => - 18201,
        'gei' => - 18184,
        'gen' => - 18183,
        'geng' => - 18181,
        'gong' => - 18012,
        'gou' => - 17997,
        'gu' => - 17988,
        'gua' => - 17970,
        'guai' => - 17964,
        'guan' => - 17961,
        'guang' => - 17950,
        'gui' => - 17947,
        'gun' => - 17931,
        'guo' => - 17928,
        'ha' => - 17922,
        'hai' => - 17759,
        'han' => - 17752,
        'hang' => - 17733,
        'hao' => - 17730,
        'he' => - 17721,
        'hei' => - 17703,
        'hen' => - 17701,
        'heng' => - 17697,
        'hong' => - 17692,
        'hou' => - 17683,
        'hu' => - 17676,
        'hua' => - 17496,
        'huai' => - 17487,
        'huan' => - 17482,
        'huang' => - 17468,
        'hui' => - 17454,
        'hun' => - 17433,
        'huo' => - 17427,
        'ji' => - 17417,
        'jia' => - 17202,
        'jian' => - 17185,
        'jiang' => - 16983,
        'jiao' => - 16970,
        'jie' => - 16942,
        'jin' => - 16915,
        'jing' => - 16733,
        'jiong' => - 16708,
        'jiu' => - 16706,
        'ju' => - 16689,
        'juan' => - 16664,
        'jue' => - 16657,
        'jun' => - 16647,
        'ka' => - 16474,
        'kai' => - 16470,
        'kan' => - 16465,
        'kang' => - 16459,
        'kao' => - 16452,
        'ke' => - 16448,
        'ken' => - 16433,
        'keng' => - 16429,
        'kong' => - 16427,
        'kou' => - 16423,
        'ku' => - 16419,
        'kua' => - 16412,
        'kuai' => - 16407,
        'kuan' => - 16403,
        'kuang' => - 16401,
        'kui' => - 16393,
        'kun' => - 16220,
        'kuo' => - 16216,
        'la' => - 16212,
        'lai' => - 16205,
        'lan' => - 16202,
        'lang' => - 16187,
        'lao' => - 16180,
        'le' => - 16171,
        'lei' => - 16169,
        'leng' => - 16158,
        'li' => - 16155,
        'lia' => - 15959,
        'lian' => - 15958,
        'liang' => - 15944,
        'liao' => - 15933,
        'lie' => - 15920,
        'lin' => - 15915,
        'ling' => - 15903,
        'liu' => - 15889,
        'long' => - 15878,
        'lou' => - 15707,
        'lu' => - 15701,
        'lv' => - 15681,
        'luan' => - 15667,
        'lue' => - 15661,
        'lun' => - 15659,
        'luo' => - 15652,
        'ma' => - 15640,
        'mai' => - 15631,
        'man' => - 15625,
        'mang' => - 15454,
        'mao' => - 15448,
        'me' => - 15436,
        'mei' => - 15435,
        'men' => - 15419,
        'meng' => - 15416,
        'mi' => - 15408,
        'mian' => - 15394,
        'miao' => - 15385,
        'mie' => - 15377,
        'min' => - 15375,
        'ming' => - 15369,
        'miu' => - 15363,
        'mo' => - 15362,
        'mou' => - 15183,
        'mu' => - 15180,
        'na' => - 15165,
        'nai' => - 15158,
        'nan' => - 15153,
        'nang' => - 15150,
        'nao' => - 15149,
        'ne' => - 15144,
        'nei' => - 15143,
        'nen' => - 15141,
        'neng' => - 15140,
        'ni' => - 15139,
        'nian' => - 15128,
        'niang' => - 15121,
        'niao' => - 15119,
        'nie' => - 15117,
        'nin' => - 15110,
        'ning' => - 15109,
        'niu' => - 14941,
        'nong' => - 14937,
        'nu' => - 14933,
        'nv' => - 14930,
        'nuan' => - 14929,
        'nue' => - 14928,
        'nuo' => - 14926,
        'o' => - 14922,
        'ou' => - 14921,
        'pa' => - 14914,
        'pai' => - 14908,
        'pan' => - 14902,
        'pang' => - 14894,
        'pao' => - 14889,
        'pei' => - 14882,
        'pen' => - 14873,
        'peng' => - 14871,
        'pi' => - 14857,
        'pian' => - 14678,
        'piao' => - 14674,
        'pie' => - 14670,
        'pin' => - 14668,
        'ping' => - 14663,
        'po' => - 14654,
        'pu' => - 14645,
        'qi' => - 14630,
        'qia' => - 14594,
        'qian' => - 14429,
        'qiang' => - 14407,
        'qiao' => - 14399,
        'qie' => - 14384,
        'qin' => - 14379,
        'qing' => - 14368,
        'qiong' => - 14355,
        'qiu' => - 14353,
        'qu' => - 14345,
        'quan' => - 14170,
        'que' => - 14159,
        'qun' => - 14151,
        'ran' => - 14149,
        'rang' => - 14145,
        'rao' => - 14140,
        're' => - 14137,
        'ren' => - 14135,
        'reng' => - 14125,
        'ri' => - 14123,
        'rong' => - 14122,
        'rou' => - 14112,
        'ru' => - 14109,
        'ruan' => - 14099,
        'rui' => - 14097,
        'run' => - 14094,
        'ruo' => - 14092,
        'sa' => - 14090,
        'sai' => - 14087,
        'san' => - 14083,
        'sang' => - 13917,
        'sao' => - 13914,
        'se' => - 13910,
        'sen' => - 13907,
        'seng' => - 13906,
        'sha' => - 13905,
        'shai' => - 13896,
        'shan' => - 13894,
        'shang' => - 13878,
        'shao' => - 13870,
        'she' => - 13859,
        'shen' => - 13847,
        'sheng' => - 13831,
        'shi' => - 13658,
        'shou' => - 13611,
        'shu' => - 13601,
        'shua' => - 13406,
        'shuai' => - 13404,
        'shuan' => - 13400,
        'shuang' => - 13398,
        'shui' => - 13395,
        'shun' => - 13391,
        'shuo' => - 13387,
        'si' => - 13383,
        'song' => - 13367,
        'sou' => - 13359,
        'su' => - 13356,
        'suan' => - 13343,
        'sui' => - 13340,
        'sun' => - 13329,
        'suo' => - 13326,
        'ta' => - 13318,
        'tai' => - 13147,
        'tan' => - 13138,
        'tang' => - 13120,
        'tao' => - 13107,
        'te' => - 13096,
        'teng' => - 13095,
        'ti' => - 13091,
        'tian' => - 13076,
        'tiao' => - 13068,
        'tie' => - 13063,
        'ting' => - 13060,
        'tong' => - 12888,
        'tou' => - 12875,
        'tu' => - 12871,
        'tuan' => - 12860,
        'tui' => - 12858,
        'tun' => - 12852,
        'tuo' => - 12849,
        'wa' => - 12838,
        'wai' => - 12831,
        'wan' => - 12829,
        'wang' => - 12812,
        'wei' => - 12802,
        'wen' => - 12607,
        'weng' => - 12597,
        'wo' => - 12594,
        'wu' => - 12585,
        'xi' => - 12556,
        'xia' => - 12359,
        'xian' => - 12346,
        'xiang' => - 12320,
        'xiao' => - 12300,
        'xie' => - 12120,
        'xin' => - 12099,
        'xing' => - 12089,
        'xiong' => - 12074,
        'xiu' => - 12067,
        'xu' => - 12058,
        'xuan' => - 12039,
        'xue' => - 11867,
        'xun' => - 11861,
        'ya' => - 11847,
        'yan' => - 11831,
        'yang' => - 11798,
        'yao' => - 11781,
        'ye' => - 11604,
        'yi' => - 11589,
        'yin' => - 11536,
        'ying' => - 11358,
        'yo' => - 11340,
        'yong' => - 11339,
        'you' => - 11324,
        'yu' => - 11303,
        'yuan' => - 11097,
        'yue' => - 11077,
        'yun' => - 11067,
        'za' => - 11055,
        'zai' => - 11052,
        'zan' => - 11045,
        'zang' => - 11041,
        'zao' => - 11038,
        'ze' => - 11024,
        'zei' => - 11020,
        'zen' => - 11019,
        'zeng' => - 11018,
        'zha' => - 11014,
        'zhai' => - 10838,
        'zhan' => - 10832,
        'zhang' => - 10815,
        'zhao' => - 10800,
        'zhe' => - 10790,
        'zhen' => - 10780,
        'zheng' => - 10764,
        'zhi' => - 10587,
        'zhong' => - 10544,
        'zhou' => - 10533,
        'zhu' => - 10519,
        'zhua' => - 10331,
        'zhuai' => - 10329,
        'zhuan' => - 10328,
        'zhuang' => - 10322,
        'zhui' => - 10315,
        'zhun' => - 10309,
        'zhuo' => - 10307,
        'zi' => - 10296,
        'zong' => - 10281,
        'zou' => - 10274,
        'zu' => - 10270,
        'zuan' => - 10262,
        'zui' => - 10260,
        'zun' => - 10256,
        'zuo' => - 10254
    );

    /**
     * 将中文编码成拼音
     *
     * @param string $utf8Data
     *            utf8字符集数据
     * @param string $sRetFormat
     *            返回格式 [head:首字母|all:全拼音]
     * @return string
     */
    public static function ToPinyin($utf8Data, $sRetFormat = 'head', $_delimeter = "")
    {
        $sGBK = iconv('UTF-8', 'GBK', $utf8Data);
        $aBuf = array();
        for ($i = 0, $iLoop = strlen($sGBK); $i < $iLoop; $i ++) {
            $iChr = ord($sGBK{$i});
            if ($iChr > 160)
                $iChr = ($iChr << 8) + ord($sGBK{++ $i}) - 65536;
            if ('head' === $sRetFormat)
                $aBuf[] = substr(self::zh2pyonce($iChr), 0, 1);
            else
                $aBuf[] = self::zh2pyonce($iChr);
        }
        if ('head' === $sRetFormat)
            return implode($_delimeter, $aBuf);
        else
            return implode($_delimeter, $aBuf);
    }

    /**
     * 中文转换到拼音(每次处理一个字符)
     *
     * @param number $iWORD
     *            待处理字符双字节
     * @return string 拼音
     */
    private static function zh2pyonce($iWORD)
    {
        if ($iWORD > 0 && $iWORD < 160) {
            return chr($iWORD);
        } elseif ($iWORD < - 20319 || $iWORD > - 10247) {
            return '';
        } else {
            foreach (self::$_aMaps as $py => $code) {
                if ($code > $iWORD)
                    break;
                $result = $py;
            }
            return $result;
        }
    }

    /**
     * 二进制转成text
     *
     * @param string $bin_str
     * @return string
     */
    public static function bin2text($bin_str)
    {
        $text_str = '';
        $chars = explode("\n", chunk_split(str_replace("\n", '', $bin_str), 8));
        $_I = count($chars);
        for ($i = 0; $i < $_I; $text_str .= chr(bindec($chars[$i])), $i);
        return $text_str;
    }

    /**
     * text转二进制
     *
     * @param string $txt_str
     * @return string
     */
    public static function text2bin($txt_str)
    {
        $len = strlen($txt_str);
        $bin = '';
        for ($i = 0; $i < $len; $i) {
            $bin .= strlen(decbin(ord($txt_str[$i]))) < 8 ? str_pad(decbin(ord($txt_str[$i])), 8, 0, STR_PAD_LEFT) : decbin(ord($txt_str[$i]));
        }
        return $bin;
    }

    /**
     * 生成随机字符串
     *
     * @param int $length
     * @param bool $_onlynumber
     *            是否是数字
     * @return string
     */
    public static function getRandChar($length, $_onlynumber = false)
    {
        $str = null;
        $strPol = "";
        if ($_onlynumber) {
            $strPol = "0123456789";
        } else {
            $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        }

        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i ++) {
            $str .= $strPol[rand(0, $max)]; // rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    /**
     * 32位唯一码
     *
     * @return string
     */
    public static function UniqId()
    {
        return md5(uniqid(HOST_NAME . SERVER_PORT . APPSELF, true));
        // return md5(uniqid("",true));
    }

    /**
     * 时间id
     * 20位
     *
     * @return string
     */
    public static function TimeId()
    {
        list ($usec, $sec) = explode(" ", microtime());
        return $sec . str_replace("0.", "", $usec) . rand(10, 99);
    }

    /**
     * 生成订单id
     *
     * @return string
     */
    public static function orderid()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 获得唯一吗
     *
     * @return string
     */
    public static function guid()
    {
        if (function_exists('com_create_guid')) {
            return strtolower(str_replace("-", "", com_create_guid()));
        } else {
            mt_srand((double) microtime() * 10000); // optional for php 4.2.0 //
                                                    // and up.
            $charid = strtolower(md5(uniqid(rand(), true)));
            // $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . substr($charid, 8, 4) . substr($charid, 12, 4) . substr($charid, 16, 4) . substr($charid, 20, 12); // "}"
            return $uuid;
        }
    }

    /**
     * 隐藏号码中间段，如// 14000234 1400**234
     *
     * @param string $str
     * @return string
     */
    public static function half_replace($str)
    {
        if (preg_match("/[\x7f-\xff]/", $str)) {
            return $str;
        }
        if (strlen($str) <= 5) {
            return $str;
        }
        $len = strlen($str) / 2;
        return substr_replace($str, str_repeat('*', $len), ceil(($len) / 2), $len);
    }

    /**
     * 截取字符串
     *
     * @param string $string
     * @param number $sublen
     * @param number $start
     * @param string $code
     * @return string
     */
    public static function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
    {
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen) {
                return join('', array_slice($t_string[0], $start, $sublen)) . "...";
            } else {
                return join('', array_slice($t_string[0], $start, $sublen));
            }
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; $i ++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129) {
                    $i ++;
                }
            }
            if (strlen($tmpstr) < $strlen) {
                $tmpstr .= "...";
            }
            return $tmpstr;
        }
    }

    /**
     * gb2312转utf-8
     *
     * @param string $gb
     * @return string
     */
    public static function gb2u($gb)
    {
        return mb_convert_encoding($gb, "UTF-8", "gb2312");
    }

    /**
     * utf-8转gb2312
     *
     * @param string $gb
     * @return string
     */
    public static function u2gb($gb)
    {
        $gb = str_replace("㎡", "平米", $gb);
        // $str = mb_convert_encoding ( $gb, "GB2312", "UTF-8" );
        // return iconv ( "GB2312", "GB2312", $str );
        return iconv("UTF-8", "GB2312//IGNORE", $gb);
    }

    /**
     * 格式化价格
     *
     * @param decimal $money
     * @return string
     */
    public static function Formatmoney($money)
    {
        if ($money == "") {
            return '0 元';
        } else {
            if ($money / 10000 >= 1) {
                return (float) ($money / 10000) . '万元';
            } else {
                return (float) $money . '元';
            }
        }
    }

    /**
     * 清除html标签
     *
     * @param stirng $str
     * @return string
     */
    public static function ClearHtml($_str)
    {
        return preg_replace("/&[a-zA-Z]+;/", '', strip_tags($_str));
        $str = preg_replace("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i", " ", $str); // 过滤img标签
        $str = preg_replace("/\s+/", " ", $str); // 过滤多余回车
        $str = preg_replace("/<[ ]+/si", "<", $str); // 过滤<__("<"号后面带空格)
        $str = preg_replace("/<\\!--.*?-->/si", "", $str); // 注释
        $str = preg_replace("/<(\\!.*?)>/si", "", $str); // 过滤DOCTYPE
        $str = preg_replace("/<(\/?html.*?)>/si", "", $str); // 过滤html标签
        $str = preg_replace("/<(\/?head.*?)>/si", "", $str); // 过滤head标签
        $str = preg_replace("/<(\/?meta.*?)>/si", "", $str); // 过滤meta标签
        $str = preg_replace("/<(\/?body.*?)>/si", "", $str); // 过滤body标签
        $str = preg_replace("/<(\/?link.*?)>/si", "", $str); // 过滤link标签
        $str = preg_replace("/<(\/?form.*?)>/si", "", $str); // 过滤form标签
        $str = preg_replace("/cookie/si", "COOKIE", $str); // 过滤COOKIE标签
        $str = preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si", "", $str); // 过滤applet标签
        $str = preg_replace("/<(\/?applet.*?)>/si", "", $str); // 过滤applet标签
        $str = preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si", "", $str); // 过滤style标签
        $str = preg_replace("/<(\/?style.*?)>/si", "", $str); // 过滤style标签
        $str = preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si", "", $str); // 过滤title标签
        $str = preg_replace("/<(\/?title.*?)>/si", "", $str); // 过滤title标签
        $str = preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si", "", $str); // 过滤object标签
        $str = preg_replace("/<(\/?objec.*?)>/si", "", $str); // 过滤object标签
        $str = preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si", "", $str); // 过滤noframes标签
        $str = preg_replace("/<(\/?noframes.*?)>/si", "", $str); // 过滤noframes标签
        $str = preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si", "", $str); // 过滤frame标签
        $str = preg_replace("/<(\/?i?frame.*?)>/si", "", $str); // 过滤frame标签
        $str = preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si", "", $str); // 过滤script标签
        $str = preg_replace("/<(\/?script.*?)>/si", "", $str); // 过滤script标签
        $str = preg_replace("/javascript/si", "BfwJavascript", $str); // 过滤script标签
        $str = preg_replace("/vbscript/si", "BfwVbscript", $str); // 过滤script标签
        $str = preg_replace("/on([a-z]+)\s*=/si", "BfwOn\\1=", $str); // 过滤script标签
        return preg_replace("/&#/si", "&＃", $str); // 过滤script标签
                                                       // $str = preg_replace('/<[^>]*>/', '', $str);
                                                       // $str = str_replace("&nbsp;", "", $str);
                                                       // return preg_replace('/\s+/', '', $str);
    }

    function filterBadHtml($str)
    {
        $str = preg_replace("/<(\/?html.*?)>/si", "", $str); // 过滤html标签
        $str = preg_replace("/<(\/?head.*?)>/si", "", $str); // 过滤head标签
        $str = preg_replace("/<(\/?meta.*?)>/si", "", $str); // 过滤meta标签
        $str = preg_replace("/<(\/?body.*?)>/si", "", $str); // 过滤body标签
        $str = preg_replace("/<(\/?link.*?)>/si", "", $str); // 过滤link标签
        $str = preg_replace("/<(\/?form.*?)>/si", "", $str); // 过滤form标签
        $str = preg_replace("/cookie/si", "COOKIE", $str); // 过滤COOKIE标签
        $str = preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si", "", $str); // 过滤applet标签
        $str = preg_replace("/<(\/?applet.*?)>/si", "", $str); // 过滤applet标签
        $str = preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si", "", $str); // 过滤style标签
        $str = preg_replace("/<(\/?style.*?)>/si", "", $str); // 过滤style标签
        $str = preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si", "", $str); // 过滤title标签
        $str = preg_replace("/<(\/?title.*?)>/si", "", $str); // 过滤title标签
        $str = preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si", "", $str); // 过滤object标签
        $str = preg_replace("/<(\/?objec.*?)>/si", "", $str); // 过滤object标签
        $str = preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si", "", $str); // 过滤noframes标签
        $str = preg_replace("/<(\/?noframes.*?)>/si", "", $str); // 过滤noframes标签
        $str = preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si", "", $str); // 过滤frame标签
        $str = preg_replace("/<(\/?i?frame.*?)>/si", "", $str); // 过滤frame标签
        $str = preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si", "", $str); // 过滤script标签
        $str = preg_replace("/<(\/?script.*?)>/si", "", $str); // 过滤script标签
        $str = preg_replace("/javascript/si", "BfwJavascript", $str); // 过滤script标签
        $str = preg_replace("/vbscript/si", "BfwVbscript", $str); // 过滤script标签
        $str = preg_replace("/on([a-z]+)\s*=/si", "BfwOn\\1=", $str); // 过滤script标签
        return $str;
    }

    /**
     * 用数组根据数组位置替换字符串
     *
     * @param string $_str
     * @param array $_arr
     * @return string
     */
    public static function ReplaceWithArr($_str, $_arr)
    {
        preg_match_all("/\\[(\\d)\\]/", $_str, $match);
        for ($i = 0; $i < count($match[0]); $i ++) {
            $_str = str_replace($match[0][$i], $_arr[$match[1][$i]], $_str);
        }
        return $_str;
    }

    /**
     * 生成摘要
     *
     * @param (string) $body
     *            正文
     * @param (int) $size
     *            摘要长度
     * @param (int) $format
     *            输入格式 id
     *
     */
    public static function htmlSummary($body, $size, $format = NULL)
    {
        preg_match_all("/[\\.|,|，|。|:|：|\\d]*[\x{4e00}-\x{9fa5}]+/u", $body, $chinese);
        return self::cut_str(implode("", $chinese[0]), $size);
    }

    /**
     * 根据正则获取内容
     * @param unknown $_str
     * @param unknown $_reg
     */
    public static function GetStringByRegx($_str, $_reg)
    {
        $arr = [];
        preg_match($_reg, $_str, $arr);
        return isset($arr[1])?$arr[1]:"";
    }
}

?>