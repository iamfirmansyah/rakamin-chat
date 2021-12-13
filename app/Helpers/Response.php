<?php

namespace App\Helpers;


define('START', round(microtime(true) * 1000));

use App\Helpers\ErrorMessageFormatter;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\JsonResponse;


class Response {
    const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    const PHONE_EXISTS = "PHONE_EXISTS";
    const UNKNOWN_RESOURCE = "UNKNOWN_RESOURCE";
    const UNAUTHORIZED = 'UNAUTHORIZED';
    const INVALID_USERNAME = 'INVALID_USERNAME';
    const INVALID_PASSWORD = 'INVALID_PASSWORD';
    const DISALLOWED_FIELD = "DISALLOWED_FIELD";
    const FORBIDDEN = "FORBIDDEN";
    const UNSUPPORTED = 'UNSUPPORTED';
    const NOT_ALLOW = 'NOT_ALLOW';
    const ZERO_DATA = 'ZERO_DATA';

    public static function send(int $code, $data = null, string $message = null, array $headers = [])
    {
        $response = [];

        if (null !== $data) {
            $response['data'] = $data;
        }

        if ($data instanceof MessageBag) {
            $response['data'] = ErrorMessageFormatter::format($data);
        }

        if (null !== $message) {
            $response['message'] = $message;
        }

        $response['time_zone'] = env('APP_TIMEZONE');
        $response['date_time'] = Carbon::now()->format(env('APP_DATETIME_FORMAT'));
        $response['epoch_time'] = Carbon::now()->timestamp;

        if ('testing' !== env('APP_ENV')) {
            $response['execution_time'] = round(microtime(true) * 1000) - START . ' ms';
        }

        $result = (new JsonResponse($response, $code, $headers))->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return $result;
    }

    public static function message(string $message)
    {
        return static::send(400, null, $message);
    }
}
