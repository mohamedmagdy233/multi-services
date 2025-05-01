<?php

namespace App\Services;

use App\Models\Setting as ObjModel;


class SettingService extends BaseService
{
    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        return view('admin.setting.index');
    } // index

    public function update($data)
    {
        $setting = $this->model->all();
        if (isset($data['logo'])) {
            if ($setting &&file_exists($setting->where('key', 'logo')->first())) {
                unlink($setting->logo);
            }
            $data['logo'] = $this->handleFile($data['logo'], 'uploads/settings');
        }

        if (isset($data['favicon'])) {
            if ($setting &&file_exists($setting->where('key', 'favicon')->first())) {
                unlink($setting->favicon);
            }
            $data['favicon'] = $this->handleFile($data['favicon'], 'uploads/settings');

        }

        foreach ($data as $key => $value) {
            $this->model->updateOrCreate(
                ['key' => $key],
                ['value' => $value]);
        }





       return response()->json(['status' => 200, 'message' => "Done Successfully"]);
    } // update
}
