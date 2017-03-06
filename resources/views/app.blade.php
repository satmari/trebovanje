<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Trebovanje</title>

	<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel='stylesheet' type='text/css' > -->
    <!-- <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel='stylesheet' type='text/css'> -->
    <!-- <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css' > -->

	
	<link href="{{ asset('/css/app.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/font.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css'>
	
	<!-- <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'> -->
	<link href="{{ asset('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/custom.css') }}" rel='stylesheet' type='text/css'>
	
		
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="http://172.27.161.171/preparation"><b>Preparation App</b></a>
				<a class="navbar-brand" href="">|</a>
				<a class="navbar-brand" href="http://172.27.161.171/trebovanje"><b>Trebovanje App</b></a>
				<a class="navbar-brand" href="">|</a>
				@if(Auth::check() && Auth::user()->level() == 4)
				<a class="navbar-brand" href="http://172.27.161.172/pdm"><span style="color:red;"><b>PDM</b></span></a></li>
				<a class="navbar-brand" href="">|</a>
				@endif
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					
					@if(Auth::check() && Auth::user()->level() == 4)
						{{-- <li><a href="{{ url('/') }}">Make request</a></li> --}}
						<li><a href="{{ url('/table') }}">Trebovanje requests history</a></li>
					@endif

					@if(Auth::check() && Auth::user()->level() == 1)
						<li><a href="{{ url('/tableso') }}">Table (requests)</a></li>
						<li><a href="{{ url('/table') }}">Table (requests+lines)</a></li>
						<li><a href="{{ url('/tabletoprint') }}">To Print</a></li>
						<li><a href="{{ url('/tabletocreate') }}">To Create</a></li>
						<li><a href="{{ url('/refresh') }}">Refresh</a></li>
					
					<li>
						 <button class="btn btn-default dropdown-toggle" style="margin: 6px 5px !important;" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								    Translations
							    <span class="caret"></span>
						  </button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						    
							<li><a href="{{ url('/import') }}">Import file</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="{{ url('/transitem') }}">Item</a></li>
							<li><a href="{{ url('/transsize') }}">Size</a></li>
							<li><a href="{{ url('/transcolor') }}">Color</a></li>
							
						</ul>
					</li>
					@endif


				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						{{-- <li><a href="{{ url('/auth/register') }}">Register</a></li> --}}
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	
	<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>

	<script src="{{ asset('/js/bootstrap-table.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript" ></script>

	<!-- <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jquery.tablesorter.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/tableExport.js') }}" type="text/javascript" ></script>
	<!--<script src="{{ asset('/js/jspdf.plugin.autotable.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jspdf.min.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/FileSaver.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/bootstrap-table-export.js') }}" type="text/javascript" ></script>


    
<script type="text/javascript">
$(function() {
    	
	$('#po').autocomplete({
		minLength: 3,
		autoFocus: true,
		source: '{{ URL('getpodata')}}'
	});
	$('#module').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getmoduledata')}}'
	});
	$('#filter').keyup(function () {

        var rex = new RegExp($(this).val(), 'i');
        $('.searchable tr').hide();
        $('.searchable tr').filter(function () {
            return rex.test($(this).text());
        }).show();
	});

	$('#sort').bootstrapTable({
    
	});

	$('.table tr').each(function(){
  		
  		//$("td:contains('pending')").addClass('pending');
  		//$("td:contains('confirmed')").addClass('confirmed');
  		//$("td:contains('back')").addClass('back');
  		//$("td:contains('error')").addClass('error');
  		//$("td:contains('TEZENIS')").addClass('tezenis');

  		// $("td:contains('TEZENIS')").function() {
  		// 	$(this).index().addClass('tezenis');
  		// }
	});

	$('.to-print').each(function(){
		var qty = $(this).html();
		//console.log(qty);

		if (qty == 0 ) {
			$(this).addClass('zuto');
		} else if (qty > 0) {
			$(this).addClass('zeleno');
		} else if (qty < 0 ) {	
			$(this).addClass('crveno');
		}
	});

	$('.status').each(function(){
		var status = $(this).html();
		//console.log(qty);

		if (status == 'pending' ) {
			$(this).addClass('pending');
		} else if (status == 'confirmed') {
			$(this).addClass('confirmed');
		} else {	
			$(this).addClass('back');
		}
	});

	// $('td').click(function() {
	//    	var myCol = $(this).index();
 	//    	var $tr = $(this).closest('tr');
 	//    	var myRow = $tr.index();

 	//    	console.log("col: "+myCol+" tr: "+$tr+" row:"+ myRow);
	// });

	//baner
	// var target = $('#target');
	// var change = function(str) {
	// var tmp = $('<h2>' + str + '</h2>');
	// tmp.css({
	//   display: "inline-block",
	//   position: "absolute"
	// })
	// .appendTo('body')
	// .hide();
	// var targetWidth = tmp.outerWidth();
	// tmp.remove();
	// target.animate({
	// opacity: 0
	// }, 200, function() {
	// target.animate({
	//   width: targetWidth
	// }, 200, function() {
	//   target.empty()
	//     .html(str)
	//     .css({
	//       display: "initial"
	//     })
	//     .animate({
	//       opacity: 1
	//     }, 200);
	// });
	// });
	// }
	// var samples = [
	// "some sample some sample some sample ",
	// "some sample some sample some sample sample some sample ",
	// "some sample some sample some sample sample some sample sample some sample ",
	// "another example",
	// "just"
	// ];
	// var i = 0;
	// setInterval(function() {
	// change(samples[++i % samples.length]);
	// }, 2000);
	// //

	/*checkbox*/

    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
	

});
</script>
<script type="text/javascript">
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
</script>


</body>
</html>
