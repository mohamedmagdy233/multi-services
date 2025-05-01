<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{trns('image')}}
                    </label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{trns('name')}}
                    </label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="need_price" class="form-control-label">{{ trns('need_price') }}</label>
                    <select class="form-control" name="need_price" id="need_price">
                        <option value="" selected disabled>{{ trns('select') }}</option>
                        <option value="1">{{ trns('yes') }}</option>
                        <option value="0">{{ trns('no') }}</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> {{trns('close')}}</button>
            <button type="submit" class="btn btn-primary" id="addButton"> {{trns('save')}}
            </button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
