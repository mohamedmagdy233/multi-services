<?php

namespace App\Services\Api;


use App\Http\Resources\Api\BaseOfferResource;
use App\Http\Resources\Api\OfferResource;
use App\Services\Admin\OfferService;
use App\Services\BaseService;
use App\Models\User as ObjModel;


class LeaderService extends BaseService
{

    public function __construct(ObjModel               $model,
                                protected OfferService $offerService,

    )
    {
        parent::__construct($model);

    }


    public function getOffers($request)
    {
        $offers = $this->offerService->model
            ->when(isset($request->status), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })->get();

        return $this->responseMsg('success', BaseOfferResource::collection($offers));
    }

    public function getOfferDetails($id)
    {
        $offer = $this->offerService->model
            ->where('id', $id)
            ->first();

        if (!$offer) {
            return $this->responseMsg('not found');
        }

        return $this->responseMsg('success', new OfferResource($offer));

    }

    public function acceptOrRejectOffer($request)
    {
        $offer = $this->offerService->model
            ->where('id', $request->offer_id)
            ->first();


        if (!$offer || $offer->status !== 0 || auth('api')->user()->user_type !== 1) {
            return $this->responseMsg('not found or already processed or not open or not a leader', null, 404);
        }

        $offer->status = $request->status;
        $offer->save();
        $msg = $request->status == 1 ? 'accepted' : 'rejected';

         // send Firebase notification
         $data=[
            'title'=>'Offer',
            'body'=>'Your offer has been ' . $msg,
            'reference_id'=>$offer->id,
            'reference_table'=>'offers',
            'type'=>'save',
            'is_leader'=>0,
        ];
        $this->sendFcm($data, $offer->user_id);

        return $this->responseMsg($msg . ' Successfully', null);

    }

}
