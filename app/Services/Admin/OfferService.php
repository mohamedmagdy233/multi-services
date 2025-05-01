<?php

namespace App\Services\Admin;
use App\Models\Offer as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class OfferService extends BaseService
{
    protected string $folder = 'admin/offer';
    protected string $route = 'offers';

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

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })
                ->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('service_type_id', function ($obj) {
                    return $obj->serviceType->name;
                })->editColumn('sub_service_type_id', function ($obj) {
                    return $obj->subServiceType->name;
                })->editColumn('is_open', function ($obj) {
                    return $obj->is_open ? trns('open') : trns('close');
                })->editColumn('user_id', function ($obj) {
                    return $obj->user_id ? $obj->user->name : '';
                })->editColumn('status', function ($obj) {
                    return $this->statusDataTable($obj);
                })

                ->editColumn('body', function ($obj) {
                    return $this->subStrText($obj->body);
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '.index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }
}
