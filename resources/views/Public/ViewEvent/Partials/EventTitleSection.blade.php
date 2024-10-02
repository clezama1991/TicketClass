
<div class="div_i-Oculta_d-SoloSmartPhone">
    @if(!is_null($event->imageCover())) 
    <img src="{{asset($event->imageCover())}}"
        style="border: solid 5px #FFF; width:100%"> 
    @endif
</div>

<div style="margin:0px 0px 10px 0px; padding:0px 10px">
    <div class="flex-container-wrap">
        <div class="flexizq66a100compra">
            <div class="col alinearenmedio">
                <div class=" font font_i_30_12-SmartPhone boldear800 ">
                    {{$event->title}}
                </div>
                <div class=" font font_i_16_12-SmartPhone boldear600  mayusculas_primera">
                    <!--style="margin-top:10px"-->

                    {{-- {{ $event->start_date->format('l') }},  --}}
                     {{ $event->startDateFormattedGeneric('d/m/Y h:i A') }}
                    <span class="boldear800">
                        {{-- @if($event->start_date->diffInDays($event->end_date) == 0)
                                {{ $event->end_date->format('H:i') }}
                            @else
                                {{ $event->endDateFormatted() }}
                            @endif --}}
                    </span>


                </div>
                <div class="mayusculas_primera" style="margin-top:0px">
                    <div class="font font_i_18_12-SmartPhone boldear600">{{$event->venue_name}}</div>
                    <br>
                    {{-- <div class="font"> Presentado Por:<b>{{$event->organiser->name}}</b> </div> --}}
                </div>
            </div>
            <div class="divimageneventocompra alinearenmedio" style="width:310px; padding:10px 0px">
                @if(!is_null($event->imageCover())) 
                <img src="{{asset($event->imageCover())}}"
                    style="border: solid 5px #FFF; width:300px">
                @endif
            </div>
        </div>
    </div>
</div>
