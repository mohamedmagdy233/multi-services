<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }} {{ trns('ar') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }} {{ trns('en') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">{{ trns('description') }} {{ trns('ar') }}</label>
                    <textarea  rows="4" type="text" class="form-control" name="description[ar]" id="description"></textarea>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">{{ trns('description') }} {{ trns('en') }}</label>
                    <textarea rows="4" type="text" class="form-control" name="description[en]" id="description"></textarea>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="from" class="form-control-label">{{ trns('from') }}</label>
                    <input type="date" class="form-control" name="from" id="from">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="to" class="form-control-label">{{ trns('to') }}</label>
                    <input type="date" class="form-control" name="to" id="to">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="vendor_id" class="form-control-label">{{ trns('vendor') }}</label>
                    <select class="form-control" name="vendor_id" id="vendor_id">
                        @foreach ($vendor as $vendor )
                        <option value="{{ $vendor->id}}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="discount" class="form-control-label">{{ trns('discount') }}</label>
                    <input type="text" class="form-control" name="discount" id="discount">
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
</script>
