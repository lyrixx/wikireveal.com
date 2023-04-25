<?php

namespace App\Text;

class Hasher
{
    public function hash(string $string): string
    {
        // return $string;
        return mb_substr(sha1($string), 0, 10);
    }
}
