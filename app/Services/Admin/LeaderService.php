<?php

namespace App\Services\Admin;

use App\Models\User as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class LeaderService extends BaseService
{
    protected string $folder = 'admin/leader';
    protected string $route = 'leaders';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('user_type',1)->get();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
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
                'bladeName' => trns('Leader'),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {


        try {
            if (isset($data['image'])) {
                $data['image'] = $this->handleFile($data['image'], 'Leader');
            }
            $data['user_type'] = 1;
            $data['password']=Hash::make($data['password']);
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "Done Successfully"]);
        } catch (\Exception $e) {
return response()->json(['status' => 500, 'message' => 'some thing want wrong', 'error' => $e->getMessage()]);

        }
    }

    public function edit($id)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $this->getById($id),
            'updateRoute' => route("{$this->route}.update", $id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Leader');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        if ($data['password'] && $data['password'] != null) {

            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "Done Successfully"]);

        } catch (\Exception $e) {
return response()->json(['status' => 500, 'message' => 'some thing want wrong', 'error' => $e->getMessage()]);

        }
    }
}
