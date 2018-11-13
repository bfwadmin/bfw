<?php
namespace App\DOM\Controler;

use Lib\Bfw;
use Lib\BoControler;
use App\DOM\Client\Client_CONTNAME;

/**
 * @author Administrator
 *CONTMEMO
 */
class Controler_CONTNAME extends BoControler
{

    /**
     * CONTMEMO添加
     */
    function AddData()
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
           FIELDNAMEARRAY
            ), false, "CONTNAME");
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_insertdata = Client_CONTNAME::getInstance()->Insert($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            return $this->Alert("添加成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("CONTNAME", "ListData"),
                    ""
                )
            ));
        }
        
        $this->Display();
    }

    /**
     * CONTMEMO删除
     */
    function DelData($id)
    {
        $_deldata = Client_CONTNAME::getInstance()->Delete($id);
        if ($_deldata['err']) {
            return $this->Error($_deldata['data']);
        }
        
        $this->Alert("删除成功", array(
            array(
                "返回",
                Bfw::ACLINK("CONTNAME", "ListData"),
                ""
            )
        ));
    }

    /**
     * CONTMEMO编辑
     */
    function EditData($id)
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
              FIELDNAMEARRAY
            ), false, "CONTNAME");
            
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            
            $_insertdata = Client_CONTNAME::getInstance()->Update($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            
            return $this->Alert("更新成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("CONTNAME", "ListData"),
                    ""
                )
            ));
        }
        $_data = Client_CONTNAME::getInstance()->Single($id);
        if ($_data['err']) {
            return $this->Error($_data['data']);
        }
        $this->Assign("itemdata", $_data['data']);
        $this->Display();
    }

    /**
     * CONTMEMO列表
     */
    function ListData($page = 0)
    {
        $pagesize = 10;
        $this->OutCharset("utf-8");
       
        
        $_orderdata = Client_CONTNAME::getInstance()
            ->PageNum($page)
            ->PageSize($pagesize)
            ->DescBy("id")
            ->Select();
        if ($_orderdata['err']) {
            return $this->Error($_orderdata['data']);
        }
        
        $this->Assign("pagerdata", \Lib\Util\PagerUtil::_GenPageData($_orderdata['data']['count'], $page, $pagesize, 2));
        $this->Assign("itemdata", $_orderdata['data']['data']);
        $this->Display();
    }

  
}

?>