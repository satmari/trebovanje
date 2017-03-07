@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center">
			<div class="panel panel-default">
				<div class="panel-heading">Request header+line table</div>
				
				<div class="input-group"> <span class="input-group-addon">Filter</span>
				    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered" id="sort" 
                data-show-export="true"
                data-export-types="['excel']"
                data-show-toggle="true"
                data-show-columns="true" 
                >
                <!--
                data-show-export="true"
                data-export-types="['excel']"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-query-params="queryParams" 
                data-pagination="true"
                data-height="300"
                data-show-columns="true" 
                data-export-options='{
                         "fileName": "preparation_app", 
                         "worksheetName": "test1",         
                         "jspdf": {                  
                           "autotable": {
                             "styles": { "rowHeight": 20, "fontSize": 10 },
                             "headerStyles": { "fillColor": 255, "textColor": 0 },
                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
                           }
                         }
                       }'
                -->
				    <thead>
				        <tr>
				           {{-- <th>id</th> --}}
				           <th><span style="color: blueviolet;">Name</span></th>
				           <th><span style="color: blueviolet;">Style</span></th>
				           <th><span style="color: blueviolet;">Color</span></th>
				           <th><span style="color: blueviolet;">Size</span></th>
				           <th><span style="color: blueviolet;">Moule</span></th>
				           <th><span style="color: blueviolet;">Leader</span></th>
				           <th><span style="color: blueviolet;">Status</span></th>
				           <th><span style="color: blueviolet;">Po</span></th>
				           <th><span style="color: blueviolet;">First</span></th>
				           <th><span style="color: blueviolet;">So</span></th>
				           {{-- <th><span style="color: blueviolet;">Comment</span></th> --}}
				           <th><span style="color: darkorange;">Item</span></th>
				           <th><span style="color: darkorange;">Color</span></th>
				           <th><span style="color: darkorange;">Size</span></th>
				           <th><span style="color: darkorange;">Hu</span></th>
				           
				           <!-- <th>Created</th> -->
				           <th></th>

				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}
				        	<td>{{ $d->name }}</td>
				        	<td>{{ $d->stylefg }}</td>
				        	<td>{{ $d->colorfg }}</td>
				        	<td>{{ $d->sizefg }}</td>
				        	<td>{{ $d->module }}</td>
				        	<td>{{ $d->leader }}</td>
				        	<td><b>{{ $d->status }}</b></td>
				        	<td>{{ $d->po }}</td>
				        	<td>{{ $d->first_time }}</td>
				        	<td>
				        		@if ($d->so == null)
				        			{{-- <a href="{{ url('/') }}" class="btn btn-success btn-xs center-block" disabled>Refresh</a> --}}
				        			<span style="color: green;">Refresh</span>
				        		@else
				        		 	{{ $d->so }}
				        		@endif
				        	</td>
				        	{{-- <td>{{ $d->comment }}</td> --}}
				        	<td>{{ $d->item }}</td>
				        	<td>{{ $d->color }}</td>
				        	<td>{{ $d->size }}</td>
				        	<td>{{ $d->hu }}</td>
				        	{{--<td>{{ $d->created_at }}</td>--}}
				        	<td>
				        	@if ($d->status == "PRINTED")
				        		<a href="{{ url('/delete_line/'.$d->lineid) }}" class="btn btn-danger btn-xs center-block" disabled>Cancel</a>
				        	@else
				        		<a href="{{ url('/delete_line/'.$d->lineid) }}" class="btn btn-danger btn-xs center-block" >Cancel</a>
				        	@endif
				        	</td>

						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection