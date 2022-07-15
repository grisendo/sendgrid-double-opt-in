<?php

declare(strict_types=1);

namespace Grisendo\DDD;

class Validator
{
    public static function hasMoreThanNCharacters(string $text, int $size): bool
    {
        return mb_strlen($text) > $size;
    }

    public static function hasLessThanNCharacters(string $text, int $size): bool
    {
        return mb_strlen($text) < $size;
    }

    public static function isValidEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
