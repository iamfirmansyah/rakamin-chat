<?php

namespace App\Helpers;

use Illuminate\Support\MessageBag;

class ErrorMessageFormatter
{
    public static function format(MessageBag $errors)
    {
        $messages = [];

        $errors = $errors->toArray();

        foreach ($errors as $field => $validations) {
            $messages[] = ['Field' => $field, 'Error' => $validations[0]];
        }

        return $messages;
    }
}
