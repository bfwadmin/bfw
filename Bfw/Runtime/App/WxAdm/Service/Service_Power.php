<?php
namespace App\[[DOM]]\Service;

use Lib\Bfw;
use Lib\BoService;
use App\[[DOM]]\Model\Model_Group;

/**
 *
 * @author Herry
 *         人物
 */
class Service_Power extends BoService
{

    protected $_model = "Power";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_Group
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function getkey()
    {
        return "123";
    }

    public function InsertOrUpdate($_arr)
    {
        if (empty($_arr)) {
            return Bfw::RetMsg(true, "empty");
        }
        $_ret = array();
        foreach ($_arr as $_a) {
            $_data = $this->ListData("*", "controlname=? and actionname=? and domianname=?", array(
                $_a[0],
                $_a[1],
                $_a[2]
            ), 1, 0, "order by id desc");
            if ($_data['err']) {
                return $_data;
            }
            if ($_data['data']['count'] == 0) {
                $_ret[$_a[0] . "_" . $_a[1] . "_" . $_a[2]] = $this->Insert(array(
                    "powername" => $_a[3],
                    "controlname" => $_a[0],
                    "actionname" => $_a[1],
                    "domianname" => $_a[2]
                ));
            } else {
                // $this->RetMsg(true, "exsit");
            }
        }
        return Bfw::RetMsg(false, $_ret);
    }

    function GetPowerByGroupId($_gid)
    {
        $_groupdata = Model_Group::getInstance()->Single("*", $_gid);
        if ($_groupdata['err']) {
            return $_groupdata;
        }
        if ($_groupdata['data'] == null) {
            return Bfw::RetMsg(true, "no_data");
        }
        $_grouppower = unserialize($_groupdata['data']['grouppower']);
        $_sParams = implode(',', array_fill(0, count($_grouppower), '?'));
        // return $this->RetMsg(true, $_sParams);
        $_powerdata = $this->ListData("controlname,actionname,domianname,pid", "id in ({$_sParams})", $_grouppower, 1000, 0, "order by id desc",false);
        if ($_powerdata['err']) {
            return $_powerdata;
        }
        $ret = array();
        foreach ($_powerdata['data']['data'] as $item) {
            $ret[] = $item['controlname'] . "_" . $item['actionname'] . "_" . $item['domianname'];
        }
        return Bfw::RetMsg(false, $ret);
    }

    function GetPowerDetailByGroupId($_gid)
    {
        $_groupdata = Model_Group::getInstance()->Single("*", $_gid);
        if ($_groupdata['err']) {
            return $_groupdata;
        }
        if ($_groupdata['data'] == null) {
            return Bfw::RetMsg(true, "no_data");
        }
        $_grouppower = unserialize($_groupdata['data']['grouppower']);
        $_sParams = implode(',', array_fill(0, count($_grouppower), '?'));
        // return $this->RetMsg(true, $_sParams);
        $_powerdata = $this->ListData("id,ismenu,powername,controlname,actionname,domianname,pid", "pid=0 or id in ({$_sParams})", $_grouppower, 1000, 0, "order by id desc",false);
        if ($_powerdata['err']) {
            return $_powerdata;
        }
        return Bfw::RetMsg(false, $_powerdata['data']['data']);
    }
}
?>

