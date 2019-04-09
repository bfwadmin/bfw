<?php
namespace App\[[DOM]]\Service;

use Lib\Bfw;
use Lib\BoService;
use App\[[DOM]]\Model\Model_Member;
use Lib\Exception\DbException;
use Lib\Util\StringUtil;

/**
 *
 * @author Herry
 *         会员
 */
class Service_Member extends BoService
{

    const THIRD_QQ = 1;

    const THIRD_WEIXIN = 2;

    const THIRD_SINA = 3;

    const KIND_NORMAL = 1;
    // 普通用户
    const KIND_COMP = 2;
    // 拍卖用户
    const DATA_STRUCT_WRONG = 0;

    const USERNAME_EXSIT = 1;

    const USERNAME_NEED = 2;

    const PASSWORD_NEED = 3;

    const USER_PWD_NOTMATCH = 4;

    const USERNAME_NOT_EXSIT = 5;

    const DATA_NULL = 6;
    
    // 密码修改
    const OLD_PWD_NEED = 7;

    const NEW_PWD_NEED = 8;

    const OLD_PWD_WRONG = 10;

    const PHONE_REGED = 9;

    const KIND_NOTALLOW = 11;

    protected $_model = "Member";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_Group
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function getkey()
    {
        return "123";
    }

    /**
     * @function password()
     * ription: 用户登陆密码算法
     * @anthour: early(early@wangcunwu.com)
     *
     * @param string $str
     *            用户密码明文
     * @param string $key
     *            密钥：6位随机字符串
     */
    private function password($str, $key)
    {
        $str = substr(substr(md5($str), 3), 0, - 3);
        $str = substr($key, 0, 2) . $str . substr($key, 3, 5);
        return md5($str);
    }

    /**
     * 修改密码
     *
     * @param number $uid            
     * @param string $oldp            
     * @param string $newp            
     * @return multitype:unknown |unknown
     */
    function ChangePwd($uid, $oldp, $newp)
    {
        if ($this->isNullOrEmpty($oldp)) {
            return Bfw::RetMsg(true, self::OLD_PWD_NEED);
        }
        if ($this->isNullOrEmpty($newp)) {
            return Bfw::RetMsg(true, self::NEW_PWD_NEED);
        }
        $udata = $this->Single("id,salt,userpwd", $uid);
        if ($udata['err']) {
            return $udata;
        }
        if ($udata['data'] == null) {
            return Bfw::RetMsg(true, self::DATA_NULL);
        }
        // return Bfw::RetMsg ( true, $this->password ( $oldp, trim ( $udata ['data'] ['salt'] ) ) );
        if ($this->password($oldp, trim($udata['data']['salt'])) == trim($udata['data']['userpwd'])) {
            $udata['data']['userpwd'] = $this->password($newp, trim($udata['data']['salt']));
            return $this->Update($udata['data']);
        }
        return Bfw::RetMsg(true, self::OLD_PWD_WRONG);
    }

    function GetData($_uid, $_field)
    {
        return $this->Single($_field, $_uid);
    }

    function ChangeData($_data, $_uid)
    {}

    function GetMoney($_uid)
    {
        $_moneydata = Model_Member::getInstance()->GetMoney($_uid);
        if ($_moneydata['err']) {
            return $_moneydata;
        }
        if ($_moneydata['data'] == null) {
            $_insertdata = Model_Member::getInstance()->InsertAddNew([
                'id' => $_uid,
                'money' => 0
            ]);
            if ($_insertdata['err']) {
                return $_insertdata;
            }
            return Bfw::RetMsg(false, 0);
        }
        return Bfw::RetMsg(false, $_moneydata['data']['money']);
    }

    function Auth($_data)
    {
        $_structdata = $this->checkDataStruct($_data, [
            'username',
            'userpwd'
        ], true);
        if ($_structdata['err']) {
            return $_structdata;
        }
        
        $_selectdata = $this->ListData("id,userpwd,groupid,salt", "(username=? or phone=? or email=?) ", array(
            $_data['username'],
            $_data['username'],
            $_data['username']
        ), 1, 0, "");
        if ($_selectdata['err']) {
            return $_selectdata;
        }
        if ($_selectdata['data']['count'] == 0) {
            return Bfw::RetMsg(true, self::USERNAME_NOT_EXSIT);
        }
        if ($this->password($_data['userpwd'], trim($_selectdata['data']['data'][0]['salt'])) != trim($_selectdata['data']['data'][0]['userpwd'])) {
            return Bfw::RetMsg(true, self::USER_PWD_NOTMATCH);
        }
        return Bfw::RetMsg(false, $_selectdata['data']['data'][0]);
    }

    private function AddNormal($_data, $_data_add)
    {
        try {
            $_resarr = array();
            $_res1 = Model_Member::getInstance()->InsertData($_data, false, true);
            if ($_res1['err']) {
                Model_Member::getInstance()->RollBack();
                Model_Member::getInstance()->ChangeBack();
                return $_res1;
            }
            $_data_add['id'] = $_res1['data'];
            $_resarr[] = Model_Member::getInstance()->InsertDataAdd($_data_add, false, true);
            
            foreach ($_resarr as $_res) {
                if ($_res['err']) {
                    Model_Member::getInstance()->RollBack();
                    Model_Member::getInstance()->ChangeBack();
                    
                    return $_res;
                }
            }
            Model_Member::getInstance()->Commit();
            Model_Member::getInstance()->ChangeBack();
            Model_Member::getInstance()->Commit();
            Model_Member::getInstance()->ChangeBack();
            
            return $_res1;
        } catch (DbException $e) {
            Model_Member::getInstance()->RollBack();
            Model_Member::getInstance()->ChangeBack();
            Model_Member::getInstance()->RollBack();
            Model_Member::getInstance()->ChangeBack();
            
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    private function AddComp($_data, $_data_add, $_comp_data)
    {
        try {
            $_resarr = array();
            $_res1 = Model_Member::getInstance()->InsertData($_data, false, true);
            if ($_res1['err']) {
                Model_Member::getInstance()->RollBack();
                Model_Member::getInstance()->ChangeBack();
                return $_res1;
            }
            $_data_add['id'] = $_res1['data'];
            $_artist['id'] = $_res1['data'];
            $_comp_data['id'] = $_res1['data'];
            $_resarr[] = Model_Member::getInstance()->InsertDataAdd($_data_add, false, true);
            $_resarr[] = Model_Member::getInstance()->InsertCompData($_comp_data, false, true);
            
            foreach ($_resarr as $_res) {
                if ($_res['err']) {
                    
                    Model_Member::getInstance()->RollBack();
                    Model_Member::getInstance()->ChangeBack();
                    Model_Member::getInstance()->RollBack();
                    Model_Member::getInstance()->ChangeBack();
                    Model_Member::getInstance()->RollBack();
                    Model_Member::getInstance()->ChangeBack();
                    return $_res;
                }
            }
            Model_Member::getInstance()->Commit();
            Model_Member::getInstance()->ChangeBack();
            Model_Member::getInstance()->Commit();
            Model_Member::getInstance()->ChangeBack();
            Model_Member::getInstance()->Commit();
            Model_Member::getInstance()->ChangeBack();
            return $_res1;
        } catch (DbException $e) {
            Model_Member::getInstance()->RollBack();
            Model_Member::getInstance()->ChangeBack();
            Model_Member::getInstance()->RollBack();
            Model_Member::getInstance()->ChangeBack();
            Model_Member::getInstance()->RollBack();
            Model_Member::getInstance()->ChangeBack();
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    function Reg($_kindid, $_data)
    {
        if (! isset($_data['mobile']) || trim($_data['mobile']) == "") {
            return Bfw::RetMsg(true, self::DATA_STRUCT_WRONG);
        }
        
        $_selectdata = $this->Count("mobile=?", array(
            $_data['mobile']
        ));
        // return Bfw::RetMsg(true, $_data['mobile']);
        if ($_selectdata['err']) {
            return $_selectdata;
        }
        if ($_selectdata['data'] > 0) {
            return Bfw::RetMsg(true, self::PHONE_REGED);
        }
        // return Bfw::RetMsg(true, "ssssssssssss".$_kindid);
        $_structdata = [
            'err' => true,
            "data" => "err"
        ];
        $_randstr = StringUtil::getRandChar(6);
        switch ($_kindid) {
            case self::KIND_NORMAL:
                $_structdata = $this->checkDataStruct($_data, [
                    'mobile',
                    'userpwd',
                    "ip"
                ], true);
                if ($_structdata['err']) {
                    return $_structdata;
                }
                
                $_adddata = $this->AddNormal([
                    "username" => $_data['mobile'],
                    "mobile" => $_data['mobile'],
                    "userpwd" => $this->password($_data['userpwd'], $_randstr),
                    "salt" => $_randstr,
                    "ip" => $_data['ip'],
                    'atime' => time(),
                    'kindid' => $_kindid,
                    'openid' => isset($_data['openid']) ? $_data['openid'] : ''
                ], [
                    "status" => $_data['status']
                ]);
                if ($_adddata['err']) {
                    return $_adddata;
                }
                return Bfw::RetMsg(false, array(
                    "id" => $_adddata['data'],
                    "kindid" => $_kindid
                ));
                break;
            case self::KIND_COMP:
                $_structdata = $this->checkDataStruct($_data, [
                    'mobile',
                    'userpwd',
                    "ip",
                    "compname"
                ], true);
                if ($_structdata['err']) {
                    return $_structdata;
                }
                $_adddata = $this->AddComp([
                    'username' => $_data['mobile'],
                    "mobile" => $_data['mobile'],
                    "userpwd" => $this->password($_data['userpwd'], $_randstr),
                    "salt" => $_randstr,
                    "ip" => $_data['ip'],
                    'atime' => time(),
                    'kindid' => $_kindid,
                    'openid' => isset($_data['openid']) ? $_data['openid'] : ''
                ], [
                    "status" => $_data['status']
                ], [
                    'compname' => $_data['compname']
                ]);
                
                if ($_adddata['err']) {
                    return $_adddata;
                }
                return Bfw::RetMsg(false, array(
                    "id" => $_adddata['data'],
                    "kindid" => $_kindid
                ));
                break;
            case self::KIND_GALLERY:
                $_structdata = $this->checkDataStruct($_data, [
                    'mobile',
                    'userpwd',
                    "nickname"
                ], true);
                if ($_structdata['err']) {
                    return $_structdata;
                }
                break;
        }
    }

    function GetUidByOpenid($_openid, $_thirdname)
    {
        $_thirddata = Model_Member::getInstance()->ThirdListData("id", "openid=? and thirdname=? ", [
            $_openid,
            $_thirdname
        ], null, null, null, false);
        if ($_thirddata['err']) {
            return $_thirddata;
        }
        if (count($_thirddata['data']['data']) > 0) {
            $_usrdata = $this->GetData($_thirddata['data']['data'][0]['id'], "kindid");
            if ($_usrdata['err']) {
                return $_usrdata;
            }
            if ($_usrdata['data'] == null) {
                return Bfw::RetMsg(true, self::DATA_NULL);
            }
            return Bfw::RetMsg(false, [
                'id' => $_thirddata['data']['data'][0]['id'],
                'kindid' => $_usrdata['data']['kindid']
            ]);
        }
        return Bfw::RetMsg(false, null);
    }

    function UpdateData($_kindid, $_data)
    {
        $_ret = Bfw::RetMsg(true, self::DATA_NULL);
        switch ($_kindid) {
            case self::KIND_NORMAL:
                $_arr = $this->filterData([
                    [
                        'id',
                        'name'
                    ],
                    [
                        'dd',
                        'ssd'
                    ]
                ], [
                    'id' => 1,
                    "dd" => 2
                ]);
                var_dump($_arr);
                break;
            case self::KIND_COMP:
                if (! isset($_data['id'])) {
                    return Bfw::RetMsg(true, self::DATA_NULL);
                }
                $_ret = Model_Member::getInstance()->UpdateCompData($_data, true, false);
                break;
            default:
                
                break;
        }
        return $_ret;
    }

    function GetMyData($_kindid, $_uid)
    {
        $_ret = Bfw::RetMsg(true, self::DATA_NULL);
        switch ($_kindid) {
            case self::KIND_COMP:
                $_ret = Model_Member::getInstance()->SingleComp($_uid);
                break;
            default:
                
                break;
        }
        return $_ret;
    }
}
?>