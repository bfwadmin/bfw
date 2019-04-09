<?php
namespace App\[[DOM]]\Widget;

use Lib\BoWidget;
use Lib\Bfw;
use App\[[DOM]]\Client\Client_Power;

/**
 *
 * @author Herry
 *         用户菜单
 */
class Widget_Menu extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
        // $_uid = Bfw::Session(USER_ID);
        $groupid = Bfw::Session(ROLE_ID);
        $_powerdata = Client_Power::getInstance()->GetPowerDetailByGroupId($groupid);
      //  var_dump($_powerdata);
        if (! $_powerdata['err']) {
            $this->AddData('data', $_powerdata['data']);
            $this->RenderIt("Menu");
        }
        // $this->AddData("test", 123);
        // $this->AddData($_data);
    }
}

?>