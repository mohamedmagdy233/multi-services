<?php

namespace App\Services\Api;

use App\Models\Fav;
use App\Models\Chat;
use App\Models\Room;
use App\Models\Media;
use App\Models\Notification;
use App\Services\BaseService;
use App\Models\User as ObjModel;
use App\Services\SettingService;
use App\Services\Admin\OfferService;
use App\Http\Resources\Api\ChatResource;
use App\Http\Resources\Api\RoomResource;
use App\Http\Resources\Api\OfferResource;
use App\Services\Admin\ServiceTypeService;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\SettingResource;
use App\Services\Admin\GeneralOfferService;
use App\Http\Resources\Api\BaseOfferResource;
use App\Services\Admin\SubServiceTypeService;
use App\Http\Resources\Api\SubServiceResource;
use App\Http\Resources\Api\GeneralOfferResource;
use App\Http\Resources\Api\NotificationResourece;

class UserService extends BaseService
{

    public function __construct(
        ObjModel                        $model,
        protected ServiceTypeService    $serviceTypeService,
        protected SubServiceTypeService $subServiceTypeService,
        protected Media                 $media,
        protected GeneralOfferService   $generalOfferService,
        protected OfferService          $offerService,
        protected Fav                   $fav,
        protected Chat $chat,
        protected Room $room,
        protected SettingService $settingService,
        protected Notification $notification
    ) {
        parent::__construct($model);
    }

    public function getServiceTypes()
    {
        return $this->responseMsg('success', ServiceResource::collection($this->serviceTypeService->model->apply()->get()));
    }


    public function getSubServiceTypes($request)
    {
        $subServiceType = $this->subServiceTypeService->model->apply()->where('service_type_id', $request->service_type_id)->get();

        return $this->responseMsg('success', SubServiceResource::collection($subServiceType));
    }

    public function addOffer(array $data)
    {


        $mediaFiles = $data['media'] ?? [];
        unset($data['media']);
        $data['user_id'] = auth('api')->id();

        $offer = $this->offerService->createData($data);

        foreach ($mediaFiles as $media) {
            $media = $this->handleFile($media, 'Offer');
            $offer->media()->create(['file' => $media]);
        }
        if ($offer) {
            // send Firebase notification
            $data = [
                'title' => 'New Offer',
                'body' => 'New Offer Added please check it',
                'reference_id' => $offer->id,
                'reference_table' => 'offers',
                'type' => 'save',
                'is_leader' => 1,
            ];
            $this->sendFcm($data);

            return $this->responseMsg('data added Successfully', null);
        }
        return $this->responseMsg('data not added', null, 400);
    }

    public function getHome($data)
    {
        $service_type_id = $data->service_type_id;
        $search = $data->search;
        $generalOffers = $this->generalOfferService->model->apply()->where('end_date', '>=', date('Y-m-d H:i:s'))->get();
        $offers = $this->offerService->model->apply()->where('is_open', 1)->where('user_id', '!=', auth('api')->user()->id)
            ->when($service_type_id, function ($query) use ($service_type_id) {
                return $query->where('service_type_id', $service_type_id);
            })->when('search', function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })->orderBy('price', 'desc')
            ->take(3)->get();

        $data = [
            'slider' => GeneralOfferResource::collection($generalOffers),
            'service_types' => ServiceResource::collection($this->serviceTypeService->model->apply()->get()),
            'recommended' => BaseOfferResource::collection($offers),
        ];

        return $this->responseMsg('data added Successfully', $data);
    }

    public function getOffers($data)
    {
        $service_type_id = $data->service_type_id;
        $search = $data->search;
        $offers = $this->offerService->model->apply()->where('is_open', 1)->where('user_id', '!=', auth('api')->user()->id)
            ->when($service_type_id, function ($query) use ($service_type_id) {
                return $query->where('service_type_id', $service_type_id);
            })->when('search', function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })->when($data->sub_service_type_id, function ($query) use ($data) {
                return $query->where('sub_service_type_id', $data->sub_service_type_id);
            })
            ->when($data->min_price, function ($query) use ($data) {
                return $query->where('price', '>=', $data->min_price);
            })
            ->when($data->max_price, function ($query) use ($data) {
                return $query->where('price', '<=', $data->max_price);
            })
            ->orderBy('price', 'desc')
            ->get();

        if ($data->type && $data->lat && $data->long && $offers->count() > 0) {
            $offers = $this->sortByNearestDistance($offers, $data->lat, $data->long, $data->type);
        }

        return $this->responseMsg('data added Successfully', BaseOfferResource::collection($offers));
    }

    public function getOfferDetails($id)
    {
        $offer = $this->offerService->model->find($id);
        return $this->responseMsg('data added Successfully', new OfferResource($offer));
    }

    public function getFilteredOffers($data)
    {
        $objs = $this->offerService->model->apply()->where('service_type_id', $data->service_type_id)
            ->where('sub_service_type_id', $data->sub_service_type_id)
            ->where('price', '>=', $data->min_price)
            ->where('price', '<=', $data->max_price)
            ->get();


        $offers = $objs && $objs->count() > 0 ? $this->sortByNearestDistance($objs, $data->lat, $data->long, $data->type) : [];
        return $this->responseMsg('data added Successfully', OfferResource::collection($offers));
    }

    private function sortByNearestDistance($offers, $lat, $long, $type)
    {
        if ($lat && $long) {
            $offers = $offers->toQuery();
            $offers->selectRaw(
                "
                offers.*,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(lat)) *
                    cos(radians(`long`) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(lat))
                )) AS distance",
                [$lat, $long, $lat]
            )->orderBy('distance', $type);

            return $offers->get();
        } else {
            return response()->json(['error' => 'Latitude and Longitude are required for nearestDistance sorting'], 400);
        }
    }

    public function getMyOffers($data)
    {
        $service_type_id = $data->service_type_id;
        $search = $data->search;
        $offers = $this->offerService->model->where('user_id', auth('api')->id())
            ->when($service_type_id, function ($query) use ($service_type_id) {
                return $query->where('service_type_id', $service_type_id);
            })->when('search', function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })->get();

        return $this->responseMsg('data added Successfully', BaseOfferResource::collection($offers));
    }

    public function closeOffer($id)
    {
        $offer = $this->offerService->model->find($id);
        if (!$offer || $offer->is_open == 0) {
            return $this->responseMsg('offer not found or already closed', null, 404);
        }
        $offer->update(['is_open' => 0]);
        return $this->responseMsg('data updated Successfully', null);
    }

    public function addOrDeleteFav($request)
    {
        $user = auth('api')->user();
        $fav = $this->fav->where('user_id', $user->id)->where('offer_id', $request->offer_id)->first();
        if ($fav) {
            $fav->delete();
            return $this->responseMsg('removed Successfully', null);
        }
        $this->fav->create(['user_id' => $user->id, 'offer_id' => $request->offer_id]);

        return $this->responseMsg('added Successfully', null);
    }

    public function getFav($request)
    {
        $search = $request->search;

        $user = auth('api')->user();
        $offer_ids = $this->fav->where('user_id', $user->id)->pluck('offer_id')->toArray();
        $favOffers = $this->offerService->model->whereIn('id', $offer_ids)
            ->when('search', function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })->get();
        return $this->responseMsg('data added Successfully', BaseOfferResource::collection($favOffers));
    }


    public function getOffersOnMap($data)
    {
        $offers = $this->offerService->model->apply()
            ->when($data->search, function ($query) use ($data) {
                return $query->where('title', 'like', '%' . $data->search . '%');
            })->get();

        // fillter base on min distace
        if ($data->distance && $data->lat && $data->long && $offers->count() > 0) {

            $offers = $this->sortByNearestToMe($offers, $data->lat, $data->long, $data->distance);
        }
        return $this->responseMsg('data added Successfully', BaseOfferResource::collection($offers));
    }

    private function sortByNearestToMe($offers, $lat, $long, $distance)
    {
        if ($lat && $long && $distance) {
            $offers = $offers->toQuery();
            $offers->selectRaw(
                "
            offers.*,
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(lat)) *
                cos(radians(`long`) - radians(?)) +
                sin(radians(?)) *
                sin(radians(lat))
            )) AS distance",
                [$lat, $long, $lat]
            )
                ->having('distance', '<=', $distance)
                ->orderBy('distance', 'asc');

            return $offers->get();
        } else {
            return response()->json(['error' => 'Latitude, Longitude, and Distance are required'], 400);
        }
    }

    public function getSettings()
    {
        $settings = $this->settingService->getAll();

        $data = [
            'privacy' => $settings->where('key', 'privacy')->first() ? $settings->where('key', 'privacy')->first()->value : null,
            'phone' => $settings->where('key', 'phone')->first() ? $settings->where('key', 'phone')->first()->value : null,
            'email' => $settings->where('key', 'email')->first() ? $settings->where('key', 'email')->first()->value : null,
        ];


        return $this->responseMsg('data fetched Successfully', $data);
    }


    public function getMyChats($request)
    {
        $user = auth('api')->user();
        $chats = $this->room->where('sender_id', $user->id)
            ->with('chats')
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->responseMsg('data added Successfully', ChatResource::collection($chats));
    }

    public function createRoom($request)
    {
        $user = auth('api')->user();
        $room = $this->room->where('sender_id', $user->id)
            ->where('receiver_id', $request->receiver_id)
            ->where('receiver_id', '!=', $user->id)
            ->first();

        if (!$room) {
            $room = $this->room->create([
                'sender_id' => $user->id,
                'receiver_id' => $request->receiver_id,
            ]);

            return $this->responseMsg('room created Successfully', $data = [
                'room_id' => $room->id,
            ]);
        }

        return $this->responseMsg('room already created', $data = [
            'room_id' => $room->id,
        ]);
    }
    public function sendMessage($request)
    {
        $user = auth('api')->user();
        $room = $this->room->where('id', $request->room_id)
            ->first();

        if (!$room) {
            return $this->responseMsg('room not found', null, 404);
        }
        //check if type is file
        if ($request->type == 1) {
            $file = $this->handleFile($request->message, 'Chat');
        } else {
            $file = null;
        }
        $message = $request->type == 1 ? $file : $request->message;


        $message = $this->chat->create([
            'room_id' => $room->id,
            'sender_id' => $user->id,
            'receiver_id' => $room->receiver_id==$user->id ? $room->sender_id : $room->receiver_id,
            'message' => $message,
            'type' => $request->type,
        ]);

        if ($message) {



            // send Firebase notification
            $data = [
                'title' => $user->name,
                'body' => $request->type == 1 ? 'New File sent please check it' : $request->message,
                'user_id' => $message->receiver_id,
                'reference_id' => $message->room_id,
                'reference_table' => 'rooms',
                'type' => 'not_save'
            ];
            $this->sendFcm($data, $message->receiver_id);
        }


        return $this->responseMsg('data added Successfully', new RoomResource($message));
    }

    public function getRoomMessages($id)
    {
        $user = auth('api')->user();
        $room = $this->room->where('id', $id)
            // ->where('sender_id', $user->id)
            // ->orWhere('receiver_id', $user->id)
            ->first();

        if (!$room) {
            return $this->responseMsg('room not found', null, 404);
        }

        $messages = $this->chat->where('room_id', $room->id)->get();

        return $this->responseMsg('data added Successfully', RoomResource::collection($messages));
    }

    public function getNotifications($request)
    {
        $user = auth('api')->user();
        $notifications = $this->notification->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return $this->responseMsg('data added Successfully', NotificationResourece::collection($notifications));
    }
    public function seeNotification($request)
    {
        $user = auth('api')->user();
        $notification = $this->notification->where('user_id', $user->id)->where('id', $request->notification_id)->first();
        if (!$notification) {
            return $this->responseMsg('notification not found', null, 404);
        }
        $notification->update(['is_seen' => 1]);
        return $this->responseMsg('data updated Successfully', null);
    }
}
