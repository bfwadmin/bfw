<?php
namespace Lib\Exception;
class LockException extends BoException
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }  
}
?>