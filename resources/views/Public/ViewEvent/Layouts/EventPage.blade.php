<!DOCTYPE html>
<html lang="en">
    <head>

        <title>{{{$event->title}}} - Ticketmatico</title>


        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" />
        <link rel="canonical" href="{{$event->event_url}}" />

        {!!HTML::style('assets/stylesheet/select-seat.css')!!}
        {!!HTML::style('assets/stylesheet/bootstrap-slider.min.css')!!}

        <!-- Open Graph data -->
        <meta property="og:title" content="{{{$event->title}}}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{$event->event_url}}?utm_source=fb" />
        @if($event->images->count())
        <meta property="og:image" content="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" />
        @endif
        <meta property="og:description" content="{{Str::words(strip_tags(Markdown::parse($event->description))), 20}}" />
        <meta property="og:site_name" content="ticketmatico.com" />
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('head')

       {!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}

        <!--Bootstrap placeholder fix-->
        <style>
            ::-webkit-input-placeholder { /* WebKit browsers */
                color:    #ccc !important;
            }
            :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                color:    #ccc !important;
                opacity:  1;
            }
            ::-moz-placeholder { /* Mozilla Firefox 19+ */
                color:    #ccc !important;
                opacity:  1;
            }
            :-ms-input-placeholder { /* Internet Explorer 10+ */
                color:    #ccc !important;
            }

            input, select {
                color: #999 !important;
            }

            .btn {
                color: #fff !important;
            }

        </style>
        @if ($event->bg_type == 'color' || Input::get('bg_color_preview'))
            <style>body {background-color: {{(Input::get('bg_color_preview') ? '#'.Input::get('bg_color_preview') : $event->bg_color)}} !important; }</style>
        @endif

        @if (($event->bg_type == 'image' || $event->bg_type == 'custom_image' || Input::get('bg_img_preview')) && !Input::get('bg_color_preview'))
            <style>
                body {
                    background: url({{(Input::get('bg_img_preview') ? URL::to(Input::get('bg_img_preview')) :  asset(config('attendize.cdn_url_static_assets').'/'.$event->bg_image_path))}}) no-repeat center center fixed;
                    background-size: cover;
                }
            </style>
        @endif

    </head>
    <body class="attendize">
        <div id="event_page_wrap" vocab="http://schema.org/" typeof="Event">
            @yield('content')

            {{-- Push for sticky footer--}}
            @stack('footer')
        </div>

        {{-- Sticky Footer--}}
        @yield('footer')

        <a href="#intro" style="display:none;" class="totop"><i class="ico-angle-up"></i>
            <span style="font-size:11px;">@lang("basic.TOP")</span></a>

        @include("Shared.Partials.LangScript")
        {!!HTML::script(config('attendize.cdn_url_static_assets').'/assets/javascript/frontend.js')!!}


        @if(isset($secondsToExpire))
        <script>if($('#countdown')) {setCountdown($('#countdown'), {{$secondsToExpire}});}</script>
        @endif

        @include('Shared.Partials.GlobalFooterJS')
    </body>
    
    {!!HTML::script('assets/javascript/bootstrap-slider.min.js')!!}
        {!!HTML::script('assets/javascript/jquery.flexslider-min.js')!!}
        {!!HTML::script('assets/javascript/jquery.imagemapster.min.js')!!}
        {!!HTML::script('assets/javascript/tooltip.js')!!}
        {!!HTML::script('assets/javascript/main.js')!!}
    <script>
        var asientos = [];
        var asientos_id = [];
        var nro_asientos = 0;
        $('.zone-events').hide();
        $(".selected_zone").on('click', function (e)
        {
            
            var seat = $(this).data("seat");
            console.log("🚀 ~ file: EmbeddedEventPage.blade.php:97 ~ seat", seat)
            
            if(seat!='sold'){
                //$('#map-event').hide();
                $('#zone-event-'+seat).show();
            }
 


        });
        $(".selected_zone_map").on('click', function (e)
        {
            
            var seat = $(this).data("seatzone");
            console.log("🚀 ~ file: EmbeddedEventPage.blade.php:97 ~ seat", seat)
            
            if(seat!='sold'){
                //$('#map-event').hide();
                $('#zone-event-'+seat).show();
            }
 


        });

        $(".back").on('click', function (e)
        {
             
                // $('#map-event').show();
                $('.zone-events').hide(); 


        });



        $(".select-nro-seat").on('change', function (e)
        {
            var ticket_id = $(this).data("id");
            console.log(ticket_id);
            var nro_asientos = $("select[name='ticket_"+ticket_id+"']").val();
            var seatzone = $(this).data("seatzone");
            $(".ver_asientos_marcados_seccion_comprar_zona_"+seatzone).html(nro_asientos);
        });

        $(".seatSelect").on('click', function (e)
        {
            e.preventDefault();
            
            if($(this).hasClass("btns-danger"))
            {
                return false;
            }
            var id = $(this).attr("id");
            console.log("🚀 ~ file: EventTicketsSection.blade.php:218 ~ id", id)
            
            var asiento = $(this).data("asiento");
            var ticket_id = $(this).data("ticket");
            var seatzone = $(this).data("seatzone");
            console.log("🚀 ~ file: EmbeddedEventPage.blade.php:128 ~ seatzone", seatzone)
            
            var remove, add, status, set_asientos;

            var nro_asientos = $("input[name='ticket_"+ticket_id+"']").val();
            // var asientos_id = [$("input[name='asientos_"+ticket_id+"']").val()];


            if($(this).hasClass("btns-info"))
            {
                remove = "btns-info";
                add = "btns-warning";
                status = 0;
                asientos.push(asiento);
                asientos_id.push(id);
                nro_asientos++;
                $("#"+id).removeClass(remove).addClass(add);
                $(".asiento_"+id).attr('fill','#FFD66A');
            }
            else
            {
                $(".asiento_"+id).attr('fill','#5495D2');




                remove = "btns-warning";
                add = "btns-info";
                status = 1;
                nro_asientos--;
                $("#"+id).removeClass(remove).addClass(add);
                asientos = asientos.filter(function(item) {
                    return item !== asiento
                })
                asientos_id = asientos_id.filter(function(item) {
                    return item !== id
                })
            }

            if(nro_asientos>0){
                $("#asientos_marcados").show();
            }else{
                
                $("#asientos_marcados").hide();
            }


            $("#set_asiento").html(asientos.toString());
            $("#asientos_marcados").val(asientos.toString());
            $("input[name='ticket_"+ticket_id+"']").val(nro_asientos);
            $("input[name='asientos']").val(asientos_id.toString());
            $("input[name='asientos_"+ticket_id+"']").val(asientos_id.toString());
            $("input[name='asientos_marcados"+ticket_id+"']").val(asientos_id.toString());
            $("input[name='asientos_marcados']").val(asientos_id.toString());
            
            $(".ver_asientos_marcados_seccion_comprar_zona_"+seatzone).html(nro_asientos);

        });
        
    </script>
</html>










