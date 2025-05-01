<?php

namespace App\Http\Controllers;

use App\Traits\FirebaseNotification;

abstract class Controller
{

    use FirebaseNotification;
    public function responseMsg($msg, $data = null, int $status = 200)
    {
        return response()->json([
            'msg' => $msg,
            'data' => $data,
            'status' => $status
        ]);
    }

    public function ExeptionResponse($msg = "تعذر الحصول على البيانات")
    {
        return response()->json([
            'msg' => $msg,
            'data' => null,
            'status' => 500
        ]);
    }
}
