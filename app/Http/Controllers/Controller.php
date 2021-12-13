<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validateError($request, $rules) {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return Response::send(422, $validator->errors());

        return true;
    }
}
