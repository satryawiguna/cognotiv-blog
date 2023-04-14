<?php

namespace App\Core\Response;

class MessageResponse
{
    public $messageType;

    public string $text;

    public function __construct($messageType, string $text)
    {
        $this->messageType = $messageType;
        $this->text = $text;
    }
}
