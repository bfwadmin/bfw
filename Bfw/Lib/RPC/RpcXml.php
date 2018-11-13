<?php
namespace Lib\RPC;

class RpcJson implements BoRpcInterface
{

    
    public function pack($_arrdata)
    {
        return json_encode($_arrdata);
    }

    public function unpack($_sourestr)
    {
        return json_decode($_sourestr, true);
    }
}

?>