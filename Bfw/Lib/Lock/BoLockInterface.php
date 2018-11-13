<?php
namespace Lib\Lock;

interface BoLockInterface
{
    public function lock();
    
    public function unlock();
}

?>