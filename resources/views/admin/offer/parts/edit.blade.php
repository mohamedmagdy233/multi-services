<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image" data-default-file="{{ getFile($obj->image) }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }} {{ trns('ar') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title" value="{{$obj->getTranslation('title', 'ar')}}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }} {{ trns('en') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title" value="{{$obj->getTranslation('title', 'en')}} ">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">{{ trns('description') }} {{ trns('ar') }}</label>
                    <textarea  rows="4" type="text" class="form-control" name="description[ar]" id="description">{{$obj->getTranslation('description', 'en')}}</textarea>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">{{ trns('description') }} {{ trns('en') }}</label>
                    <textarea rows="4" type="text" class="form-control" name="description[en]" id="description"> {{$obj->getTranslation('description', 'en')}}</textarea>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="from" class="form-control-label">{{ trns('from') }}</label>
                    <input type="date" class="form-control" name="from" id="from" value="{{ $obj->from }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="to" class="form-control-label">{{ trns('to') }}</label>
                    <input type="date" class="form-control" name="to" id="to" value="{{ $obj->to }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="vendor_id" class="form-control-label">{{ trns('vendor') }}</label>
                    <select class="form-control" name="vendor_id" id="vendor_id">
                        @foreach ($vendor as $vendor )
                        <option value="{{ $vendor->id }}" {{ $obj->vendor->id == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="discount" class="form-control-label">{{ trns('discount') }}</label>
                    <input type="text" class="form-control" name="discount" id="discount" value="{{$obj->discount}}">
                </div>
            </div>

        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
