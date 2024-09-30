<?php

namespace Techlove\FortySixElks\Notifications\Facades;

use Illuminate\Support\Facades\Facade;
use Techlove\FortySixElks\Notifications\Messages\Mms;
use Techlove\FortySixElks\Notifications\Messages\Sms;

/**
 * @method static Sms sms()
 * @method static Mms mms()
 */
class MessageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'techlove-46elks-notifications-message';
    }
}