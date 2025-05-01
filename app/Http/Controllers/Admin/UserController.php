<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest as ObjRequest;
use App\Models\User as ObjModel;
use App\Services\Admin\UserService as ObjService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }



    public function destroy($id)
    {
        return $this->objService->delete($id);
    }
    public function updateColumnSelected(Request $request)
    {

       $action = $this->objService->updateColumnSelected($request,'status');
        foreach ($request->ids as $id) {
            $obj=$this->objService->model->where('id', $id)->first();
            $msg = $obj->status == 1 ? 'active' : 'suspended';


                // send notifications to all active users
                $data = [
                    'title' => 'account',
                    'body' => 'account ' . $msg ,
                    'reference_id' => $obj->id,
                    'reference_table' => 'users',
                    'type'=>'save',
                    'is_leader'=>0,
                ];

                $this->sendFcm($data, $obj->id);
            }

        return $action;
    }

    public function deleteSelected(Request $request){
        return $this->objService->deleteSelected($request);
    }
}
