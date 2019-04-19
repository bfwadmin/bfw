<?php
namespace Lib;


/**
 * @author wangbo
 * 小部件类
 */
class BoWidget
{

    protected $_data = null;

    public function __construct(&$_data)
    {
        $this->_data['widgetdata'] = $_data;
    }

    /**
     * 赋值
     * @param string $_key
     * @param object $_data
     */
    protected function AddData($_key, $_data)
    {
        //if ($_data != null) {
            $this->_data[$_key] = $_data;
        //}
    }

    protected function RenderIt($_viewname, $_domian = DOMIAN_VALUE)
    {
        BoRes::View($_viewname, $_domian, "Widget", $this->_data);
    }
}

?>