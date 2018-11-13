<?php
namespace Lib\Cache;
interface BoCacheInterface
{

    public function setkey($_key, $_val, $_expire);
    public function getkey($_key);
    public function del($_key);
}

?>