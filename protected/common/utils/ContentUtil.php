<?php
namespace common\utils;

class ContentUtil
{
    public static function substrText($text, $length)
    {
        $textLength = mb_strlen($text);

        if ($textLength <= $length) {
            return $text;
        }

         $text = mb_substr($text, 0, $length) . '[...]';

        return $text;
    }
}
