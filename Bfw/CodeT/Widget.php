<?php
namespace App\DOM\Widget;

use Lib\BoWidget;

/**
 * @author bfw
 * 分页组件
 */
class Widget_Pager extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
        //可在此传值
        //$this->AddData("customdata", 123);
        $this->RenderIt("Pager");
    }
}

?>