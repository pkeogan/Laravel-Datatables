{{-- <script> --}}
@php
	$config = config('datatables.config');
	$config['ajax'] = route($ajax);
	$config['stateSave'] = true;
	$config['autoWidth'] = true;
	foreach($attributes as $attribute)
	{
		$config['columns'][] = $attribute;

	}
	$json = json_encode($config);
	$json = str_replace('"render":"function', '"render":function', $json);
	$json = str_replace(';}"', ';}', $json);
	


@endphp
	var datatable{{ $id }} = $('#datatable-{{ $id }}').DataTable( 
	
		{!! $json !!}
     );
	/*
	Function: actionButtons
	
	This jQuery Function will change the action button state from full width to dropdown style. If the device does not support this function, then the dropdown will be the default
	*/
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

	datatable{{ $id }}.on( 'column-sizing', function () {
		$('td.action-buttons').actionButtons();
} );

	
	


{{-- </script> --}}