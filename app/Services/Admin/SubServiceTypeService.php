<?php

namespace App\Services\Admin;

use App\Models\SubServiceType as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class SubServiceTypeService extends BaseService
{
    protected string $folder = 'admin/sub_service_type';
    protected string $route = 'sub_service_types';

    public function __construct(ObjModel $objModel,protected ServiceTypeService $serviceTypeService)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
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
                })->addColumn('service_type_id', function ($obj) {
                    return $obj->serviceType->name;
                })->editColumn('status', function ($obj) {
                    return $this->statusDataTable($obj);
                })->escapeColumns([])
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns('sub_service_type'),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'serviceTypes' => $this->serviceTypeService->model->apply()->get(),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "Done Successfully"]);
        } catch (\Exception $e) {
return response()->json(['status' => 500, 'message' => 'some thing want wrong', 'error' => $e->getMessage()]);

        }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'serviceTypes' => $this->serviceTypeService->model->apply()->get(),
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'SubServiceType');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "Done Successfully"]);

        } catch (\Exception $e) {
return response()->json(['status' => 500, 'message' => 'some thing want wrong', 'error' => $e->getMessage()]);

        }
    }
}
