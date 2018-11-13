<?php
namespace Lib\RPC;

interface BoRpcInterface
{
    function pack($_arrdata);
    function unpack($_sourcestr);
}

?>