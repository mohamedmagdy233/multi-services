<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{trns('image')}}</label>
                    <input type="file" class="dropify" name="image" data-default-file="{{getFile($obj->image)}}"
                           id="image">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{trns('title')}}</label>
                    <input type="text" class="form-control" name="title" value="{{$obj->title}}" id="title">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="link" class="form-control-label">{{trns('link')}}</label>
                    <input type="url" class="form-control" name="link" value="{{$obj->link}}" id="link">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="start_date" class="form-control-label">{{trns('start_date')}}</label>
                    <input type="date" class="form-control" name="start_date"
                           value="{{\Carbon\Carbon::parse($obj->start_date)->format('Y-m-d')}}" id="start_date">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="end_date" class="form-control-label">{{trns('end_date')}}</label>
                    <input type="date" class="form-control" name="end_date"
                           value="{{\Carbon\Carbon::parse($obj->end_date)->format('Y-m-d')}}"
                           id="end_date">
                </div>
            </div>

{{--            <div class="col-12">--}}
{{--                <div class="form-group">--}}
{{--                    <label for="body" class="form-control-label">{{trns('body')}}</label>--}}
{{--                    <textarea type="text" class="form-control" rows="7" name="body" id="body">{{$obj->body}}</textarea>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trns('close')}}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{trns('update')}}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
