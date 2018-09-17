<table id="datatable-{{ $id }}" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
			@foreach($columns as $column)
				<th>{{$column['title']}}</th>
			@endforeach
            </tr>
        </thead>
        <tfoot>
            <tr>
			@foreach($columns as $column)
				<th>{{$column['title']}}</th>
			@endforeach
            </tr>
        </tfoot>
    </table>