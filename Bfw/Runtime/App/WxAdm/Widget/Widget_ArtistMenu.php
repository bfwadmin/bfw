<?php
namespace App\Admin\Widget;


use Lib\BoWidget;

/**
 * @author Herry
 * 艺术家后台菜单
 */
class Widget_ArtistMenu extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
        //  $this->AddData("test", 123);
        // $this->AddData($_data);
        $this->RenderIt("ArtistMenu");
    }
}

?>