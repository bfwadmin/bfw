<?php
namespace Lib\Log;

use Lib\BoErrEnum;

interface BoLogInterface
{

    public function Log($_word, $_tag, $_level = BoErrEnum::BFW_INFO);
}

?>