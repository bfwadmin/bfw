<?php
namespace Lib;
use Lib\Bfw;
use Lib\Util\ArrayUtil;
class BoValidate
{

    public $_input_array;

    public $_validate_array;

    public function validateArray()
    {
        if (! isset($this->_input_array)) {
            return array(
                "err" => true,
                "data" => Bfw::Config("Sys", "validate","System")['input_array_empty']
            );
        }
       return  ArrayUtil::Validate($this->_input_array, $this->_validate_array, $this);
    }
}

?>