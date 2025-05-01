<?php

namespace App\Services\Admin;

use App\Models\GeneralOffer as ObjModel;
use App\Services\BaseService;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class GeneralOfferService extends BaseService
{
    protected string $folder = 'admin/general_offer';
    protected string $route = 'general_offers';

    public function __construct(ObjModel $objModel)
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
                })->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('start_date', function ($obj) {
                    return Carbon::parse($obj->start_date)->format('d F Y');
                })->editColumn('end_date', function ($obj) {
                    return Carbon::parse($obj->end_date)->format('d F Y');
                })->editColumn('status', function ($obj) {
                    return $this->statusDataTable($obj);
                })->editColumn('link', function ($obj) {

                    return $this->linkDataTable($obj->link, 'link');
                })->editColumn('body', function ($obj) {
                    return $this->subStrText($obj->body);
                })->escapeColumns([])
                ->escapeColumns([])
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns('general_offer'),
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

        // try {
            if (isset($data['image'])) {
                $data['image'] = $this->handleFile($data['image'], 'GeneralOffer');
            }

            $obj = $this->createData($data);
            if ($obj) {

                // send Firebase notification
                $data = [
                    'title' => 'ads',
                    'body' => 'new ads was added',
                    'reference_id' => $obj->id,
                    'reference_table' => 'general_offers',
                    'type' => 'save',
                    'is_leader' => 0,
                    'is_admin' => 1,
                ];

                $this->sendFcm($data);

            }
            return response()->json(['status' => 200, 'message' => "Done Successfully"]);

        // } catch (\Exception $e) {
            // return response()->json(['status' => 500, 'message' => 'some thing want wrong', 'error' => $e->getMessage()]);
        // }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'GeneralOffer');

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
