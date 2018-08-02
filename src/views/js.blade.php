{{-- <script> --}}
@php
	$config = config('datatables.config');
	$config['ajax'] = route($ajax);
	$config['stateSave'] = true;
	foreach($attributes as $attribute)
	{
		$config['columns'][] = $attribute;

	}
	$json = json_encode($config);
	$json = str_replace('"render":"function', '"render":function', $json);
	$json = str_replace(';}"', ';}', $json);
	


@endphp
	$('#datatable-{{ $id }}').DataTable( 
		{!! $json !!}
     );

{{-- </script> --}}