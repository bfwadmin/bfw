<?php
namespace App\[[DOM]]\Controler;

use Lib\Bfw;
use Lib\BoControler;
use App\[[DOM]]\Client\Client_Power;
use Lib\Util\ArrayUtil;

/**
 *
 * @author wangbo
 *         权限相关
 */
class Controler_Power extends BoControler
{

    /**
     * 权限删除
     */
    function DelData($id)
    {
        $_deldata = Client_Power::getInstance()->Delete($id);
        if ($_deldata['err']) {
            return $this->Error($_deldata['data']);
        }
        $this->Alert("删除成功", array(
            array(
                "返回",
                Bfw::ACLINK("Power", "ListData"),
                ""
            )
        ));
    }

    /**
     * 权限编辑
     */
    function EditData($id)
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "powername",
                "controlname",
                "actionname",
                "domianname",
                "id",
                "pid",
                "ismenu"
            ), true, "Power");
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_insertdata = Client_Power::getInstance()->Update($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
                ;
            }
            return $this->Alert("修改成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("Power", "ListData"),
                    ""
                )
            ));
        }
        $_itemdata = Client_Power::getInstance()->Single($id);
        if ($_itemdata['err'] || $_itemdata['data'] == null) {
            return $this->Error("data wrong");
        }
        $this->Assign("itemdata", $_itemdata['data']);
        
        $_powerdata = Client_Power::getInstance()->Field("id,powername")
        ->Where("pid=0", [])
        ->Select(false);
        if ($_powerdata['err']) {
            return $this->Error("数据错误");
        }
        $_powerdata['data']['data'][] = array(
            "id" => 0,
            "powername" => "顶级"
        );
        $this->Assign("powerdata", ArrayUtil::ConvertToOne($_powerdata['data']['data']));
        $this->Display();
    }

    /**
     * 权限添加
     */
    function AddData()
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "powername",
                "controlname",
                "actionname",
                "domianname",
                "pid",
                "ismenu"
            ), true, "Power");
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_insertdata = Client_Power::getInstance()->Insert($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            return $this->Alert("添加成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("Power", "ListData"),
                    ""
                )
            ));
        }
        $_powerdata = Client_Power::getInstance()->Field("id,powername")
            ->Where("pid=0", [])
            ->Select(false);
        if ($_powerdata['err']) {
            return $this->Error("数据错误");
        }
        $_powerdata['data']['data'][] = array(
            "id" => 0,
            "powername" => "顶级"
        );
        $this->Assign("powerdata", ArrayUtil::ConvertToOne($_powerdata['data']['data']));
        $this->Display();
    }

    /**
     * 权限列表
     *
     * @param number $page            
     */
    function ListData($page = 0)
    {
        $pagesize = 10;
        $this->OutCharset("utf-8");
        $_wherestr = "";
        $_wherearr = array();
        $_orderdata = Client_Power::getInstance()->Where($_wherestr, $_wherearr)
            ->PageNum($page)
            ->PageSize($pagesize)
            ->DescBy("id")
            ->Select();
        if ($_orderdata['err']) {
            $this->Error($_orderdata['data']);
            return;
        }
        $this->Assign("pagerdata", \Lib\Util\PagerUtil::_GenPageData($_orderdata['data']['count'], $page, $pagesize, 2));
        $this->Assign("itemdata", $_orderdata['data']['data']);
        $this->Display();
    }
}

?>