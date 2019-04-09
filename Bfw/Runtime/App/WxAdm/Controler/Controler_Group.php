<?php
namespace App\[[DOM]]\Controler;

use Lib\Bfw;
use Lib\BoControler;
use App\[[DOM]]\Client\Client_Group;
use App\[[DOM]]\Client\Client_Power;

class Controler_Group extends BoControler
{

    /**
     * 组删除
     */
    function DelData($id)
    {
        $_deldata = Client_Group::getInstance()->Delete($id);
        if ($_deldata['err']) {
            return $this->Error($_deldata['data']);
        }
        $this->ClearCache("power_group_{$id}");
        $this->Alert("删除成功", array(
            array(
                "返回",
                Bfw::ACLINK("Group", "ListData"),
                ""
            )
        ));
    }

    /**
     * 组编辑
     */
    function EditData($id)
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "groupname",
                "grouppower",
                "id"
            ), true, "Group");
            
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_formdata['data']['grouppower'] = serialize($_formdata['data']['grouppower']);
            $_insertdata = Client_Group::getInstance()->Update($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            $this->ClearCache("power_group_{$id}");
            return $this->Alert("更新成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("Group", "ListData"),
                    ""
                )
            ));
        }
        $_groupdata = Client_Group::getInstance()->Single($id);
        if ($_groupdata['err']) {
            return $this->Error($_groupdata['data']);
        }
        $this->Assign("groupname", $_groupdata['data']['groupname']);
        $this->Assign("groupdata", unserialize($_groupdata['data']['grouppower']));
        $_powerdata = Client_Power::getInstance()->Select();
        if ($_powerdata['err']) {
            return $this->Error($_powerdata['data']);
        }
        $this->Assign("powerdata", $_powerdata['data']['data']);
        $this->Display();
    }

    /**
     * 组添加
     */
    function AddData()
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "groupname",
                "grouppower"
            ), true, "Group");
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_formdata['data']['grouppower'] = serialize($_formdata['data']['grouppower']);
            $_insertdata = Client_Group::getInstance()->Insert($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            return $this->Alert("添加成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("Group", "ListData"),
                    ""
                )
            ));
        }
        $_powerdata = Client_Power::getInstance()->AscBy("pid")->Select();
        if ($_powerdata['err']) {
            return $this->Error($_powerdata['data']);
        }
        $this->Assign("powerdata", $_powerdata['data']['data']);
        $this->Display();
    }

    /**
     * 组列表
     */
    function ListData($page = 0)
    {
        $pagesize = 10;
        $this->OutCharset("utf-8");
        $_wherestr = "";
        $_wherearr = array();
        $_orderdata = Client_Group::getInstance()->Where($_wherestr, $_wherearr)
            ->PageNum($page)
            ->PageSize($pagesize)
            ->DescBy("id")
            ->Select();
        if ($_orderdata['err']) {
          return  $this->Error($_orderdata['data']);
        }
        $this->Assign("pagerdata", \Lib\Util\PagerUtil::_GenPageData($_orderdata['data']['count'], $page, $pagesize, 2));
        $this->Assign("itemdata", $_orderdata['data']['data']);
        $this->Display();
    }
}

?>