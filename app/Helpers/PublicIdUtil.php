<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PublicIdUtil
{
    public static function unique($table, $col, $maxLength = 24, $multiplyBy = 4, $publicId = "")
    {
        while (true) {
            $rand = rand(1, 10000);
            $hash = sha1($rand . microtime());

            $substringHash = substr($hash, 0, $maxLength);

            for ($i = 0; $i < $maxLength; $i++) {
                if (($i % $multiplyBy) == 0 && $i > 0) {
                    $publicId .= '-';
                }

                $publicId .= $substringHash[$i];
            }

            $isExist = DB::table($table)->where($col, $publicId)->exists();

            if ($isExist) continue;

            return $publicId;
        }
    }

    public static function uniqueDefault($table, $col, $initial, $length = 8) {
        $id = $initial;

        while (strlen($id) <= $length) {
            if (strlen($id) == 2 || strlen($id) == 3 || strlen($id) == 6 || strlen($id) == 7) $id .= chr(mt_rand(65,90)); // Generate String Random
            else $id .= mt_rand(0, 9);

            if (strlen($id) == 8) {
                $isExist = DB::table($table)->where($col, $id)->exists();

                if ($isExist) {
                    $id = $initial;
                    continue;
                }

                return $id;
            }
        }
    }

    public static function random($size, $withSpecialCharacters = false)
    {
        $code = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $code .= "abcdefghijklmnopqrstuvwxyz";
        $code .= "0123456789";

        $token = self::generate($code, $size, $withSpecialCharacters);

        return $token;
    }

    private static function generate($characters, $size, $withSpecialCharacters = false)
    {
        if ($withSpecialCharacters) {
            $characters .= '!@#$%^&*()';
        }

        $token = '';
        $max = strlen($characters);
        for ($i = 0; $i < $size; $i++) {
            $token .= $characters[random_int(0, $max - 1)];
        }

        return $token;
    }
}
