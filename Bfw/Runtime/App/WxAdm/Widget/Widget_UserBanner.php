<?php
namespace App\Admin\Widget;

use Lib\BoWidget;
use Lib\Bfw;
use App\Admin\Client\Client_Member;
use App\Admin\Client\Client_Artist;
use App\Admin\Client\Client_Attach;
use App\Admin\Client\Client_Fans;

/**
 *
 * @author Herry
 *         用户中心banner
 */
class Widget_UserBanner extends BoWidget
{

    public function __construct(&$_data = null)
    {
        parent::__construct($_data);
    }

    public function Render()
    {
        // $this->AddData("test", 123);
        // $this->AddData($_data);
        $_uid = Bfw::Session(USER_ID);
        $_kindid = Bfw::Session(ROLE_ID);
        if ($_kindid == Client_Member::KIND_ARTIST) {
            $_userdata = Client_Artist::getInstance()->Cache(5)->Single($_uid);
            if ($_userdata['err'] || $_userdata['data'] == null) {
                $this->AddData("userinfo", null);
            }
            $_userdata['data']['userimg'] = Client_Attach::getInstance()->GetUrlById($_userdata['data']['userimg'], "200_200_3");
            $_fanscountdata = Client_Fans::getInstance()->Cache(1000)->Count("followuid=?", [
                $_uid
            ]);
            if ($_fanscountdata['err']) {
                $_userdata['data']['fanscount'] = 0;
            } else {
                $_userdata['data']['fanscount'] = $_fanscountdata['data'];
            }
            $_focuscountdata = Client_Fans::getInstance()->Cache(1000)->Count("uid=?", [
                $_uid
            ]);
            if ($_focuscountdata['err']) {
                $_userdata['data']['focuscount'] = 0;
            } else {
                $_userdata['data']['focuscount'] = $_focuscountdata['data'];
            }
            
            $this->AddData("userinfo", $_userdata['data']);
            $this->RenderIt("ArtistUserBanner");
        } else {
            $_userdata = Client_Member::getInstance()->Cache(5)->Single($_uid);
            if ($_userdata['err'] || $_userdata['data'] == null) {
                $this->AddData("userinfo", null);
            }
            
            $_userdata['data']['userimg'] = Client_Attach::getInstance()->GetUrlById($_userdata['data']['userimg'], "200_200_3");
            // $_userdata['data']['userimg'] = Client_Attach::getInstance()->GetUrlById($_userdata['data']['userimg'], "400_200_1");
            
            $_fanscountdata = Client_Fans::getInstance()->Cache(1000)->Count("followuid=?", [
                $_uid
            ]);
            if ($_fanscountdata['err']) {
                $_userdata['data']['fanscount'] = 0;
            } else {
                $_userdata['data']['fanscount'] = $_fanscountdata['data'];
            }
            $_focuscountdata = Client_Fans::getInstance()->Cache(1000)->Count("uid=?", [
                $_uid
            ]);
            if ($_focuscountdata['err']) {
                $_userdata['data']['focuscount'] = 0;
            } else {
                $_userdata['data']['focuscount'] = $_focuscountdata['data'];
            }
            $this->AddData("userinfo", $_userdata['data']);
            $this->RenderIt("UserBanner");
        }
    }
}

?>