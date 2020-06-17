@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center">
			<div class="panel panel-default">
				<div class="panel-heading">Request history (last 14 days)<div>
				
				<div class="input-group"><span class="input-group-addon">Filter</span>
				    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered" id="sort" 
                data-pagination="true"
                >
				<!--
				data-show-export="true"
                data-export-types="['excel']"
	            data-show-export="true"
                data-export-types="['excel']"
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
				        <tr>{{-- <th>id</th> --}}
				           {{-- <th><span style="color: blueviolet;">Name</span></th> --}}
				           <th data-sortable="true"><span style="color: blueviolet;">Line</span></th>
				           <th data-sortable="true"><span style="color: blueviolet;">Created</span></th>
				           <th><span style="color: blueviolet;">Style</span></th>
				           {{--<th><span style="color: blueviolet;">Color</span></th>--}}
				           {{--<th><span style="color: blueviolet;">Size</span></th>--}}
				           <th><span style="color: blueviolet;">Leader</span></th>
				           <th><span style="color: blueviolet;">Status</span></th>
				           <th><span style="color: blueviolet;">Po</span></th>
				           <th><span style="color: blueviolet;">Flash</span></th>
				           {{-- <th><span style="color: blueviolet;">First</span></th>--}}
				           {{-- <th><span style="color: blueviolet;">So</span></th>--}}
				           {{-- <th><span style="color: blueviolet;">Comment</span></th> --}}
				           <th><span style="color: darkorange;">Material</span></th>
				           <th><span style="color: darkorange;">Desc</span></th>
				           {{--<th><span style="color: darkorange;">Size</span></th>--}}
				           <th><span style="color: darkorange;">Qty</span></th>
				           <th><span style="color: darkorange;">UoM</span></th>
				           <th><span style="color: darkorange;">UoM Desc</span></th>
				           {{--<th><span style="color: darkorange;">Hu</span></th>--}}
				          
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
				        	
				        	
				        	<td>{{ $d->leader }}</td>
				        	<td><b>{{ $d->status }}</b></td>
				        	<td>{{ $d->po }}</td>
				        	<td>{{ $d->flash }}</td>
				        	
				        	<td>{{ $d->material }}</td>
				        	<td>{{ $d->description }}</td>
				        	
				        	<td>{{ $d->standard_qty }}</td>
				        	<td>{{ $d->uom }}</td>
				        	<td>{{ $d->uom_desc }}</td>
				        	{{--<td>{{ $d->created_at }}</td>--}}
						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection