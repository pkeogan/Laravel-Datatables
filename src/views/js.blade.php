{{-- <script> --}}
@php
	$config = config('datatables.config');
	$config['ajax'] = route($ajax);
	$config['stateSave'] = true;
	foreach($attributes as $attribute)
	{
		$config['columns'][] = $attribute;
	}
@endphp
	$('#datatable-{{ $id }}').DataTable( 
		{!! json_encode($config) !!}
     );

{{-- </script> --}}