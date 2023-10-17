
<div id="Evento_COMPRANDO" class="dded" style="width:33.33%; vertical-align:top;">

    <div class="espacio_margen_fondo_blanco_top lateral_Evento_COMPRANDO">
        <div>

            @if(!is_null($event->imageCover())) 
            <img src="{{asset($event->imageCover())}}"
                class="imageneventocompra">
            @endif

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

                {{-- <div class="font14">MoRIDA</div> --}}
                <div class="font12">  
                    
                    {{-- {{ $event->start_date->format('l') }},  --}}
                    {{ $event->startDateFormatted() }}
                    
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
    <div class="espacio_margen_fondo_blanco lateral_Evento_COMPRANDO view-boleto">

        <div id="MapaTemplateCompra" style="margin:0px 0px 20px 0px;" class="font14"
            align="center">
            <div style="padding:10px 0px 5px 0px; margin:0px 5px 10px 5px">

                <img src="{{asset($event->map->url)}}" >

            </div>
        </div> 
    </div>
    <div class="espacio_margen_fondo_blanco div_i-Oculta_d-SoloSmartPhone lateral_Evento_COMPRANDO mt-3 view-mapa">
                       
        <!-- InstanceBeginEditable name="PreciosTemplateCompra" -->
        <div id="templateBoletoSeleccionado" style="display:none;">
            <div class="precio listadoboletos blancofondo borde_doble_grismuyclarofondo_bottom flex-container-between boldear codigo" style="padding:0px 5px"> 
                <div class="line-clamp-1 tipo" style="width:25%; padding:0px 2px; align-self:center; text-align:left"> 
                    Adulto 
                </div>
                <div class="fila" style="width:17%; padding:0px 2px; align-self:center"> 
                    A 
                </div> 
                <div class="asiento" style="width:17%; padding:0px 2px; align-self:center"> 
                    0 
                </div> 
                <div class="valor precio" style="width:35%; padding:0px 2px; align-self:center"> 
                    $ 00.00 
                </div> 
                <div style="width:6%; align-self:center"> 
                    <input data-ispromo="false" type="button" value="X" data-bs-dismiss="modal" class="closeButton font18 boldear800"> 
                </div> 
            </div>
        </div>
        <div>
            
            <div class="font11" style="text-align:left; margin:10px 0px">
                Evento sujeto a cargo x servicio , los cuales están incluidos en el precio listado.
            </div>    
            <div id="PreciosCompra" class="izquierdaText tb ancho_100">
                
                @foreach ($tickets_all as $key_tickets_all => $ticket_all)
                @php
                    $name_grup = str_replace(' ', '_', $key_tickets_all);
                @endphp

                @foreach ($ticket_all as $sectkey => $sect)
                    @php
                        $name_section = str_replace(' ', '_', $sectkey);
                    @endphp
 
                    <div id="24561" class="row" style="border-bottom:1px dashed #336699;">
                        <div id="24561" class="col-color-bg zn-color" style="width: 8px !important;"></div>
                        <div class="col font14 boldear600 alinearenmedio" style="padding: 5px;">{{ $sectkey }}</div>
                        <div class="col">
                            <div id="precios" class="tb ancho_100" style="text-align:left; margin-bottom:10px">
                                <div class="row font14">
                                    <div class="col ancho_50">
                                        <div data-toggle="popover" 
                                            data-trigger="hover" 
                                            data-placement="top" 
                                            data-html="true" 
                                            data-content="Precio: <b>$3,800.00</b><br>Cargos: <b>$595.00</b>" 
                                            style="text-align:center; cursor:pointer" 
                                            data-original-title="" title="">
                                            <strong>
                                                @foreach ($sect as $ticketskey => $tickets)
                                                    {{ money($tickets[0]->price, $event->currency) }}
                                                @endforeach</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                @endforeach
            @endforeach

            
                </div> 
            <div style="display:none">
                <!-- Plantilla para el nombre de bloque -->
                <div id="templateBloque">
                    <div id="mainrow" class="row" style="border-bottom:1px dashed #336699;">
                        <div id="sectionID" class="col-color-bg zn-color" style="width: 8px!important;"></div>
                        <div class="col_i-Desplegada_o-SmartPhone font14 boldear600 alinearenmedio" style="padding: 5px;">("sectionDescription")</div>
                        <div class="col">
                            <div id="precios" class="tb ancho_100" style="text-align:left; margin-bottom:10px">
                                <div class="row font14">
                                    <div id="typeDesc" class="col ancho_50 pe-2" style="padding:5px 0px 0px 0px">("typeDescription")</div>
                                    <div class="col ancho_50">
                                        <div data-toggle="popover" data-trigger="hover" data-placement="top" data-html="true" data-content="Precio: <b>[price]</b><br>Cargos: <b>[charge]</b>" style="text-align:center; cursor:pointer" data-original-title="" title="">
                                            <strong>("total")</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Plantilla para el precio con su respectivo popup -->
                <div id="templateMontos">
                    <div class="row font14">
                        <div id="MontoDesc" class="col ancho_50" style="padding:5px 0px 0px 0px">("typeDescription")</div>
                        <div class="col ancho_50">
                            <div data-toggle="popover" data-trigger="hover" data-placement="top" data-html="true" data-content="Precio: <b>[price]</b><br>Cargos: <b>[charge]</b>" style="text-align:center; cursor:pointer" data-original-title="" title="">
                                <strong>("total")</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Plantilla para el precio con su spinner -->
                <div id="templatePrecio">
                    <div class="precio contenedor">
                        <div class="tb ancho_100">
                            <div class="precio titulo col_i-Desplegada_inline-SmartPhone text_i_izq_cent_SmartPhone col_ancho33 alinearenmedio">
                                <span class="precio etiqueta boldear600 font16">ADULTO</span>&nbsp;                                                           
                            </div>
                            <div class="precio codigo col_i-Desplegada_inline-SmartPhone centrarTexto col_ancho33 alinearenmedio">
                                <div class="precio valor boldear800 azul font16 centrarTexto alinearenmedio" data-toggle="tooltip" title="" data-price-db="[total]" data-original-title="Importe: [price]" cargos:'[charge]'="">$0.00</div>
                                <div class="codigopromo_container"></div>
                            </div>
                            <div class="precio cantidad col_i-Desplegada_block-SmartPhone centrarTexto ">
                                <div class="spinbox_container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div id="templatePricing" class="izquierdaText tb ancho_100">
                    <div class="row" style="border-bottom:1px dashed #336699;">
                        <div class="col" style="width: 8px;">
                        </div>
                    </div>
                </div> 
                <div id="templateZona">
                    <div class="col font18 boldear600 alinearenmedio tipoBoleto" style="padding: 5px;">
                        Numerado
                    </div>
                </div>
                <div id="templateDiaEvento">
                    <div class="row font14">
                        <div class="col boldear600 azul font16 diaEventoValor">
                            Día del Evento
                        </div>
                    </div>
                </div>
                <div id="templatePrecioEvento">
                    <div class="row font14">
                        <div class="col ancho_50 nombreTipoBoleto" style="padding:5px 0px 0px 0px">
                            Adulto 
                        </div>
                        <div class="col ancho_50">
                            <div class="precioTipoBoleto" data-toggle="popover" data-trigger="hover" data-placement="top" data-html="true" data-content="" style="text-align:center; cursor:pointer" data-original-title="" title="">
                                <strong>
                                    $ 250.00
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="templatePrecioPadre">
                    <div class="tb ancho_100 diaEvento" style="text-align:left; margin:10px 0px">
                    </div>
                </div>
            </div>
        </div>                                           
        <!-- InstanceEndEditable -->  
                                          
    </div>
</div>