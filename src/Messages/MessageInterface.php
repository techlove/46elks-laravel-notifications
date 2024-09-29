<?php

namespace Techlove\FortySixElks\Notifications\Messages;

use Techlove\FortySixElks\Notifications\Services\MessageClient;

interface MessageInterface
{
    public function __construct(MessageClient $client);

    public function send(): static;

    public function routedTo(?string $routedTo = null);
}