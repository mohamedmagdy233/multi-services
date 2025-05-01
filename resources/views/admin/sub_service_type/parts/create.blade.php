<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="service_type_id" class="form-control-label">{{ trns('service_type') }}</label>
                    <select class="form-control" name="service_type_id" id="service_type_id">
                        <option value="" selected disabled>{{ trns('select') }}</option>
                        @foreach($serviceTypes as $serviceType)
                            <option value="{{ $serviceType->id }}">
                                {{ $serviceType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{trns('name')}}
                    </label>
                    <input type="text" class="form-control" name="name" id="name">
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
