<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OfferRequest;
use App\Http\Requests\Api\createRoomRequest;
use App\Http\Requests\Api\OffersOnMapRequest;
use App\Services\Api\UserService as ObjService;
use App\Http\Requests\Api\FilteredOffersRequest;
use App\Http\Requests\Api\SubServiceTypesRequest;


class UserController extends Controller
{
    public function __construct(protected ObjService $objService){

    }

    public function getServiceTypes()
    {
        try {
            return $this->objService->getServiceTypes();
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function getSubServiceTypes(SubServiceTypesRequest $request)
    {
        try {
            return $this->objService->getSubServiceTypes($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function addOffer(OfferRequest $request)
    {
        try {
            return $this->objService->addOffer($request->all());
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function updateOffer(Request $request, $id)
    {
        try {
            return $this->objService->updateOffer($request, $id);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function getHome(Request $request)
    {
        try {
            return $this->objService->getHome($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function getOffers(Request $request)
    {
        try {
            return $this->objService->getOffers($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function getOfferDetails($id)
    {
        try {
            return $this->objService->getOfferDetails($id);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function getFilteredOffers(FilteredOffersRequest $request)  // not need it will delete in the end of project
    {
        try {
            return $this->objService->getFilteredOffers($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function getMyOffers(Request $request)
    {
        try {
            return $this->objService->getMyOffers($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function closeOffer($id)
    {
        try {
            return $this->objService->closeOffer($id);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }
    public function openOffer($id)
    {
        try {
            return $this->objService->openOffer($id);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function addOrDeleteFav(Request $request)
    {
        try {
            return $this->objService->addOrDeleteFav($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function getFav(Request $request)
    {
        try {
            return $this->objService->getFav($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }

    }

    public function getOffersOnMap(OffersOnMapRequest $request)
    {

        try {
            return $this->objService->getOffersOnMap($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }
    }

    public function getSettings()
    {

        // try {
            return $this->objService->getSettings();
        // } catch (\Exception $e) {
            // return self::ExeptionResponse();
        // }
    }

    public function getMyChats(Request $request)
    {
        // try {
            return $this->objService->getMyChats($request);
        // } catch (\Exception $e) {
            // return self::ExeptionResponse();
        // }
    }
    public function createRoom(createRoomRequest $request)
    {
        try {
            return $this->objService->createRoom($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }
    }
    public function sendMessage(Request $request)
    {
        try {
            return $this->objService->sendMessage($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }
    }

    public function getRoomMessages($id)
    {
        try {
            return $this->objService->getRoomMessages($id);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }
    }
    public function getNotifications(Request $request)
    {
        try {
            return $this->objService->getNotifications($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }
    }

    public function seeNotification(Request $request)
    {
        try {
            return $this->objService->seeNotification($request);
        } catch (\Exception $e) {
            return self::ExeptionResponse();
        }
    }



}//end class
