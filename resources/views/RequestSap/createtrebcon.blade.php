@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Create new Request for: <b>{{$po}}</b> and <b>{{$fg}}</b> <span class="pull-right">Majstorica: <b>{{$leader}}</b></span></div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>

				@endif

				{!! Form::open(['method'=>'POST', 'url'=>'/requeststoretreb_sap']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />

				{!! Form::hidden('leader', $leader, ['class' => 'form-control']) !!}
				{!! Form::hidden('po', $po, ['class' => 'form-control']) !!}
				{!! Form::hidden('po_sap', $po_sap, ['class' => 'form-control']) !!}
				{!! Form::hidden('fg', $fg, ['class' => 'form-control']) !!}
				{!! Form::hidden('activity', $activity, ['class' => 'form-control']) !!}
				{!! Form::hidden('wc', $wc, ['class' => 'form-control']) !!}
				{!! Form::hidden('list', $list, ['class' => 'form-control']) !!}

				<div class="panel-body">

				<table style="width:100%">
						<th style="width:70%"></th>
						
						<th style="width:30%; text-align:center">Not Standard Qty </th>
						

				@foreach ($newarray as $line)
				
  						<tr>
  							<td style="width:80%">
  								<div class="checkbox">
							    	<label style="width: 90%;" type="button" class="btn check btn-default"  data-color="primary">
							      		<input type="checkbox" class="btn check" name="items[]" value="{{ $line['material'].'#'.$line['uom'].'#'.$line['description'].'#'.$line['standard_qty'].'#'.$line['uom_desc'].'#'.$line['tpa'] }}">  
							      		<input name="hidden[]" type='hidden' value="{{ $line['material'].'#'.$line['uom'].'#'.$line['description'].'#'.$line['standard_qty'].'#'.$line['uom_desc'].'#'.$line['tpa'] }}"> 

							      		{{ $line['description'] }}

										@if (($line['description'] == 'Kuma') OR ($line['description'] == 'Elan'))
												{{ $line['description'] }}
										@endif
										
										-
										<small><i>
										[{{ $line['material'] }}]
										</i></small>
										-
										{{ $line['standard_qty'] }}
										[{{ $line['uom_desc'] }}]
										
										
										
							    	</label>
							  	</div>
  						 	</td>
  							
  							<td style="width:20%;text-align:left;">
  								<input type="number" class="test" name="not_std_qty[]" style="width:50px" value="">  [{{ $line['uom_desc'] }}]
  							</td>
  							
  						</tr>
  				@endforeach
				</table>
			    
			  	
			  	<div class="checkbox">
			    	<label style="width: 30%;" type="button" class="btn check btn-warrning"  data-color="info">
			      		<input type="checkbox" class="btn check" id="checkAll"><b>Izaberi sve</b>
			    	</label>
			  	</div>
					

			    <div class="panel-body">
			    	<p>Comment:</p>
				    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
				</div>				    

				<div class="panel-body">
					{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

				{{--
				<hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
				</div>
				--}}
				
			</div>
		</div>
	</div>
</div>
@endsection