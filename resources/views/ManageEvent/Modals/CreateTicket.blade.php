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
                                                    <?php                     
                                                        $zonas_disponibles = $event->seat_zone_ocupadas();
                                                     ?>
                                                    @foreach($event->sections_map() as $key => $map_section)
                                                    <area data-seat="{{ in_array($map_section->combine,$zonas_disponibles) ? 'sold' : "
                                                        x".$map_section->combine }}" {{-- data-seat="x{{ $key}}" --}}
                                                        data-seatzone="{{ $map_section->combine}}" class="selected_zone" alt="{{ $map_section->combine }}"
                                                        title="{{ $map_section->combine }}" href="#" shape="{{$map_section->shape}}" coords="{{$map_section->coords}}">
                                                    @endforeach
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
                                    {!! Form::label('group', trans("ManageEvent.group"),
                                    array('class'=>' control-label required')) !!}
                                    {!! Form::text('group_zone', Input::old('group_zone'),
                                    array(
                                    'class'=>'form-control',
                                    'placeholder'=> 'General Por Defecto'
                                    )
                                    ) !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('is_hidden', 1, false, ['id' => 'is_hidden']) !!}
                                        {!! Form::label('is_hidden', trans("ManageEvent.hide_this_ticket"),
                                        array('class'=>' control-label')) !!}
                                    </div>

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