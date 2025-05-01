@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') ?? ''}} | {{ trns('settings') }}
@endsection
@section('page_name')
    الاعدادات
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ trns('settings') }} {{ config()->get('app.name') ?? ''}}</h3>
                </div>
                <div class="card-body">
                    <form id="updateForm" method="POST" enctype="multipart/form-data"
                          action="{{route('settingUpdate',1)}}">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="logo" class="form-control-label">{{ trns('logo') }}</label>
                                    <input type="file" id="testDrop" class="dropify" name="logo"
                                           data-default-file="{{ isset($setting->where('key','logo')->first()->value) ? getFile($setting->where('key','logo')->first()->value) : getFile(null)  }}"/>
                                </div>
                                <div class="col-6">
                                    <label for="favicon" class="form-control-label">{{ trns('favicon') }}</label>
                                    <input type="file" id="testDrop" class="dropify" name="favicon"
                                           data-default-file="{{ isset($setting->where('key','favicon')->first()->value) ? getFile($setting->where('key','favicon')->first()->value) : getFile(null) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">


                            <div class="row">
                                <div class="col-6">

                                    <label for="email" class="form-control-label">{{ trns('email') }}</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="{{ isset($setting->where('key','email')->first()->value) ? $setting->where('key','email')->first()->value : ''}}"
                                    >
                                </div>
                                <div class="col-6">
                                    <label for="phone" class="form-control-label"> {{ trns('phone') }}</label>
                                    <input type="number" class="form-control" name="phone" id="phone"
                                           value="{{ isset( $setting->where('key','phone')->first()->value) ? $setting->where('key','phone')->first()->value : ''}}"
                                    >
                                </div>

                            </div>


                            <hr>
                            <h4 class="text-center">{{  trns('privacy')}}</h4>
                            <div class="row">
                                <div class="col-12">
                                    <label for="privacy" class="form-control-label">{{ trns('privacy') }}</label>
                                    <textarea class="form-control editor" rows="20" name="privacy" id="privacy">
                                        {{isset( $setting->where('key','privacy')->first()->value) ? $setting->where('key','privacy')->first()->value : ''}}
                                    </textarea>
                                </div>

                            </div>
                            <hr>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    id="updateButton">{{ trns('update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')

    <script>
        editScript();
    </script>
@endsection


