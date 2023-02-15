<?php

namespace App\Database;

use App\Entity\Paste;
use App\Entity\TextPaste;

enum PasteType: int
{
    case TEXT = 0;

    public static function fromPaste(Paste $paste) :PasteType
    {
        return match (get_class($paste)) {
            TextPaste::class => self::TEXT
        };
    }
}
