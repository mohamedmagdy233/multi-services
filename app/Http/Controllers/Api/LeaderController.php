<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\LeaderService as ObjService;
use Illuminate\Http\Request;


class LeaderController extends Controller
{
    public function __construct(protected ObjService $objService){

    }

    public function getOffers(Request $request)
    {
        try {
            return $this->objService->getOffers($request);
        }catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function getOfferDetails($id)
    {
        try {
            return $this->objService->getOfferDetails($id);
        }catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }


    public function acceptOrRejectOffer(Request $request)
    {
        // try {
            return $this->objService->acceptOrRejectOffer($request);
        // }catch (\Exception $e) {
            // return self::ExeptionResponse();
        // }

    }






}//end class
