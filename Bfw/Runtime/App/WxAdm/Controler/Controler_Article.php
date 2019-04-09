<?php
namespace App\[[DOM]]\Controler;

use Lib\Bfw;
use Lib\BoControler;
use App\[[DOM]]\Client\Client_Article;

class Controler_Article extends BoControler
{

    /**
     * 文章添加
     */
    function AddData()
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "title",
                "classname",
                "content"
            ), false, "Article");
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_formdata['data']['atime'] = UNIX_TIME;
            $_insertdata = Client_Article::getInstance()->Insert($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            return $this->Alert("添加成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("Article", "ListData"),
                    ""
                )
            ));
        }
        
        $this->Display();
    }
    
    function Index(){
        $_orderdata = Client_Article::getInstance()->Where($_wherestr, $_wherearr)
        ->DescBy("id")->Field("id,title,classname")
        ->Select();
        if ($_orderdata['err']) {
            return $this->Error($_orderdata['data']);
        }
        $this->Assign("itemdata", $_orderdata['data']['data']);
        return  $this->Display();
    }

    /**
     * 文章删除
     */
    function DelData($id)
    {
        $_deldata = Client_Article::getInstance()->Delete($id);
        if ($_deldata['err']) {
            return $this->Error($_deldata['data']);
        }
        
        $this->Alert("删除成功", array(
            array(
                "返回",
                Bfw::ACLINK("Article", "ListData"),
                ""
            )
        ));
    }

    /**
     * 文章编辑
     */
    function EditData($id)
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "title",
                "classname",
                "content",
                "id"
            ), false, "Article");
            
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            
            $_insertdata = Client_Article::getInstance()->Update($_formdata['data']);
            if ($_insertdata['err']) {
                return $this->Error($_insertdata['data']);
            }
            
            return $this->Alert("更新成功", array(
                array(
                    "返回",
                    Bfw::ACLINK("Article", "ListData"),
                    ""
                )
            ));
        }
        $_data = Client_Article::getInstance()->Single($id);
        if ($_data['err']) {
            return $this->Error($_data['data']);
        }
        $this->Assign("itemdata", $_data['data']);
        $this->Display();
    }

    /**
     * 文章列表
     */
    function ListData($page = 0, $title = "", $classname = "")
    {
        $pagesize = 10;
        $this->OutCharset("utf-8");
        $_wherestr = "";
        $_wherearr = array();
        if ($title != "") {
            $_wherestr .= " title=? ";
            $_wherearr[] = $title;
        }
        if ($classname != "") {
            $_wherestr .= " classname=? ";
            $_wherearr[] = $classname;
        }
        
        $_orderdata = Client_Article::getInstance()->Where($_wherestr, $_wherearr)
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

    /**
     * 目录
     */
    function Catalog()
    {
        $this->OutCharset("utf-8");
        $_cata_data = Client_Article::getInstance()->Cache(100)->getCatalog();
        if ($_cata_data['err']) {
            return $this->Error($_cata_data['data']);
        }
        $this->Assign("itemdata", $_cata_data['data']);
        $this->Display();
    }

    function CatalogData($classname = "")
    {
        $this->OutCharset("utf-8");
        if ($classname != "") {
            $_wherestr .= " classname=? ";
            $_wherearr[] = $classname;
        }
        $_orderdata = Client_Article::getInstance()->Where($_wherestr, $_wherearr)
            ->PageNum(0)
            ->PageSize(100)
            ->AscBy("id")
            ->Select(false);
        if ($_orderdata['err']) {
            return $this->Error($_orderdata['data']);
        }
        $this->Assign("itemdata", $_orderdata['data']['data']);
        $this->Display();
    }

    function DetailData($id)
    {
        $this->OutCharset("utf-8");
        $_data = Client_Article::getInstance()->Single($id);
        if ($_data['err']) {
            return $this->Error($_data['data']);
        }
        if ($id > 1) {
            $this->Assign("beforeid", $id - 1);
        }
        $this->Assign("nextid", $id + 1);
        $_artdata = Client_Article::getInstance()->Where($_wherestr, $_wherearr)
        ->AscBy("id")->Field("id,title,classname")
        ->Select();
        if ($_artdata['err']) {
            return $this->Error($_artdata['data']);
        }
        $this->Assign("menudata", $_artdata['data']['data']);
        $this->Assign("itemdata", $_data['data']);
        $this->Display();
    }
}

?>