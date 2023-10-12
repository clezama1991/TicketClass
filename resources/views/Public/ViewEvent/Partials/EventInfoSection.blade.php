
<div id="Evento_COMPRANDO" style="width:33.33%; vertical-align:top;">

    <div class="espacio_margen_fondo_blanco_top lateral_Evento_COMPRANDO">
        <div>

            <img src="{{asset($event->images[0]->image_path)}}"
                class="imageneventocompra">

        </div>
        <!-- inicia: Informacion Evento -->
        <div align="center" style="padding-top:10px">
            <div class="grisfondo" style="margin:5px auto 10px auto">
                <hr>
                {{-- <img src="{{asset('assets/images/spacer.gif')}}" height="1" width="100%"> --}}
            </div>
            <div style="margin:10px 0px 0px 0px; padding:0px 0px 10px 0px" class="font14" align="center">
                <div class="font18 boldear">{{$event->title}}</div>

                <div class="font14">{{$event->venue_name}}</div>

                <div class="font14">MoRIDA</div>
                <div class="font12">  
                    
                    {{ $event->start_date->format('l') }}, {{ $event->startDateFormatted() }}
                    
                    <span class="boldear800">@if($event->start_date->diffInDays($event->end_date) == 0)
                        {{ $event->end_date->format('H:i') }}
                    @else
                        {{ $event->endDateFormatted() }}
                    @endif</span>

                </div>
            </div>
        </div>
        <!-- termina: Informacion Evento -->

    </div>
    <!-- InstanceBeginEditable name="BoletosSeleccionados" -->
    <!-- InstanceEndEditable -->
    <div class="espacio_margen_fondo_blanco lateral_Evento_COMPRANDO">

        <div id="MapaTemplateCompra" style="margin:0px 0px 20px 0px;" class="font14"
            align="center">
            <div style="padding:10px 0px 5px 0px; margin:0px 5px 10px 5px">

                <img src="{{asset($event->map->url)}}" >

            </div>
        </div>

        <!-- InstanceBeginEditable name="PreciosTemplateCompra" -->
        <!-- InstanceEndEditable -->

    </div>

</div>