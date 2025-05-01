@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | {{ $bladeName }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">

									<span>
										<i class="fe fe-plus"></i>
									</span> {{trns('add')}}
                        </button>
                        <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                            <span><i class="fe fe-trash"></i></span>{{trns('deleted_selected')}}
                        </button>

                        <button class="btn btn-secondary btn-icon text-white" id="bulk-update">
                            <span><i class="fe fe-trending-up"></i></span>{{trns('update_selected')}}
                        </button>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px rounded-end">{{trns('title')}} </th>
{{--                                <th class="min-w-50px rounded-end">{{trns('description')}} </th>--}}
                                <th class="min-w-50px rounded-end">{{trns('start_date')}} </th>
                                <th class="min-w-50px rounded-end">{{trns('end_date')}} </th>
                                <th class="min-w-50px rounded-end">{{trns('status')}} </th>
                                <th class="min-w-50px rounded-end">{{trns('link')}} </th>
                                <th class="min-w-50px rounded-end">{{trns('image')}} </th>
                                <th class="min-w-50px rounded-end">{{trns('action')}} </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>{{ trns('are_you_sure_you_want_to_delete_this_obj') }} <span id="title"
                                                                                        class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            {{trns('close')}}
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">{{trns('delete')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">{{ trns('object_details') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->

        <!-- delete selected Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">{{trns('delete')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{trns('are_you_sure_you_want_to_delete_selected_obj')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal"> {{trns('close')}}</button>
                        <button type="button" class="btn btn-danger"
                                id="confirm-delete-btn"> {{trns('delete')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- delete selected Modal -->


        <!-- update cols selected Modal -->
        <div class="modal fade" id="updateConfirmModal" tabindex="-1" role="dialog"
             aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel"> {{trns('update')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{trns('are_you_sure_you_want_to_update_selected_obj')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal"> {{trns('close')}}</button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn"> {{trns('update')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- update selected Modal -->

    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                }
            },
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            // {data: 'body', name: 'body'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'status', name: 'status'},
            {data: 'link', name: 'link'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route($route.'.index')}}', columns);

        // Delete Using Ajax
        deleteScript('{{route($route.'.destroy',':id')}}');
        deleteSelected('{{route($route.'.deleteSelected')}}');

        updateColumnSelected('{{route($route.'.updateColumnSelected')}}');


        // Add Using Ajax
        showAddModal('{{route($route.'.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route($route.'.edit',':id')}}');
        editScript();
    </script>

       <script>
        // for status
        $(document).on('click', '.statusBtn', function() {
            let id = $(this).data('id');

            var val = $(this).is(':checked') ? 1 : 0;

            let ids = [id];




            $.ajax({
                type: 'POST',
                url: '{{ route($route . '.updateColumnSelected') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function(data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('Success', "نشط");
                        } else {
                            toastr.warning('Success', "غير نشط ");
                        }
                    } else {
                        toastr.error('Error', "حدث error ما");
                    }
                },
                error: function() {
                    toastr.error('Error', "حدث error ما");
                }
            });
        });



    </script>


@endsection


