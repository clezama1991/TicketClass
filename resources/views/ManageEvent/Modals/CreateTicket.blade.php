<div role="dialog" class="modal fade" style="display: none;">
    {!! Form::open(array('url' => route('postCreateTicket', array('event_id' => $event->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    @lang("ManageEvent.create_ticket")
                </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    {!! Form::label('title', trans("ManageEvent.ticket_title"),
                                    array('class'=>'control-label required')) !!}
                                    {!! Form::text('title', Input::old('title'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>trans("ManageEvent.ticket_title_placeholder")
                                    )) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('title', trans("ManageEvent.ticket_section"),
                                    array('class'=>'control-label required')) !!}
                                    {!! Form::text('seat_zone', Input::old('seat_zone'),
                                    array(
                                    'class'=>'form-control show-mapa-entrada disabled',
                                    'placeholder'=>trans("ManageEvent.ticket_title_placeholder"),
                                    'readonly' => 'true'
                                    )) !!}
                                    <p class="text-muted">Debe seleccionar en el mapa la sección</p>
                                </div>
                            </div>
                            <div class="col-md-12 mapa-entrada" style="padding-bottom: 20px">
                                <h3 style="display: flex;justify-content: space-between;">Secciones disponibles para
                                    este evento...

                                    <a class="btn btn-primary btn-sm hide-mapa-entrada">× Cerrar</a>

                                </h3>
                                <section class="section-page-content" id="map-event" style="padding-top: 10px">
                                    <div class=" ">
                                        <div class="row">
                                            <div id="" class="col-md-12" style="display: flex;justify-content: center;">
                                                <img id="hall-seat-plan" src="{{ asset($event->image_map()) }}"
                                                    alt="stage" usemap="#map" />
                                                <map name="map" class="seatmap">
                                                    <!-- SEAT A -->

                                                    <?php $coords = []; ?>
                                                    @foreach ($event->sections_map() as $item => $value)
                                                    <?php $coords[$item] = $value; ?>
                                                    @endforeach
                                                    <?php                     
                                                        $zonas_disponibles = $event->seat_zone_ocupadas();
                                                     ?>
                                                    @foreach($coords as $key => $ticket)
                                                    <area data-seat="{{ in_array($key,$zonas_disponibles) ? 'sold' : "
                                                        x".$key }}" {{-- data-seat="x{{ $key}}" --}}
                                                        data-seatzone="{{ $key}}" class="selected_zone" alt="{{ $key }}"
                                                        title="{{ $key }}" href="#" shape="poly" coords="{{$ticket}}">
                                                    @endforeach

                                                    {{-- <area data-seat="a2"
                                                        coords="212,241,224,227,240,212,220,179,202,195,190,209,180,223">
                                                    <area data-seat="a3 coords="
                                                        264,199,283,192,302,189,301,150,273,156,243,168">

                                                    <!-- SEAT C -->
                                                    <area
                                                        dcoords="0,302,38,302,39,282,43,261,48,241,53,221,60,205,68,187,36,168,26,187,20,205,14,224,8,243,5,262,2,281">
                                                    <area data-seat="c2" alt="c2" href="#" shape="poly"
                                                        coords="146,50,132,59,117,70,102,83,88,96,75,110,63,125,53,139,49,146,82,165,93,150,107,133,125,112,142,97,166,80">
                                                    <area data-seat="sold" alt="c3" href="#" shape="poly"
                                                        coords="186,68,219,55,250,46,280,40,302,38,300,1,263,4,234,11,199,22,168,36">
                                                    <area data-seat="sold" alt="c4" href="#" shape="poly"
                                                        coords="326,0,327,39,347,40,365,42,383,46,402,52,417,58,440,69,460,37,444,28,422,19,396,10,374,5,351,2">
                                                    <area data-seat="c5" alt="c5" href="#" shape="poly"
                                                        coords="464,81,481,94,500,110,514,124,532,144,547,164,580,146,568,130,552,109,530,86,504,63,482,49">
                                                    <area data-seat="c6" alt="c6" href="#" shape="poly"
                                                        coords="560,187,568,204,574,222,580,241,586,261,588,278,589,301,627,301,627,282,624,260,619,235,612,212,604,191,592,168">
                                                    --}}
                                                </map>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row">
                            

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', trans("ManageEvent.ticket_price_neto"),
                                    array('class'=>'control-label required')) !!}
                                    {!! Form::text('price_neto', Input::old('price'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>trans("ManageEvent.price_placeholder")
                                    )) !!}


                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', trans("ManageEvent.ticket_price_service"),
                                    array('class'=>'control-label required')) !!}
                                    {!! Form::text('price_service', Input::old('price'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>trans("ManageEvent.price_placeholder")
                                    )) !!}


                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', trans("ManageEvent.ticket_price_paypal"),
                                    array('class'=>'control-label required')) !!}
                                    {!! Form::text('price_paypal', Input::old('price'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>trans("ManageEvent.price_placeholder")
                                    )) !!}


                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', trans("ManageEvent.ticket_price"),
                                    array('class'=>'control-label required')) !!}
                                    <input class="form-control" placeholder="P.ej.: 25,99" name="price" type="text" id="price" value="0" readonly>


                                </div>
                            </div>
                            
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('quantity_available', trans("ManageEvent.quantity_available"),
                                    array('class'=>' control-label required')) !!}
                                    {!! Form::text('quantity_available', Input::old('quantity_available'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>trans("ManageEvent.quantity_available_placeholder")
                                    )
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="" style="color: #fff">.</label>
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('select_seat', 1, false, ['id' => 'select_seat']) !!}
                                        {!! Form::label('select_seat', '¿ Seleccionar Asientos ?', array('class'=>'
                                        control-label')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group asientos hide">
                                    {!! Form::label('quantity_row', 'Cantidad x Filas', array('class'=>' control-label
                                    ')) !!}
                                    {!! Form::text('quantity_row', Input::old('quantity_row'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>'Ej: 30-40-50-89'
                                    )
                                    ) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group asientos hide">
                                    {!! Form::label('asientos_blanco', 'Asientos en Blanco', array('class'=>'
                                    control-label ')) !!}
                                    {!! Form::text('asientos_blanco', Input::old('asientos_blanco'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=>'Ej: 1-2-20-4'
                                    )
                                    ) !!}
                                </div>
                            </div>

                        </div>

                        <div class="form-group more-options">
                            {!! Form::label('description', trans("ManageEvent.ticket_description"),
                            array('class'=>'control-label')) !!}
                            {!! Form::text('description', Input::old('description'),
                            array(
                            'class'=>'form-control'
                            )) !!}
                        </div>
                        <div class="row more-options">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('start_sale_date', trans("ManageEvent.start_sale_on"),
                                    array('class'=>' control-label')) !!}
                                    {!! Form::text('start_sale_date', Input::old('start_sale_date'),
                                    [
                                    'class'=>'form-control start hasDatepicker ',
                                    'data-field'=>'datetime',
                                    'data-startend'=>'start',
                                    'data-startendelem'=>'.end',
                                    'readonly'=>''

                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    {!! Form::label('end_sale_date', trans("ManageEvent.end_sale_on"),
                                    [
                                    'class'=>' control-label '
                                    ]) !!}
                                    {!! Form::text('end_sale_date', Input::old('end_sale_date'),
                                    [
                                    'class'=>'form-control end hasDatepicker ',
                                    'data-field'=>'datetime',
                                    'data-startend'=>'end',
                                    'data-startendelem'=>'.start',
                                    'readonly'=>''
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row more-options">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('min_per_person', trans("ManageEvent.minimum_tickets_per_order"),
                                    array('class'=>' control-label')) !!}
                                    {!! Form::selectRange('min_per_person', 1, 100, 1, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('max_per_person', trans("ManageEvent.maximum_tickets_per_order"),
                                    array('class'=>' control-label')) !!}
                                    {!! Form::selectRange('max_per_person', 1, 100, 100, ['class' => 'form-control'])
                                    !!}
                                </div>
                            </div>
                        </div>
                        <div class="row more-options">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('is_hidden', 1, false, ['id' => 'is_hidden']) !!}
                                        {!! Form::label('is_hidden', trans("ManageEvent.hide_this_ticket"),
                                        array('class'=>' control-label')) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="show-more-options">
                            @lang("ManageEvent.more_options")
                        </a>
                    </div>

                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal'])
                !!}
                {!! Form::submit(trans("ManageEvent.create_ticket"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
        {!! Form::close() !!}
    </div>
</div>

{!!HTML::script('assets/javascript/bootstrap-slider.min.js')!!}
{!!HTML::script('assets/javascript/jquery.flexslider-min.js')!!}
{!!HTML::script('assets/javascript/jquery.imagemapster.min.js')!!}
{!!HTML::script('assets/javascript/tooltip.js')!!}
{!!HTML::script('assets/javascript/main.js')!!}
<script>
    $(".mapa-entrada").hide();
    
        $(".show-mapa-entrada").on('click', function (e)
        {
                e.preventDefault();
                $(".mapa-entrada").fadeIn().removeClass("hide");
        });
        $(".hide-mapa-entrada").on('click', function (e)
        {
                e.preventDefault();
                $(".mapa-entrada").fadeOut().addClass("hide");
        });

        $(".selected_zone").on('click', function (e)
        {
                e.preventDefault();
                var seat = $(this).data("seat");
                if(seat!='sold'){

                    $(".mapa-entrada").fadeOut().addClass("hide");
                    var seatzone = $(this).data("seatzone");

                    $("input[name='seat_zone']").val(seatzone.toUpperCase());
                }

        });







        $("#select_seat").on('change', function (e)
        {
            e.preventDefault();
            if($(this).prop('checked'))
            {
                $("#quantity_row").focus();
                $(".asientos").fadeIn().removeClass("hide");
            }
            else
            {
                $(".asientos").fadeOut().addClass("hide");
            }
        });
</script>