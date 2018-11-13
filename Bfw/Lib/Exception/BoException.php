<?php
namespace Lib\Exception;

class BoException extends \Exception
{

    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function getException()
    {
        return [
            'errmsg' => $this->getMessage(),
            "errline" => $this->getLine(),
            "errfile" => $this->getFile(),
            "trace" => $this->getTraceAsString(),
            "type" => str_replace("Lib\\Exception\\", "", get_class($this))
        ];
    }
}
?>