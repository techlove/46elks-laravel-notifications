<?php

namespace Techlove\FortySixElks\Notifications\Exceptions;

class ChannelException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}