<?php

namespace App\Database;

use App\Entity\FilePaste;
use App\Entity\ImagePaste;
use App\Entity\Paste;
use App\Entity\TextPaste;

enum PasteType: int
{
    case TEXT = 0;
    case FILE = 1;
    case IMAGE = 2;

    public static function fromPaste(Paste $paste) :PasteType
    {
        return match (get_class($paste)) {
            TextPaste::class => self::TEXT,
            FilePaste::class => self::FILE,
            ImagePaste::class => self::IMAGE,
        };
    }
}
