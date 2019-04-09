<?php
namespace App\Admin\Widget;

use Lib\BoWidget;
use App\Admin\Client\Client_Artwork;
use App\Admin\Client\Client_Attach;

/**
 * @author Herry
 * 猜你喜欢
 */
class Widget_GuessYouLove extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
        // $this->AddData("test", 123);
        // $this->AddData($_data);
        $_data = Client_Artwork::getInstance()->PageNum(0)
            ->PageSize(10)
            ->Cache(1000)
            ->DescBy("disorderid")
            ->Select(false);
        if ($_data['err']) {}
        $this->AddData("itemdata", array_map(function (&$item) {
            $item['productimg'] = Client_Attach::getInstance()->GetUrlById($item['productimg'], "400_200_1");
            return $item;
        }, $_data['data']['data']));
        
        $this->RenderIt("GuessYouLove");
    }
}

?>