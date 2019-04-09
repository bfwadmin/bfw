<?php
namespace App\Admin\Widget;


use Lib\BoWidget;

/**
 * @author Herry
 * 前台分页组件
 */
class Widget_FrontPager extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
        //  $this->AddData("test", 123);
        // $this->AddData($_data);
        $this->RenderIt("FrontPager");

       
    }
}

?>