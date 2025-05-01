<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer as ObjModel;
use App\Services\Admin\OfferService as ObjService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function updateColumnSelected(Request $request)
    {

       $action = $this->objService->updateColumnSelected($request,'status');
        foreach ($request->ids as $id) {
            $obj=$this->objService->model->where('id', $id)->first();
            $msg = $obj->status == 1 ? 'accepted' : 'suspended';


                // send notifications to all active users
                $data = [
                    'title' => 'Offer',
                    'body' => 'Offer ' . $msg . ' You can view it',
                    'reference_id' => $obj->id,
                    'reference_table' => 'offers',
                    'type'=>'save',
                    'is_leader'=>0,


                ];
                $this->sendFcm($data, $obj->user_id);
            }

        return $action;
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }





}
