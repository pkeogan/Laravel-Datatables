{{-- <script> --}}
	
	@if(isset($alpaca_route))window.alpaca_url = "{{ route($alpaca_route) }}";@endif
	var datatable{{ $id }} = $('#datatable-{{ $id }}').DataTable( 
		{!! $json !!}
	 );
