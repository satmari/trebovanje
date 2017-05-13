@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center">
			<div class="panel panel-default">
				<div class="panel-heading">Request header table (LOG)</div>
								

				<div class="input-group"> <span class="input-group-addon">Filter</span>
				    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
                <table class="table table-striped table-bordered" id="sort" 
                data-show-export="true"
                data-export-types="['excel']"
                >
                <!--
                data-show-toggle="true"
                data-show-columns="true" 
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
				           {{-- <th><span style="color: blueviolet;">Name</span></th> --}}
				           <th data-sortable="true"><span style="color: blueviolet;">Module</span></th>
				           <th data-sortable="true"><span style="color: blueviolet;">Created</span></th>
				           <th><span style="color: blueviolet;">Style</span></th>
				           <th><span style="color: blueviolet;">Color</span></th>
				           <th><span style="color: blueviolet;">Size</span></th>
				           <th><span style="color: blueviolet;">Leader</span></th>
				           <th><span style="color: blueviolet;">Status</span></th>
				           <th data-sortable="true"><span style="color: blueviolet;">Po</span></th>
				           <th><span style="color: blueviolet;">Flash</span></th>
				           <th><span style="color: blueviolet;">First</span></th>
				           <th><span style="color: blueviolet;">Comment</span></th>
				           <th data-sortable="true"><span style="color: blueviolet;">So</span></th>
				           <th></th>
				           <th></th>
				           <th></th>
				           <th></th>

				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}
				        	{{-- <td>{{ $d->name }}</td> --}}
				        	<td>{{ $d->module }}</td>
				        	<td>{{ substr($d->created_at, 0, 19) }}</td>
				        	<td>{{ $d->stylefg }}</td>
				        	<td>{{ $d->colorfg }}</td>
				        	<td>{{ $d->sizefg }}</td>
				        	<td>{{ $d->leader }}</td>
				        	<td><b>{{ $d->status }}</b></td>
				        	<td>{{ $d->po }}</td>
				        	<td>{{ $d->flash }}</td>
				        	<td>{{ $d->first_time }}</td>
				        	<td>{{ $d->comment }}</td>
				        	<td>
				        		@if ($d->so == null)
				        			{{-- <a href="{{ url('/') }}" class="btn btn-success btn-xs center-block" disabled>Refresh</a> --}}
				        			<span style="color: green;">Refresh</span>
				        		@else
				        		 	{{ $d->so }}
				        		@endif
				        	</td>
				        	<td>
				        		<a href="{{ url('/request_lines/'.$d->id) }}" class="btn btn-default btn-xs center-block">Request Lines</a>
				        	</td>
				        	<td>
				        	@if ($d->status == "TO PRINT") 
				        		<a href="{{ url('/print/'.$d->id) }}" class="btn btn-info btn-xs center-block">Print</a>
				        	@else 
				        		@if (($d->status == "PRINTED") AND ($d->so != ""))
				        			<a href="{{ url('/print/'.$d->id) }}" class="btn btn-info btn-xs center-block">Print</a>
				        		@else
				        			<a href="{{ url('/print/'.$d->id) }}" class="btn btn-info btn-xs center-block" disabled>Print</a>
				        		@endif
				        	@endif
				        	</td>
				        	<td>
				        	@if ($d->status == "PRINTED")
				        		<a href="{{ url('/delete_header/'.$d->id) }}" class="btn btn-danger btn-xs center-block" disabled>Cancel header</a>
				        	@else
				        		<a href="{{ url('/delete_header/'.$d->id) }}" class="btn btn-danger btn-xs center-block" >Cancel header</a>
				        	@endif
				        	</td>
				        	<td>
				        		<a href="{{ url('/edit_header/'.$d->id) }}" class="btn btn-default btn-xs center-block" >Edit</a>
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