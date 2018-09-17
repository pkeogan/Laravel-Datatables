{{-- <script> --}}
	var datatable{{ $id }} = $('#datatable-{{ $id }}').DataTable( 
	
		{!! $json !!}
	 );
	 
		var bulkButtons = datatable{{ $id }}.buttons( ['clone:name', 'delete:name'] );
		var singleButtons = datatable{{ $id }}.buttons( ['edit:name'] );
		bulkButtons.disable();
		singleButtons.disable();

	$.fn.actionButtons = function(){
			if($('.action-buttons-large').first().parent().width() < $('.action-buttons-large').first().attr('data-buttons-count') * 40 ){
			$('.action-buttons-large').addClass('hidden');
			$('.action-buttons-small').removeClass('hidden');
		} else {
			console.log( 'big menu' );
			$('.action-buttons-large').removeClass('hidden');
			$('.action-buttons-small').addClass('hidden');
		}
	}


datatable{{ $id }}.on( 'deselect', function ( e, dt, type, indexes ) {
		l = datatable{{ $id }}.rows( { selected: true } ).indexes().length;
    	if (l === 0 ) {
			bulkButtons.disable();
			singleButtons.disable();
		} else if(l === 1){
			bulkButtons.enable();
			singleButtons.enable();
		} else if(l > 1){
			bulkButtons.enable();
			singleButtons.disable();
		}
} );

datatable{{ $id }}.on( 'select', function ( e, dt, type, indexes ) {
	l = datatable{{ $id }}.rows( { selected: true } ).indexes().length;
    	if (l === 0 ) {
			bulkButtons.disable();
			singleButtons.disable();
		} else if(l === 1) {
			bulkButtons.enable();
			singleButtons.enable();
		} else if(l > 1) {
			bulkButtons.enable();
			singleButtons.disable();
		}
} );

datatable{{ $id }}.on( 'column-sizing', function () {
		$('td.action-buttons').actionButtons();
} );