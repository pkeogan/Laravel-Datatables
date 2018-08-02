<table id="datatable-{{ $id }}" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
			@foreach($attributes as $attribute)
				<th>{{$attribute['title']}}</th>
			@endforeach
            </tr>
        </thead>
        <tfoot>
            <tr>
			@foreach($attributes as $attribute)
				<th>{{$attribute['title']}}</th>
			@endforeach
            </tr>
        </tfoot>
    </table>