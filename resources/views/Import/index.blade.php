@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">

			@if(Auth::check() )

			<!-- <div class="panel panel-default">
				<div class="panel-heading">Import <b>Items</b> from Excel file</div>

				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['ImportController@postImportItems']]) !!}
					<div class="panel-body">
						{!! Form::file('file1', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Items', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			</div>

			<div class="panel panel-default">
				<div class="panel-heading">Import <b>Colors</b> from Excel file</div>

				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['ImportController@postImportColors']]) !!}
					<div class="panel-body">
						{!! Form::file('file2', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Colors', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			</div>

			<div class="panel panel-default">
				<div class="panel-heading">Import <b>Sizes</b> from Excel file</div>

				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['ImportController@postImportSizes']]) !!}
					<div class="panel-body">
						{!! Form::file('file3', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Sizes', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			</div> -->

			<div class="panel panel-default">
				<div class="panel-heading">Import from SAP</div>

				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['ImportController@postSAP']]) !!}
					<div class="panel-body">
						{!! Form::file('file4', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			</div>

			@endif
			

			
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection