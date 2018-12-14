@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Choose printer</div>
				

				<div class="panel-body">
					<div class="">
							
						{!! Form::open(['method'=>'POST', 'url'=>'/printer_set']) !!}
		
							<div class="panel-body">
								{!! Form::select('printer_name', array(''=>'','Cutting A4'=>'Cutting A4','Magacin A4'=>'Magacin A4','Workstudy A4'=>'Workstudy A4'), null, array('class' => 'form-control')); !!} 
							</div>
							<br>

							{!! Form::submit('Set printer', ['class' => 'btn  btn-success center-block']) !!}

							@include('errors.list')

						{!! Form::close() !!}

						<hr>
						
    						<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection