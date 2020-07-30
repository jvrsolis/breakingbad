<?php

namespace BreakingBad\Services;

use Illuminate\Support\Facades\Http;

class API
{
    public const BASE_URL = 'https://www.breakingbadapi.com/api/';

    public static function characters()
    {
        return Http::get(self::BASE_URL . 'characters')->json();
    }
}
