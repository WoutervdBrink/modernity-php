<?php

namespace App\Models\Enums;

enum ContentEncoding: string
{
    case PLAIN = 'plain';
    case BASE64 = 'base64';
}
