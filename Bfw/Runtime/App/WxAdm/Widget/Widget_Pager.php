<?php
namespace App\[[DOM]]\Widget;


use Lib\BoWidget;

/**
 * @author Herry
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
        //  $this->AddData("test", 123);
        // $this->AddData($_data);
        $this->RenderIt("Pager");

       
    }
}

?>