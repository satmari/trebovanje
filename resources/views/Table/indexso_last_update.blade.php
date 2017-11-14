@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center">
			<div class="panel panel-default">
				<div class="panel-heading">Last used SO</div>
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
				           <th><span style="color: blueviolet;">So</span></th>
				           <th><span style="color: blueviolet;">Po</span></th>
				           <th><span style="color: blueviolet;">Moule</span></th>
				           <th><span style="color: blueviolet;">WMS Status</span></th>
				           <th><span style="color: blueviolet;">PO Status</span></th>
				           <th>Request created</th>
				           <th>SO Due date</th>
				           <th></th>
				          
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	<td><b>{{ $d->so }}</b></td>
				        	<td>{{ $d->po }}</td>
				        	<td>{{ $d->module }}</td>
				        	<td>{{ $d->sowmsstatus }}</td>
				        	<td>{{ $d->postatus }}</td>
				        	<td>{{ substr($d->created, 0, 10)}}</td>
				        	<td>{{ substr($d->lastmodified, 0, 10)}}</td>
				        	<td><a href="{{ url('/wmsclose/'.$d->so) }}" class="btn btn-info btn-xs center-block">Close SO in Navision</a></td>
				        	
						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection