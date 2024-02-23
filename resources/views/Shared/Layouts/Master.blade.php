<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
<head>

    <title>
        @section('title')
            {{config('app.name')}} -
        @show
    </title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @include('Shared.Layouts.ViewJavascript')

    <!--Meta-->
    @include('Shared.Partials.GlobalMeta')
   <!--/Meta-->

    <!--JS-->
    {!! HTML::script(config('attendize.cdn_url_static_assets').'/vendor/jquery/dist/jquery.min.js') !!}
    <!--/JS-->

    <!--Style-->
    {!! HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/application.css') !!}
    <!--/Style-->
    
    <script src="https://kit.fontawesome.com/b9a310c037.js" crossorigin="anonymous"></script>

    @yield('head')
</head>
<body class="attendize">
@yield('pre_header')
<header id="header" class="navbar">

    <div class="navbar-header">
        <a class="navbar-brand" href="javascript:void(0);">
            <img style="width:150px !important;" class="logo" alt="Attendize" src="{{asset('assets/images/logo-light.png')}}"/>
        </a>
    </div>

    <div class="navbar-toolbar clearfix">
        @yield('top_nav')

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown profile">

                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="meta">
                        <span class="text ">{{isset($organiser->name) ? $organiser->name : $event->organiser->name}}</span>
                        <span class="arrow"></span>
                    </span>
                </a>


                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="{{route('showCreateOrganiser')}}">
                            <i class="ico ico-plus"></i>
                            @lang("Top.create_organiser")
                        </a>
                    </li>
                    @foreach($organisers as $org)
                        <li>
                            <a href="{{route('showOrganiserDashboard', ['organiser_id' => $org->id])}}">
                                <i class="ico ico-building"></i> &nbsp;
                                {{$org->name}}
                            </a>

                        </li>
                    @endforeach
                    <li class="divider"></li>

                    <li>
                        <a data-href="{{route('showEditUser')}}" data-modal-id="EditUser"
                           class="loadModal editUserModal" href="javascript:void(0);"><span class="icon ico-user"></span>@lang("Top.my_profile")</a>
                    </li>
                    <li class="divider"></li>
                    <li><a data-href="{{route('showEditAccount')}}" data-modal-id="EditAccount" class="loadModal"
                           href="javascript:void(0);"><span class="icon ico-cog"></span>@lang("Top.account_settings")</a></li>


                    <li class="divider"></li>
                    <li><a target="_blank" href="https://github.com/Attendize/Attendize/issues/new?body=Version%20{{ config('attendize.version') }}"><span class="icon ico-megaphone"></span>@lang("Top.feedback_bug_report")</a></li>
                    <li class="divider"></li>
                    <li><a href="{{route('logout')}}"><span class="icon ico-exit"></span>@lang("Top.sign_out")</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>

@yield('menu')

<!--Main Content-->
<section id="main" role="main">
    <div class="container-fluid">
        <div class="page-title">
            <h1 class="title">@yield('page_title')</h1>
        </div>
        @if(array_key_exists('page_header', View::getSections()))
        <!--  header -->
        <div class="page-header page-header-block row">
            <div class="row">
                @yield('page_header')
            </div>
        </div>
        <!--/  header -->
        @endif
 
        @yield('content') 
    </div>

    <!--To The Top-->
    <a href="#" style="display:none;" class="totop"><i class="ico-angle-up"></i></a>
    <!--/To The Top-->

</section>
<!--/Main Content-->

<!--JS-->
@include("Shared.Partials.LangScript")
{!! HTML::script('assets/javascript/backend.js') !!} 

<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet"/>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>


{{-- <script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script> --}}

<script>

var table = $('.DataTable').DataTable({
		"order": [[ 1, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
	});

    $(".DataTable #filtros th").each( function ( i ) {  
		
		if ($(this).text() !== '') {
	        var isStatusColumn = (($(this).text() == 'Status') ? true : false);
			var select = $('<select class="form-control"><option value=""></option></select>')
	            .appendTo( $(this).empty() )
	            .on( 'change', function () {
	                var val = $(this).val();
					
	                table.column( i )
	                    .search( val ? '^'+$(this).val()+'$' : val, true, false )
	                    .draw();
	            } );
	 		
			// Get the Status values a specific way since the status is a anchor/image
			if (isStatusColumn) {
				var statusItems = [];
				
                /* ### IS THERE A BETTER/SIMPLER WAY TO GET A UNIQUE ARRAY OF <TD> data-filter ATTRIBUTES? ### */
				table.column( i ).nodes().to$().each( function(d, j){
					var thisStatus = $(j).attr("data-filter");
					if($.inArray(thisStatus, statusItems) === -1) statusItems.push(thisStatus);
				} );
				
				statusItems.sort();
								
				$.each( statusItems, function(i, item){
				    select.append( '<option value="'+item+'">'+item+'</option>' );
				});

			}
            // All other non-Status columns (like the example)
			else {
				table.column( i ).data().unique().sort().each( function ( d, j ) {  
					select.append( '<option value="'+d+'">'+d+'</option>' );
		        } );	
			}
	        
		}
    } );
   
</script>


<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    });

    @if(!Auth::user()->first_name)
      setTimeout(function () {
        $('.editUserModal').click();
    }, 1000);
    @endif

</script>
<!--/JS-->
@yield('foot')

@include('Shared.Partials.GlobalFooterJS')
</body>
</html>
