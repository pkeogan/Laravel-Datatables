jQuery.fn.extend({
	formatErrors: function (input) {
	  var formated = '';
	  input = JSON.parse(input);
	  $.each(input.message, function( index, value ) {
		var name = index;
		  $.each(value, function( key, errmsg ){
				formated += '<b>'+name+'</b><p>' + errmsg + '</p>';
		   });
		  });
		  return formated;
	}
});

$.fn.dataTable.ext.buttons.reload = {
    text: 'Reload',
    action: function ( e, dt, node, config ) {
        dt.ajax.reload();
    }
};

$.fn.dataTable.ext.buttons.create = {
    text: 'Create',
    action: function ( e, dt, node, config ) {
        $(document).alpacaCreate();
    }
};

$.fn.dataTable.ext.buttons.edit = {
    name: 'edit',
    text: 'Edit',
    action: function ( e, dt, node, config ) {
        id = dt.rows( { selected: true } ).data().pluck('uuid');
        $(document).alpacaEdit(id[0]);
    }
};



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
                        url: "/api/" +id  ,
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
							$(document).handleError(XMLHttpRequest);                        
						},
                        });
                }
                })
    }
};

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
                        url: "/api/" +id  ,
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
							$(document).handleError(XMLHttpRequest);
						},
                        });
                }
                })    }
};

jQuery.fn.extend({
        destroyModel: function () {
          var id = this.closest('[data-model-id]').attr('data-model-id');
          $.ajax({
            type: "DELETE",
            url: "/api/" + id,
            success: function(data) {
                $("table[id*=datatable]").DataTable().ajax.reload(null, false);
                 swal('Deleted', data.success,'success');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
				$(document).handleError(XMLHttpRequest);
			},
          });
        }
    });
