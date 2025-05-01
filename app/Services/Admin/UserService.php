<?php

namespace App\Services\Admin;

use App\Models\User as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class UserService extends BaseService
{
    protected string $folder = 'admin/user';
    protected string $route = 'users';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('user_type',0)->get();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('status', function ($obj) {
                    return $this->statusDataTable($obj);
                })->editColumn('email', function ($obj) {
                    return $obj->email ? $obj->email : trns('none');
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "",
                'route' => $this->route,
            ]);
        }
    }


}
