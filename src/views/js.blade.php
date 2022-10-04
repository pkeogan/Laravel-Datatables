{{-- <script> --}}
	@if(isset($alpaca_route))window.alpaca_url = "{{ route($alpaca_route) }}";@endif


  window.datatables = {};
  window.datatables.updateEvent = new Event('DatableSelected');
  window.datatables.{{ $id }} = {};
  window.datatables.{{ $id }}.selected = new Array();
  window.datatables.{{ $id }}.selectedData = new Array();
  
  window.datatables.{{ $id }}.table = $('#datatable-{{ $id }}').DataTable( 
		{!! $json !!}
	 );
  
window.datatables.{{ $id }}.table.on( 'select', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
       var data = window.datatables.{{ $id }}.table.rows('.selected').data();
      
       var temp = new Array();
       var temp2 = new Array();
        $.each( data, function( key, value ) { 
          if(value.uuid != undefined){
            temp.push(value.uuid);
            temp2.push(value);
          }
        });
          window.datatables.{{ $id }}.selected = temp;
          window.datatables.{{ $id }}.selectedData = temp2;
          document.dispatchEvent(window.datatables.updateEvent);
    }
});
  
window.datatables.{{ $id }}.table.on( 'deselect', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
       var data = window.datatables.{{ $id }}.table.rows('.selected').data();

       var temp = new Array();
       var temp2 = new Array();
        $.each( data, function( key, value ) { 
          if(value.uuid != undefined){
            temp.push(value.uuid);
            temp2.push(value);
          }
        });
         window.datatables.{{ $id }}.selected = temp;
         window.datatables.{{ $id }}.selectedData = temp2;
      
         document.dispatchEvent(window.datatables.updateEvent);
    }
});
  
  
