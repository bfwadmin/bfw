<?php
namespace Lib;
use Lib\Util\ArrayUtil;
/**
 * @author wangbo
 * 验证器父类
 */
class BoValidate
{

    public $_input_array;

    public $_validate_array;

    public function validateArray()
    {
        if (! isset($this->_input_array)) {
            return array(
                "err" => true,
                "data" => BoConfig::Config("Sys", "validate","System")['input_array_empty']
            );
        }
       return  ArrayUtil::Validate($this->_input_array, $this->_validate_array, $this);
    }
}

?>