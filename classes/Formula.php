<?php

namespace app\classes;


class Formula
{
    const PATTERN = '/^\s*[*]\s*(\d+)\s*(([+-])\s*(\d+))?$/';

    public static function correct($str)
    {
        return preg_match( static::PATTERN, $str);
    }
}