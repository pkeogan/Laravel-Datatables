$.fn.dataTable.ext.buttons.reload = {
    text: 'Reload',
    action: function ( e, dt, node, config ) {
        dt.ajax.reload();
    }
};

@if(isset($create))
$.fn.dataTable.ext.buttons.create = {
    text: 'Create',
    action: function ( e, dt, node, config ) {
        $(document).createModel();
    }
};
@endif

@if(isset($edit))
$.fn.dataTable.ext.buttons.edit = {
    name: 'edit',
    text: 'Edit',
    action: function ( e, dt, node, config ) {
        id = dt.rows( { selected: true } ).data().pluck('uuid');
        $(document).editModel(id[0]);
    }
};

@endif


@if(isset($delete))
$.fn.dataTable.ext.buttons.delete = {
    name: 'delete',
    text: 'Delete',
    action: function ( e, dt, node, config ) {
               ids = dt.rows( { selected: true } ).data().pluck('uuid').toArray();
               var data = {uuids: ids};
               swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route($delete, 'delete') }}"  ,
                        data: JSON.stringify(data),
                        contentType: "application/json",
                        processData: false,
                        success: function(data) {
                            dt.ajax.reload(null, false);
                            swal({
                                    type: 'success',
                                    title: 'Success',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            swal('Error', XMLHttpRequest.responseJSON.message ,'error');
                        },
                        });
                }
                })
    }
};
@endif

@if(isset($clone))
$.fn.dataTable.ext.buttons.clone = {
    name: 'clone',
    text: 'Clone',
    action: function ( e, dt, node, config ) {
               ids = dt.rows( { selected: true } ).data().pluck('uuid').toArray();
               var data = {uuids: ids};
               swal({
                title: 'Clone Selected?',
                text: "Are you sure you want to clone the selected items?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route($clone, 'clone') }}"  ,
                        data: JSON.stringify(data),
                        contentType: "application/json",
                        processData: false,
                        success: function(data) {
                            dt.ajax.reload(null, false);
                            swal({
                                    type: 'success',
                                    title: 'Success',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            swal('Error', XMLHttpRequest.responseJSON.message ,'error');
                        },
                        });
                }
                })    }
};
@endif

@if(isset($delete))
jQuery.fn.extend({
        destroyModel: function () {
          var id = this.closest('[data-model-id]').attr('data-model-id');
          $.ajax({
            type: "GET",
            url: "{{ route($delete, 'destroy')}}/" + id,
            success: function(data) {
                $("table[id*=datatable]").DataTable().ajax.reload(null, false);
                 swal('Deleted', data.success,'success');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              swal('Error', XMLHttpRequest.responseJSON.message ,'error');
            },
          });
        }
    });
@endif