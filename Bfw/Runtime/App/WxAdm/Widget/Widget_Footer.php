<?php
namespace App\[[DOM]]\Widget;


use Lib\BoWidget;
use Lib\Util\UrlUtil;

/**
 * @author Herry
 * 尾部
 */
class Widget_Footer extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
       // $host = str_replace("http://", "", UrlUtil::getbase());
        //if($host!="www.88art.com"){
           // return $this->RenderIt("ArtistSiteFooter");
      //  }
        //  $this->AddData("test", 123);
        // $this->AddData($_data);
        $this->RenderIt("Footer");

       
    }
}

?>