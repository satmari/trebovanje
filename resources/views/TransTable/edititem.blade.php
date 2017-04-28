@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit:</div>
				<br>
					{!! Form::model($data , ['method' => 'POST', 'url' => 'update_item/'.$data->id /*, 'class' => 'form-inline'*/]) !!}

					{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body">
					<p>Item:</p>
						{!! Form::input('string', 'trans', $data->item, ['class' => 'form-control','disabled' => 'disabled']) !!}
					</div>

					<div class="panel-body">
					<p>Item T:</p>
						{!! Form::input('string', 'trans', $data->item_t, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
					<p>Item Std Qty:</p>
						{!! Form::input('integer', 'std_qty', $data->std_qty, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
					<p>Item Std UoM:</p>
						{!! Form::input('string', 'std_uom', $data->std_uom, ['class' => 'form-control']) !!}
					</div>
						
					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}
					<br>
					
					
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default">Back</a>
					</div>
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection